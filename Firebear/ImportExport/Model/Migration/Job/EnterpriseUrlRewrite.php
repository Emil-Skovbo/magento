<?php
/**
 * @copyright: Copyright © 2020 Firebear Studio. All rights reserved.
 * @author   : Firebear Studio <fbeardev@gmail.com>
 */

namespace Firebear\ImportExport\Model\Migration\Job;

use Firebear\ImportExport\Model\Migration\AdditionalOptions;
use Firebear\ImportExport\Model\Migration\Config;
use Symfony\Component\Console\Output\OutputInterface;
use Firebear\ImportExport\Model\Migration\DbConnection;
use Firebear\ImportExport\Model\Migration\Field\Job\MapStoreId;
use Firebear\ImportExport\Model\Migration\FilterJobs\StoreId;
use Firebear\ImportExport\Model\Migration\JobInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * @inheritdoc
 */
class EnterpriseUrlRewrite implements JobInterface
{
    /**
     * @var DbConnection
     */
    private $dbConnection;

    /**
     * @var StoreId
     */
    private $storeIdFilter;

    /**
     * @var MapStoreId
     */
    private $storeIdMapper;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param DbConnection $dbConnection
     * @param Config $config
     * @param MapStoreId $storeIdMapper
     * @param StoreId $storeIdFilter
     */
    public function __construct(
        DbConnection $dbConnection,
        Config $config,
        StoreId $storeIdFilter,
        MapStoreId $storeIdMapper
    ) {
        $this->dbConnection = $dbConnection;
        $this->config = $config;
        $this->storeIdFilter = $storeIdFilter;
        $this->storeIdMapper = $storeIdMapper;
    }

    /**
     * @return DbConnection
     */
    protected function getDbConnection()
    {
        return $this->dbConnection;
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     * @throws \Zend_Db_Statement_Exception
     */
    public function job($output, $additionalOptions = null)
    {
        $source = $this->getDbConnection()->getSourceChannel();
        $destination = $this->getDbConnection()->getDestinationChannel();

        $sourceSelect = $source->select()
            ->from($this->config->getM1Prefix() . 'enterprise_url_rewrite', [
                'entity_type' => new \Zend_Db_Expr(
                    "IF(category_id, 'category', IF(product_id, 'product', null))"
                ),
                'entity_id' => new \Zend_Db_Expr(
                    'IF(category_id, category_id, IF(product_id, product_id, null))'
                ),
                'request_path' => 'request_path',
                'target_path' => 'target_path',
                'store_id' => 'store_id',
                'is_autogenerated' => 'is_system',
                'metadata' => new \Zend_Db_Expr('null'),
            ])->join(
                ['s' => $this->config->getM1Prefix() . 'enterprise_url_rewrite_redirect_rewrite'],
                $this->config->getM1Prefix() . 'enterprise_url_rewrite.url_rewrite_id = s.url_rewrite_id',
                []
            )->join(
                ['b' => $this->config->getM1Prefix() . 'enterprise_url_rewrite_redirect'],
                's.redirect_id = b.redirect_id',
                [
                    'redirect_type' => new \Zend_Db_Expr(
                        "CASE b.options WHEN 'RP' THEN 301 WHEN 'R' THEN 302 ELSE 0 END"
                    ),
                    'description' => 'b.description'
                ]
            )
            ->where($this->config->getM1Prefix() . 'enterprise_url_rewrite.is_system = ?', 0);

        $this->storeIdFilter->apply($this->config->getM1Prefix() . 'enterprise_url_rewrite.store_id', $sourceSelect);
        $sourceData = $source->query($sourceSelect);

        $destinationRows = [];
        while ($sourceDataRow = $sourceData->fetch()) {
            $requestPath = $sourceDataRow['request_path'];
            $targetPath = $sourceDataRow['target_path'];
            if (!preg_match('/^.+-(\d+)\/$/', $requestPath)) {
                $existingSelect = $destination->select()
                    ->from($this->config->getM2Prefix() . 'url_rewrite', ['url_rewrite_id'])
                    ->where('request_path = ?', $requestPath)
                    ->where('target_path = ?', $targetPath);

                $existingId = $destination->fetchOne($existingSelect);

                if (!$existingId) {
                    $sourceDataRow['url_rewrite_id'] = null;
                } else {
                    $sourceDataRow['url_rewrite_id'] = $existingId;
                }

                $sourceDataRow['store_id'] = $this->storeIdMapper->job(
                    'store_id',
                    $sourceDataRow['store_id'],
                    'store_id',
                    $sourceDataRow['store_id'],
                    $sourceDataRow
                );

                $destinationRows[] = $sourceDataRow;
            }
        }

        foreach (array_chunk($destinationRows, 1000) as $destinationBatch) {
            $this->getDbConnection()->getDestinationChannel()->insertOnDuplicate(
                $this->config->getM2Prefix() . 'url_rewrite',
                $destinationBatch
            );
        }
    }
}

<?php

declare(strict_types=1);

namespace Ereactor\Slider\Model\Slider;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\ReadInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Ereactor\Slider\Model\ResourceModel\Slider\Collection;
use Ereactor\Slider\Model\Slider;
use Ereactor\Slider\Model\ResourceModel\Slider\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;
    /**
     * @var ReadInterface
     */
    protected $mediaDirectory;
    /**
     * @var array|null
     */
    protected $loadedData;
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        SerializerInterface $serializer,
        Filesystem $filesystem,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->serializer = $serializer;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            /** @var Slider $model */
            $modelData = $model->getData();
            if ($model->getImagePath() && $this->mediaDirectory->isExist($model->getImagePath())) {
                $modelData['image'] = [[
                    'name' => basename($model->getImagePath()),
                    'url' => $model->getImageUrl(),
                    'type' => 'image',
                    'size' => filesize($this->mediaDirectory->getAbsolutePath($model->getImagePath()))
                ]];
            } else {
                $modelData['image'] = null;
            }

            if ($model->getKeywords()) {
                $modelData['keywords'] = $this->serializer->unserialize($model->getKeywords());
            }

            $this->loadedData[$model->getId()] = $modelData;
        }
        $data = $this->dataPersistor->get('ereactor_slider_slider');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('ereactor_slider_slider');
        }

        return $this->loadedData;
    }
}

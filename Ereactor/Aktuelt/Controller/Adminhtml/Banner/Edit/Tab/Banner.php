<?php

namespace Ereacto\aktuelt\Block\Adminhtml\Banner\Edit\Tab;


/**
 * Banner Edit tab.
 * @category WeltPixel
 * @package  WeltPixel_OwlCarouselSlider
 * @module   OwlCarouselSlider
 * @author   WeltPixel Developer
 */
class Banner extends \Magento\Backend\Block\Widget\Form\Generic
    implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * @var \Magento\Framework\DataObjectFactory
     */
    protected $_objectFactory;

    /**
     * slider factory.
     *
     * @var \WeltPixel\OwlCarouselSlider\Model\SliderFactory
     */
    protected $_sliderFactory;

    /**
     * @var \WeltPixel\OwlCarouselSlider\Model\Banner
     */
    protected $_bannerModel;

    /**
     * available status.
     *
     * @var \WeltPixel\OwlCarouselSlider\Model\Status
     */
    private $_status;

    /**
     * constructor.
     *
     * @param \Magento\Backend\Block\Template\Context                                  $context
     * @param \Magento\Framework\Registry                                              $registry
     * @param \Magento\Framework\Data\FormFactory                                      $formFactory
     * @param \Magento\Framework\DataObjectFactory                                     $objectFactory
     * @param \WeltPixel\OwlCarouselSlider\Model\Banner                                $banner
     * @param \WeltPixel\OwlCarouselSlider\Model\SliderFactory                         $sliderFactory
     * @param \WeltPixel\OwlCarouselSlider\Model\Status                                $status
     * @param array                                                                    $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        \Magento\Framework\DataObjectFactory $objectFactory,
        \WeltPixel\OwlCarouselSlider\Model\Banner $bannerModel,
        \WeltPixel\OwlCarouselSlider\Model\SliderFactory $sliderFactory,
        \WeltPixel\OwlCarouselSlider\Model\Status $status,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);

        $this->_objectFactory = $objectFactory;
        $this->_bannerModel   = $bannerModel;
        $this->_sliderFactory = $sliderFactory;
        $this->_status        = $status;
    }

    /**
     * prepare layout.
     *
     * @return $this
     */
    protected function _prepareLayout()
    {
        $pageTitle = $this->getPageTitle();

        $this->getLayout()->getBlock('page.title')->setPageTitle($pageTitle);

        \Magento\Framework\Data\Form::setFieldsetElementRenderer(
            $this->getLayout()->createBlock(
                'WeltPixel\OwlCarouselSlider\Block\Adminhtml\Form\Renderer\Fieldset\Element', $this->getNameInLayout()
                .'_fieldset_element'
            )
        );

        return $this;
    }

    /**
     * Prepare form.
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $dataObj = $this->_objectFactory->create();

        /**
         * @var \WeltPixel\OwlCarouselSlider\Model\Banner $bannerModel
         */
        $bannerModel = $this->_coreRegistry->registry('banner');

        if ($sliderId = $this->getRequest()->getParam('loaded_slider_id')) {
            $bannerModel->setSliderId($sliderId);
        }

        $dataObj->addData($bannerModel->getData());

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();

        $form->setHtmlIdPrefix($this->_bannerModel->getFormFieldHtmlIdPrefix());

        $htmlIdPrefix = $form->getHtmlIdPrefix();

        $fieldset = $form->addFieldset('base_fieldset', ['legend' => __('Banner Details')]);

        if ($bannerModel->getId()) {
            $fieldset->addField('id', 'hidden', ['name' => 'id']);
        }

        $elements = [];

        $elem0ents['title'] = $fieldset->addField(
            'title',
            'text',
            [
                'name'     => 'title',
                'label'    => __('Title'),
                'title'    => __('Title'),
                'required' => true,
            ]
        );

        $dateFormat = $this->_localeDate->getDateFormatWithLongYear();
        $timeFormat = $this->_localeDate->getTimeFormat();

        if ($dataObj->hasData('valid_from')) {
            $datetime = new \DateTime($dataObj->getData('valid_from'));
            $dataObj->setData('valid_from',
                $datetime->setTimezone(new \DateTimeZone($this->_localeDate->getConfigTimezone())));
        }

        if ($dataObj->hasData('valid_to')) {
            $datetime = new \DateTime($dataObj->getData('valid_to'));
            $dataObj->setData('valid_to',
                $datetime->setTimezone(new \DateTimeZone($this->_localeDate->getConfigTimezone())));
        }

        $style = 'color: #000;background-color: #fff; font-weight: bold; font-size: 13px;';
        $elements['valid_from'] = $fieldset->addField(
            'valid_from',
            'date',
            [
                'name'        => 'valid_from',
                'label'       => __('Banner Valid From'),
                'title'       => __('Valid From'),
                'required'    => true,
                'readonly'    => true,
                'style'       => $style,
                'class'       => 'required-entry',
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'note'        => implode(' ', [$dateFormat, $timeFormat]) . '<br/>The banner is displayed from selected date and time.' .
                            '<br/><b>For schedules to work make sure to enable Ajax Scheduled Banners from Slider Details.</b>',
            ]
        );

        $elements['valid_to'] = $fieldset->addField(
            'valid_to',
            'date',
            [
                'name'        => 'valid_to',
                'label'       => __('Banner Valid To'),
                'title'       => __('Valid To'),
                'required'    => true,
                'readonly'    => true,
                'style'       => $style,
                'class'       => 'required-entry',
                'date_format' => $dateFormat,
                'time_format' => $timeFormat,
                'note'        => implode(' ', [$dateFormat, $timeFormat]) . '<br/>The Banner is displayed until selected date and time.' .
                    '<br/><b>For schedules to work make sure to enable Ajax Scheduled Banners from Slider Details.</b>',
            ]
        );

        $form->addValues($dataObj->getData());

        $this->setChild(
            'form_after',
            $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Form\Element\Dependence')
                ->addFieldMap(
                    "{$htmlIdPrefix}banner_type",
                    'banner_type'
                )
                ->addFieldMap(
                    "{$htmlIdPrefix}video",
                    'video'
                )
                ->addFieldMap(
                    "{$htmlIdPrefix}image",
                    'image'
                )
                ->addFieldMap(
                    "{$htmlIdPrefix}mobile_image",
                    'mobile_image'
                )
                ->addFieldMap(
                    "{$htmlIdPrefix}custom",
                    'custom'
                )
                ->addFieldMap(
                    "{$htmlIdPrefix}alt_text",
                    'alt_text'
                )
                ->addFieldDependence(
                    'image',
                    'banner_type',
                    '1'
                )
                ->addFieldDependence(
                    'mobile_image',
                    'banner_type',
                    '1'
                )
                ->addFieldDependence(
                    'alt_text',
                    'banner_type',
                    '1'
                )
                ->addFieldDependence(
                    'video',
                    'banner_type',
                    '2'
                )
                ->addFieldDependence(
                    'custom',
                    'banner_type',
                    '3'
                )
        );

        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Retrieve the banner model.
     *
     * @return \WeltPixel\OwlCarouselSlider\Model\Banner
     */
    public function getBanner()
    {
        return $this->_coreRegistry->registry('banner');
    }

    /**
     * Return the page title.
     *
     * @return \Magento\Framework\Phrase
     */
    public function getPageTitle()
    {
        return $this->getBanner()->getId()
            ? __("Edit Banner '%1'", $this->escapeHtml($this->getBanner()->getTitle())) : __('New Banner');
    }

    /**
     * Prepare tab label.
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Banner Details');
    }

    /**
     * Prepare tab title.
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Banner Details');
    }

    /**
     * Can show tab in tabs.
     *
     * @return boolean
     * @api
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Tab is hidden.
     *
     * @return boolean
     * @api
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Checks if Google Analytics Tracking for banners is enabled
     *
     * @return boolean
     */
    public function showGaFields()
    {
        $sysPath = 'weltpixel_owl_slider_config/general/enable_google_tracking';
        return $this->_scopeConfig->getValue($sysPath, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}

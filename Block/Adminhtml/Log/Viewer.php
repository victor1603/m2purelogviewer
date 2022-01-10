<?php

namespace CodeCustom\PureLogViewer\Block\Adminhtml\Log;

use Magento\Framework\View\Element\Template;
use CodeCustom\PureLogViewer\Model\ResourceModel\LogPath\CollectionFactory;
use Magento\Framework\Data\Form\FormKey;

class Viewer extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var FormKey
     */
    protected $formKey;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collection,
        array $data = [],
        FormKey $formKey
    )
    {
        $this->collectionFactory = $collection;
        $this->formKey = $formKey;
        parent::__construct($context, $data);
    }

    /**
     * @return array
     */
    public function getLogPaths()
    {
        $colection = $this->collectionFactory->create();
        $result = [];

        if ($colection->getSize()) {
            foreach ($colection as $item) {
                $item['is_file'] = strpos($item['path'], '.log') === false ? '0' : '1';
                $result[] = $item;
            }
        }

        return $result;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFormKey()
    {
        return $this->formKey->getFormKey();
    }

    /**
     * @return string
     */
    public function getLogListUrl()
    {
        return $this->getUrl('purelog/logviewer/ajax_loglist');
    }

    /**
     * @return string
     */
    public function getLogReaderUrl()
    {
        return $this->getUrl('purelog/logviewer/ajax_logreader');
    }

    /**
     * @return string
     */
    public function getDownloadUrl()
    {
        return $this->getUrl('purelog/logviewer/download');
    }
}

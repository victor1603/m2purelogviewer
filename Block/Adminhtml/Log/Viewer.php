<?php

namespace CodeCustom\PureLogViewer\Block\Adminhtml\Log;

use Magento\Framework\View\Element\Template;
use CodeCustom\PureLogViewer\Model\ResourceModel\LogPath\CollectionFactory;

class Viewer extends Template
{
    protected $collectionFactory;

    public function __construct(
        Template\Context $context,
        CollectionFactory $collection,
        array $data = []
    )
    {
        $this->collectionFactory = $collection;
        parent::__construct($context, $data);
    }

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

    public function getLogListUrl()
    {
        return $this->getUrl('purelog/logviewer/ajax_loglist');
    }

    public function getLogReaderUrl()
    {
        return $this->getUrl('purelog/logviewer/ajax_logreader');
    }
}

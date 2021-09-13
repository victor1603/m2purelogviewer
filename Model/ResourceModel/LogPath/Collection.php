<?php

namespace CodeCustom\PureLogViewer\Model\ResourceModel\LogPath;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entity_id';
    protected $_eventPrefix = 'code_custom_purelog_path_collection';
    protected $_eventObject = 'purelog_path_collection';

    protected function _construct()
    {
        $this->_init(
            'CodeCustom\PureLogViewer\Model\LogPath',
            'CodeCustom\PureLogViewer\Model\ResourceModel\LogPath'
        );
    }
}

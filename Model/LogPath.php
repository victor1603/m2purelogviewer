<?php

namespace CodeCustom\PureLogViewer\Model;

use CodeCustom\PureLogViewer\Api\LogPathInterface;
use Magento\Framework\Model\AbstractModel;

class LogPath extends AbstractModel implements LogPathInterface
{
    const CACHE_TAG = 'code_custom_purelog_path';

    protected $_cacheTag = 'code_custom_purelog_path';

    protected $_eventPrefix = 'code_custom_purelog_path';

    protected function _construct()
    {
        $this->_init('CodeCustom\PureLogViewer\Model\ResourceModel\LogPath');
    }

    /**
     * @param int $entity_id
     * @return LogPath|mixed|void
     */
    public function setEntityId($entity_id)
    {
        $this->setData('entity_id', $entity_id);
    }

    /**
     * @param $name
     * @return mixed|void
     */
    public function setName($name)
    {
        $this->setData('name', $name);
    }

    /**
     * @param $path
     * @return mixed|void
     */
    public function setPath($path)
    {
        $this->setData('path', $path);
    }

    /**
     * @param $is_date_log
     * @return mixed|void
     */
    public function setIsDateLog($is_date_log)
    {
        $this->setData('is_date_log', $is_date_log);
    }

    /**
     * @return array|mixed|null
     */
    public function getEntityId()
    {
        return $this->getData('entity_id');
    }

    /**
     * @return array|mixed|null
     */
    public function getName()
    {
        return $this->getData('name');
    }

    /**
     * @return array|mixed|null
     */
    public function getPath()
    {
        return $this->getData('path');
    }

    /**
     * @return array|mixed|null
     */
    public function getIsDateLog()
    {
        return $this->getData('is_date_log');
    }
}

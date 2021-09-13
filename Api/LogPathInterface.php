<?php

namespace CodeCustom\PureLogViewer\Api;

interface LogPathInterface
{
    /**
     * @param $entity_id
     * @return mixed
     */
    public function setEntityId($entity_id);

    /**
     * @param $name
     * @return mixed
     */
    public function setName($name);

    /**
     * @param $path
     * @return mixed
     */
    public function setPath($path);

    /**
     * @param $is_date_log
     * @return mixed
     */
    public function setIsDateLog($is_date_log);

    /**
     * @return mixed
     */
    public function getEntityId();

    /**
     * @return mixed
     */
    public function getName();

    /**
     * @return mixed
     */
    public function getPath();

    /**
     * @return mixed
     */
    public function getIsDateLog();
}

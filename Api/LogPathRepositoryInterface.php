<?php

namespace CodeCustom\PureLogViewer\Api;

use CodeCustom\PureLogViewer\Api\LogPathInterface;

interface LogPathRepositoryInterface
{
    /**
     * @param null $id
     * @return LogPathInterface|null
     */
    public function getById($id = null);

    /**
     * @param null $value
     * @param null $field
     * @return mixed
     */
    public function getByField($value = null, $field = null);

    /**
     * @return mixed
     */
    public function getList();

    /**
     * @param \CodeCustom\PureLogViewer\Api\LogPathInterface $logPath
     * @return mixed
     */
    public function save(LogPathInterface $logPath);

    /**
     * @param \CodeCustom\PureLogViewer\Api\LogPathInterface $logPath
     * @return mixed
     */
    public function delete(LogPathInterface $logPath);
}

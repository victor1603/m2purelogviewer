<?php

namespace CodeCustom\PureLogViewer\Model;

use CodeCustom\PureLogViewer\Api\LogPathInterface;
use CodeCustom\PureLogViewer\Api\LogPathRepositoryInterface;
use CodeCustom\PureLogViewer\Model\ResourceModel\LogPath as LogPathResource;
use CodeCustom\PureLogViewer\Model\LogPathFactory;

class LogPathRepository implements LogPathRepositoryInterface
{

    /**
     * @var LogPathResource
     */
    protected $logPathResource;

    /**
     * @var \CodeCustom\PureLogViewer\Model\LogPathFactory
     */
    protected $logPathFactory;

    public function __construct(
        LogPathResource $logPathResource,
        LogPathFactory $logPathFactory
    )
    {
        $this->logPathFactory = $logPathFactory;
        $this->logPathResource = $logPathResource;
    }

    /**
     * @param null $id
     * @return LogPathInterface|null
     */
    public function getById($id = null)
    {
        try {
            $object = $this->logPathFactory->create();
            $this->logPathResource->load($object, $id);
        } catch (\Exception $exception) {
            $object = null;
        }

        return $object;
    }

    /**
     * @param null $value
     * @param null $field
     * @return mixed|null
     */
    public function getByField($value = null, $field = null)
    {
        try {
            $object = $this->logPathFactory->create();
            $this->logPathResource->load($object, $value, $field);
        } catch (\Exception $exception) {
            $object = null;
        }

        return $object;
    }

    public function getList()
    {
        return [];
    }

    /**
     * @param LogPathInterface $logPath
     * @return bool
     * @throws \Exception
     */
    public function save(\CodeCustom\PureLogViewer\Api\LogPathInterface $logPath)
    {
        try {
            $this->logPathResource->save($logPath);
        } catch (\Exception $exception) {
            throw new \Exception(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param LogPathInterface $logPath
     * @return bool
     * @throws \Exception
     */
    public function delete(\CodeCustom\PureLogViewer\Api\LogPathInterface $logPath)
    {
        try {
            $this->logPathResource->delete($logPath);
        } catch (\Exception $exception) {
            throw new \Exception(__($exception->getMessage()));
        }
        return true;
    }
}

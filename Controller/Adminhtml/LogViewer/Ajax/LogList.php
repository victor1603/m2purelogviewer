<?php

namespace CodeCustom\PureLogViewer\Controller\Adminhtml\LogViewer\Ajax;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use CodeCustom\PureLogViewer\Helper\FileSystem;
use CodeCustom\PureLogViewer\Api\LogPathRepositoryInterface;
use Magento\Framework\Escaper;

class LogList extends Action
{

    protected $jsonFactory;

    protected $fileSystem;

    protected $logPathRepository;

    protected $escaper;

    public function __construct(
        Context $context,
        JsonFactory $jsonFactory,
        FileSystem $fileSystem,
        LogPathRepositoryInterface $logPathRepository,
        Escaper $escaper
    )
    {
        $this->jsonFactory = $jsonFactory;
        $this->fileSystem = $fileSystem;
        $this->logPathRepository = $logPathRepository;
        $this->escaper = $escaper;
        parent::__construct($context);
    }

    public function execute()
    {
        try {
            $result = $this->jsonFactory->create();
            $data = $this->getRequest()->getParams();
            $entity_id = isset($data['logId']) ? $data['logId'] : null;
            if (!$entity_id) {
                throw new \Exception(__('No entity loaded'));
            }
            $log = $this->logPathRepository->getById($entity_id);
            $data = $this->fileSystem->readDirectory($log->getPath(), $log->getIsDateLog(), $data['logDate']);
            if (is_array($data)) {
                $result->setData([
                    'data' => $data,
                    'is_file' => '0',
                    'file_name' => basename($log->getPath())
                ]);
            } else {
                $result->setData([
                    'data' => nl2br($this->escaper->escapeHtml($data)),
                    'is_file' => '1',
                    'file_name' => basename($log->getPath())
                ]);
            }

        } catch (\Exception $exception) {
            $result->setData([]);
        }

        return $result;
    }
}

<?php

namespace CodeCustom\PureLogViewer\Controller\Adminhtml\LogGrid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use CodeCustom\PureLogViewer\Model\LogPathFactory;
use CodeCustom\PureLogViewer\Api\LogPathRepositoryInterface;

class Save extends Action
{
    /**
     * @var LogPathFactory
     */
    protected $logPathFactory;

    /**
     * @var LogPathRepositoryInterface
     */
    protected $logPathRepository;

    public function __construct(
        Context $context,
        LogPathFactory $logPathFactory,
        LogPathRepositoryInterface $logPathRepository
    )
    {
        $this->logPathFactory = $logPathFactory;
        $this->logPathRepository = $logPathRepository;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $data = array_filter($data, function($value) {return $value !== ''; });

        try {
            $id = isset($data['entity_id']) ? $data['entity_id'] : null;
            $logPath = $this->logPathRepository->getById($id);
            $logPath->setData($data);
            $this->logPathRepository->save($logPath);
            $this->messageManager->addSuccessMessage(__('Successfuly saved'));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__('Error while saving log-path'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index');
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index');
    }
}

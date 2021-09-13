<?php

namespace CodeCustom\PureLogViewer\Controller\Adminhtml\LogGrid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use CodeCustom\PureLogViewer\Api\LogPathRepositoryInterface;

class Delete extends Action
{

    /**
     * @var LogPathRepositoryInterface
     */
    protected $logPathRepository;

    /**
     * @param Context $context
     * @param LogPathRepositoryInterface $logPathRepository
     */
    public function __construct(
        Context $context,
        LogPathRepositoryInterface $logPathRepository
    )
    {
        $this->logPathRepository = $logPathRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $params = $this->_request->getParams();
        $id = isset($params['entity_id']) ? $params['entity_id'] : null;
        try {
            $lopPath = $this->logPathRepository->getById($id);
            $this->logPathRepository->delete($lopPath);
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage(__('Error while trying to delete reason'));
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('*/*/index');
        }

        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/index');
    }
}

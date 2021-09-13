<?php

namespace CodeCustom\PureLogViewer\Controller\Adminhtml\LogGrid;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{

    /**
     * @var PageFactory
     */
    protected $pageFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory
    )
    {
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->setActiveMenu('CodeCustom_PureLogViewer::log');
        $resultPage->getConfig()->getTitle()->prepend(__('Log grid'));
        return $resultPage;
    }
}

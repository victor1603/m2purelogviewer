<?php

namespace CodeCustom\PureLogViewer\Controller\Adminhtml\LogViewer;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use CodeCustom\PureLogViewer\Helper\FileSystem;
use CodeCustom\PureLogViewer\Api\LogPathRepositoryInterface;
use Magento\Framework\Escaper;

class Download extends Action implements HttpPostActionInterface
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * @var LogPathRepositoryInterface
     */
    protected $logPathRepository;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @param Context $context
     * @param FileSystem $fileSystem
     * @param LogPathRepositoryInterface $logPathRepository
     * @param Escaper $escaper
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        FileSystem $fileSystem,
        LogPathRepositoryInterface $logPathRepository,
        Escaper $escaper,
        PageFactory $pageFactory
    )
    {
        $this->fileSystem = $fileSystem;
        $this->logPathRepository = $logPathRepository;
        $this->escaper = $escaper;
        $this->pageFactory = $pageFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $entity_id = isset($data['logId']) ? $data['logId'] : null;
        if (!$entity_id) {
            throw new \Exception(__('No entity loaded'));
        }

        $log = $this->logPathRepository->getById($entity_id);
        $content = nl2br($this->escaper->escapeHtml(
            $this->fileSystem->readLog(
                $log->getPath(), $log->getIsDateLog(), $data['logDate'], $data['logFile'], true
            )->getContent()
        ));

        /**
         * Download file and add content from echo to file
         */
        header('Content-Type: application/text');
        header('Content-Disposition: attachment; filename="'.$data['logFile'].'"');
        echo $content;
        exit(1);
    }
}

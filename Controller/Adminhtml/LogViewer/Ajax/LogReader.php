<?php

namespace CodeCustom\PureLogViewer\Controller\Adminhtml\LogViewer\Ajax;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use CodeCustom\PureLogViewer\Helper\FileSystem;
use CodeCustom\PureLogViewer\Api\LogPathRepositoryInterface;
use Magento\Framework\Escaper;

class LogReader extends Action
{
    /**
     * @var JsonFactory
     */
    protected $jsonFactory;

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
     * @param JsonFactory $jsonFactory
     * @param FileSystem $fileSystem
     * @param LogPathRepositoryInterface $logPathRepository
     * @param Escaper $escaper
     */
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

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json
     * @throws \Exception
     */
    public function execute()
    {
        $result = $this->jsonFactory->create();
        $data = $this->getRequest()->getParams();
        $entity_id = isset($data['logId']) ? $data['logId'] : null;
        if (!$entity_id) {
            throw new \Exception(__('No entity loaded'));
        }
        $log = $this->logPathRepository->getById($entity_id);
        $data = $this->fileSystem->readLog($log->getPath(), $log->getIsDateLog(), $data['logDate'], $data['logFile']);
        $content = $this->fileSystem->formatFileToView(nl2br($this->escaper->escapeHtml($data->getContent())), $data->getLinesCount());
        $result->setData(
            [
                'data' => $content
            ]
        );

        return $result;
    }

    /**
     * @param string $content
     * @return string
     */
    private function addLineNumber(string $content = '')
    {
        try {
            $newContent = '';
            $lineNum = 1;
            foreach (explode(PHP_EOL, $content) as $line) {
                if ($line) {
                    $newContent .= "<b>[$lineNum]></b> " . $line;
                    $lineNum++;
                }
            }
            return $newContent;
        } catch (\Exception $exception) {
            return $content;
        }
    }
}

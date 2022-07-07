<?php

namespace CodeCustom\PureLogViewer\Helper;

use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class FileSystem
{

    const IS_SUCCESS_LOADED     = 'is_success_loaded';
    const FILE_LINES_COUNT      = 'lines_count';
    const FILE_CONTENT          = 'content';

    const LOG_EXT               = '.log';
    const YEAR_VAR              = '$year';
    const MONTH_VAR             = '$month';
    const DAY_VAR               = '$day';
    const BEFORE_LNGTH          = 2000000;

    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * @var null
     */
    protected $data = [];

    /**
     * @var bool
     */
    protected $_hasDataChanges = false;

    /**
     * @param DriverInterface $driver
     * @param DirectoryList $directoryList
     */
    public function __construct(
        DriverInterface $driver,
        DirectoryList $directoryList
    )
    {
        $this->driver = $driver;
        $this->directoryList = $directoryList;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function getData($key = null)
    {
        if (!$key) {
            return $this->data;
        }

        if ($key && !isset($this->data[$key])) {
            return null;
        }

        return $this->data[$key];
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function setData($key, $value = null)
    {
        if ($key === (array)$key) {
            if ($this->data !== $key) {
                $this->_hasDataChanges = true;
            }
            foreach ($key as $k => $v) {
                $this->data[$k] = $v;
            }
        } else {
            if (!array_key_exists($key, $this->data) || $this->data[$key] !== $value) {
                $this->_hasDataChanges = true;
            }
            $this->data[$key] = $value;
        }
        return $this;
    }

    public function isSuccessLoaded()
    {
        return $this->getData(self::IS_SUCCESS_LOADED);
    }

    /**
     * @return mixed|null
     */
    public function getContent()
    {
        return $this->getData(self::FILE_CONTENT);
    }

    /**
     * @return mixed|null
     */
    public function getLinesCount()
    {
        return $this->getData(self::FILE_LINES_COUNT);
    }

    /**
     * @param $path
     * @param $is_date_log
     * @param $date
     * @return $this|array|false|\Magento\Framework\Phrase
     */
    public function readDirectory($path, $is_date_log = false, $date = null)
    {
        try {
            $data = [];
            $varPath = $this->directoryList->getPath('var');

            /**
             * Check if we look on file
             */
            if ($this->driver->isFile($varPath . DIRECTORY_SEPARATOR . $path)) {
                return $this->readLog($path);
            }

            /**
             * If not file try to open directory and load logs
             */
            if ($is_date_log && $date) {
                $path = $this->getDatePath($path, $date);
            } elseif ($is_date_log && !$date) {
                return __('Please choose log date in datepicker');
            }
            $dirData = $this->driver->readDirectory($varPath . DIRECTORY_SEPARATOR . $path);
            if (!empty($dirData)) {
                foreach ($dirData as $dir) {
                    if (strpos($dir, self::LOG_EXT) !== false) {
                        $data[] = basename($dir);
                    }
                }
            }
        } catch (\Exception $exception) {
            $data = __('Cant load log-path');
        }

        return $data;
    }

    /**
     * @param $path
     * @param $is_date_log
     * @param $date
     * @param $filename
     * @param $readFullLog
     * @return $this|false
     */
    public function readLog($path = null, $is_date_log = false, $date = null, $filename = null, $readFullLog = false)
    {
        $content = '';
        $this->setData(self::IS_SUCCESS_LOADED, true);

        try {
            if ($is_date_log && $date) {
                $path = $this->getDatePath($path, $date);
            }
            if ($filename && strpos($path, self::LOG_EXT) === false) {
                $path = $path . DIRECTORY_SEPARATOR . $filename;
            }

            $logPath = $this->directoryList->getPath('var') . DIRECTORY_SEPARATOR . $path;
            if (!$this->driver->isFile($logPath)) {
                $this->setData(self::IS_SUCCESS_LOADED, false);
                return false;
            }
            $h = $this->driver->fileOpen($logPath, 'r');
            if (!$readFullLog) {
                fseek($h, -self::BEFORE_LNGTH, SEEK_END);
            }
            $logStat = $this->driver->stat($logPath);
            if (isset($logStat['size']) && $logStat['size'] > 0) {
                $content = $this->driver->fileReadLine($h, $logStat['size']);
                if ($content === false) {
                    $this->setData(self::IS_SUCCESS_LOADED, false);
                    $content = __('Log file is not readable');
                }
                $this->driver->fileClose($h);
            }
            $this->setData(self::FILE_LINES_COUNT, count(file($logPath)));
        } catch (\Exception $exception) {
            $content = __('Cant load log file');
            $this->setData(self::IS_SUCCESS_LOADED, false);
        }

        $this->setData(self::FILE_CONTENT, $content);
        return $this;
    }

    /**
     * @param $path
     * @param $date
     * @return array|mixed|string|string[]
     */
    private function getDatePath($path, $date)
    {
        if (!$path || !$date) {
            return $path;
        }

        return str_replace(
            [
                self::YEAR_VAR, self::MONTH_VAR, self::DAY_VAR
            ],
            [
                date('Y', strtotime($date)),
                date('m', strtotime($date)),
                date('d', strtotime($date))
            ],
            $path
        );
    }

    public function formatFileToView(string $content = '', int $linesCount = 0)
    {
        try {
            $newContent = '';
            $patern = "/\s*[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) ([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9]) INFO\s*/";
            $contentLines = array_filter(preg_split($patern, $content));
            $lineNum = $linesCount - (count($contentLines) - 1);

            foreach ($contentLines as $line) {
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

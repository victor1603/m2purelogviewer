<?php

namespace CodeCustom\PureLogViewer\Helper;

use Magento\Framework\Filesystem\DriverInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class FileSystem
{
    const LOG_EXT       = '.log';
    const YEAR_VAR      = '$year';
    const MONTH_VAR     = '$month';
    const DAY_VAR       = '$day';
    const BEFORE_LNGTH  = 1000000;

    /**
     * @var DriverInterface
     */
    protected $driver;

    /**
     * @var DirectoryList
     */
    protected $directoryList;

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
     * @param null $path
     * @return string
     */
    public function readLog($path = null, $is_date_log = false, $date = null, $filename = null)
    {
        $content = '';
        try {
            if ($is_date_log && $date) {
                $path = $this->getDatePath($path, $date);
            }
            if ($filename && strpos($path, self::LOG_EXT) === false) {
                $path = $path . DIRECTORY_SEPARATOR . $filename;
            }

            $logPath = $this->directoryList->getPath('var') . DIRECTORY_SEPARATOR . $path;
            if (!$this->driver->isFile($logPath)) {
                return $content;
            }
            $h = $this->driver->fileOpen($logPath, 'r');
            fseek($h, -self::BEFORE_LNGTH, SEEK_END);
            $logStat = $this->driver->stat($logPath);
            if (isset($logStat['size']) && $logStat['size'] > 0) {
                $content = $this->driver->fileReadLine($h, $logStat['size']);
                if ($content === false) {
                    return "Log file is not readable";
                }
                $this->driver->fileClose($h);
            }

        } catch (\Exception $exception) {
            $content = __('Cant load log file');
        }

        return $content;
    }

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
}

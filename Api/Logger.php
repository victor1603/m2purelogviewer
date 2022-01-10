<?php

namespace CodeCustom\PureLogViewer\Api;

interface Logger
{
    /**
     * @return mixed
     */
    public function writeInDailyFile();

    /**
     * @return mixed
     */
    public function writeInNewFile();
}

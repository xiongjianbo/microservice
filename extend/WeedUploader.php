<?php

Class WeedUploader
{
    public static $weed;
    public static $instance;

    public static function getInstance()
    {
        return self::$instance ?? self::$instance = new self;
    }

    public static function upFile()
    {

    }
}
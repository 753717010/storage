<?php

namespace Cje\Storage\bases;

abstract class BaseDriver implements DriverInterface
{
    /**
     * 基础路径
     * @var string
     */
    public $basePath = 'uploads';

    /**
     * 压缩文件基础路径
     * @var string
     */
    public $thumbPath = 'thumbs';

    /**
     * 文件保存路径
     * @var string
     */
    public $fullPath = '';

    public $accessKey;
    public $secretKey;
    public $bucket;
    public $domain;
    public $isCName = false;

    protected $urlCallback = null;

    public function __construct($config = [])
    {
        foreach ($config as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }
    }

    public function getUrlCallback()
    {
        return $this->urlCallback;
    }

    public function setUrlCallback(callable $cb)
    {
        $this->urlCallback = $cb;
    }

    /**
     * 拼接最终保存的文件路径
     * @param $file
     * @param bool $thumb
     * @return string
     */
    public function getFullPath($file, $thumb = false)
    {
        return rtrim($this->basePath, '/')
            . '/'
            . ($thumb && trim($this->thumbPath) ? trim($this->thumbPath, '/') . '/' : '')
            . (trim($this->fullPath) ? trim($this->fullPath, '/') . '/' : '')
            . ltrim($file, '/');
    }

    public function saveFile($localFile, $file)
    {
        $result = $this->upload($localFile, $file);
        return call_user_func_array($this->urlCallback, [$result, $file, $this]);
    }

    abstract public function upload($localFile, $file);
}
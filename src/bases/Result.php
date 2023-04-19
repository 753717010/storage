<?php
namespace Cje\Storage\bases;

class Result
{
    public $url;

    public $thumbUrl;

    public function __construct($url, $thumbUrl = null)
    {
        $this->url = $url;
        $this->thumbUrl = $thumbUrl ?: $url;
    }
}
<?php
/**
 * Created By PhpStorm
 * User: 风哀伤
 * Date: 2023/4/19
 * Time: 10:23 AM
 * @copyright: ©2023 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace Cje\Storage\drivers;

use Cje\Storage\bases\BaseDriver;
use Cje\Storage\bases\Result;
use Cje\Storage\exceptions\StorageException;
use OSS\Core\OssException;
use OSS\OssClient;

class AliyunDriver extends BaseDriver
{
    /**
     * @var OssClient
     */
    public $ossClient;

    public $appendStyleApi = false;
    public $styleApi = '';

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->ossClient = new OssClient(
            $this->accessKey,
            $this->secretKey,
            $this->domain,
            $this->isCName
        );
    }

    public function upload($localFile, $file)
    {
        $saveTo = $this->getFullPath($file);
        try {
            $res = $this->ossClient->uploadFile($this->bucket, $saveTo, $localFile);
        } catch (OssException $ex) {
            throw new StorageException($ex->getErrorMessage() ?: $ex->getMessage());
        }
        return new Result($this->append($res['oss-request-url']));
    }

    protected function append($url)
    {
        if (!$this->appendStyleApi || !$this->styleApi) {
            return $url;
        }
        return $url . '?' . $this->styleApi;
    }
}
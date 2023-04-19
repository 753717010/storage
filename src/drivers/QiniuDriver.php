<?php
/**
 * Created By PhpStorm
 * User: 风哀伤
 * Date: 2023/4/19
 * Time: 10:38 AM
 * @copyright: ©2023 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace Cje\Storage\drivers;

use Cje\Storage\bases\BaseDriver;
use Cje\Storage\bases\Result;
use Cje\Storage\exceptions\StorageException;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;

class QiniuDriver extends BaseDriver
{
    /**
     * Qiniu auth class
     *
     * @var Auth
     */
    protected $qiniuAuth;

    /**
     * Qiniu upload manager class
     *
     * @var UploadManager
     */
    protected $qiniuUploadManager;

    public $appendStyleApi = false;
    public $styleApi = '';

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->qiniuAuth = new Auth($this->accessKey, $this->secretKey);
        $this->qiniuUploadManager = new UploadManager();
    }

    public function upload($localFile, $file)
    {
        $saveTo = $this->getFullPath($file);
        $token = $this->qiniuAuth->uploadToken($this->bucket);
        list($res, $err) = $this->qiniuUploadManager->putFile($token, $saveTo, $localFile);
        if ($err !== null) {
            throw new StorageException($err->message());
        }
        $url = $this->domain . '/' . $res['key'];
        return new Result($this->append($url));
    }

    protected function append($url)
    {
        if (!$this->appendStyleApi || !$this->styleApi) {
            return $url;
        }
        return $url . '?' . $this->styleApi;
    }
}
<?php
/**
 * Created By PhpStorm
 * User: 风哀伤
 * Date: 2023/4/19
 * Time: 10:29 AM
 * @copyright: ©2023 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace Cje\Storage\drivers;

use Cje\Storage\bases\BaseDriver;
use Cje\Storage\bases\Result;
use Cje\Storage\exceptions\StorageException;
use Qcloud\Cos\Client;

class TencentDriver extends BaseDriver
{
    public $cosClient;

    public $region;

    public function __construct($config = [])
    {
        parent::__construct($config);
        $this->cosClient = new Client([
            'region' => $this->region,
            'credentials' => [
                'secretId' => $this->accessKey,
                'secretKey' => $this->secretKey,
            ],
        ]);
    }

    public function upload($localFile, $file)
    {
        $saveTo = $this->getFullPath($file);
        try {
            /** @var \GuzzleHttp\Command\Result $result */
            $result = $this->cosClient->putObject([
                'Bucket' => $this->bucket,
                'Key' => $saveTo,
                'Body' => fopen($localFile, 'rb'),
            ]);
            if (!isset($result['Location']) && !isset($result['Key'])){
                throw new StorageException('上传失败');
            }
            if ($this->isCName && $this->domain) {
                $url = trim($this->domain, ' /') . '/' . $saveTo;
            } else {
                $url = urldecode('https://' . $result['Location']);
            }
        } catch (\Exception $exception) {
            throw new StorageException($exception->getMessage());
        }
        return new Result($url);
    }
}
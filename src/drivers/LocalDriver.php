<?php

namespace Cje\Storage\drivers;

use Cje\Storage\bases\BaseDriver;
use Cje\Storage\bases\Result;
use Cje\Storage\exceptions\StorageException;
use Grafika\Grafika;
use Grafika\ImageInterface;

class LocalDriver extends BaseDriver
{
    /**
     * 是否创建一个压缩图片
     * @var bool
     */
    public $isThumb = false;

    /**
     * 根路径
     * @var string
     */
    public $rootPath = '';

    /**
     * 访问域名
     * @var string
     */
    public $domain;

    public function upload($localFile, $file)
    {
        // 上传到本地服务器
        list($accessUrl, $saveTo) = $this->createPath($file);
        if (!@copy($localFile, $saveTo)) {
            throw new StorageException('上传失败，请检查是否有站点权限');
        }
        $thumbUrl = $this->thumb($file, $saveTo);
        return new Result($accessUrl, $thumbUrl);
    }

    protected function thumb($file, $saveTo)
    {
        $thumbUrl = '';
        if (!$this->isThumb) {
            return $thumbUrl;
        }
        // 创建一个200*200的压缩图片
        try {
            list($thumbUrl, $saveThumbFile) = $this->createPath($file, true);

            $editor = Grafika::createEditor(get_supported_image_lib());
            /** @var ImageInterface $image */
            $editor->open($image, $saveTo);
            $editor->resizeFit($image, 200, 200);
            $editor->save($image, $saveThumbFile);
        } catch (\Exception $e) {
        }
        return $thumbUrl;
    }

    /**
     * 创建本地存储的物理路径和网络路径
     * @param $file
     * @param $thumb
     * @return array
     * @throws StorageException
     */
    protected function createPath($file, $thumb = false)
    {
        $saveTo = $this->getFullPath($file, $thumb);
        $accessUrl = $this->domain . '/' . $saveTo;
        $saveTo = rtrim($this->rootPath, '/') . '/' . $saveTo;
        $saveDir = dirname($saveTo);
        if (!make_dir($saveDir)) {
            throw new StorageException('目录创建失败，请检查是否有站点权限');
        }
        return [$accessUrl, $saveTo];
    }
}

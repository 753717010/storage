<?php
/**
 * Created By PhpStorm
 * User: 风哀伤
 * Date: 2023/4/19
 * Time: 10:40 AM
 * @copyright: ©2023 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace Cje\Storage;

use Cje\Storage\bases\BaseDriver;
use Cje\Storage\bases\StorageConstant;
use Cje\Storage\drivers\AliyunDriver;
use Cje\Storage\drivers\LocalDriver;
use Cje\Storage\drivers\QiniuDriver;
use Cje\Storage\drivers\TencentDriver;
use Cje\Storage\exceptions\StorageException;

class DriverService
{
    /**
     * @var BaseDriver
     */
    private $driver;

    /**
     * @param string $type 存储驱动类型
     * @param array $config 对应驱动配置
     * @return self
     * @throws StorageException
     */
    public static function getInstance($type, $config = [])
    {
        $self = new self();
        $class = self::getDriverClass($type);
        $self->driver = new $class($config);
        return $self;
    }

    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * @param BaseDriver $driver
     * @return void
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param string $type 存储驱动类型
     * @return string 对应的驱动类名
     * @throws StorageException
     */
    public static function getDriverClass($type)
    {
        switch ($type) {
            case StorageConstant::LOCAL_DRIVER:
                return LocalDriver::class;
                break;
            case StorageConstant::ALIYUN_DRIVER:
                return AliyunDriver::class;
                break;
            case StorageConstant::TENCENT_DRIVER:
                return TencentDriver::class;
                break;
            case StorageConstant::QINIU_DRIVER:
                return QiniuDriver::class;
                break;
            default:
                throw new StorageException('存储驱动暂时没有对接');
        }
    }

    public function saveFile($localFile, $file)
    {
        return $this->driver->saveFile($localFile, $file);
    }
}

# cje/storage

一个集成了多种云存储服务的 PHP 库，提供统一的存储接口，方便开发者在不同存储服务之间切换。

## 功能特性

- 支持多种存储服务：本地存储、阿里云 OSS、腾讯云 COS、七牛云存储
- 统一的 API 接口，易于使用和切换
- 支持图像处理功能
- 灵活的配置系统
- 支持 URL 回调处理

## 安装

使用 Composer 安装：

```bash
composer require cje/storage
```

## 环境要求

- PHP >= 7.2.0
- Composer

## 快速开始

### 1. 初始化存储服务

```php
use Cje\Storage\DriverService;
use Cje\Storage\bases\StorageConstant;

// 配置数组
$config = [
    'accessKey' => 'your-access-key',
    'secretKey' => 'your-secret-key',
    'bucket' => 'your-bucket',
    'domain' => 'your-domain',
    'isCName' => false // 是否使用自定义域名
];

// 获取存储实例
$storage = DriverService::getInstance(StorageConstant::ALIYUN_DRIVER, $config);
```

### 2. 上传文件

```php
// 本地文件路径
$localFile = '/path/to/local/file.jpg';

// 保存路径（相对于存储根目录）
$savePath = 'images/file.jpg';

// 上传文件
$result = $storage->saveFile($localFile, $savePath);

// 输出访问 URL
echo $result->url;
```

## 支持的存储服务

### 1. 本地存储

```php
$config = [
    'basePath' => 'uploads', // 基础路径
    'thumbPath' => 'thumbs', // 缩略图路径
    'fullPath' => '' // 完整路径
];

$storage = DriverService::getInstance(StorageConstant::LOCAL_DRIVER, $config);
```

### 2. 阿里云 OSS

```php
$config = [
    'accessKey' => 'your-access-key',
    'secretKey' => 'your-secret-key',
    'bucket' => 'your-bucket',
    'domain' => 'your-domain',
    'isCName' => false
];

$storage = DriverService::getInstance(StorageConstant::ALIYUN_DRIVER, $config);
```

### 3. 腾讯云 COS

```php
$config = [
    'accessKey' => 'your-access-key',
    'secretKey' => 'your-secret-key',
    'bucket' => 'your-bucket',
    'domain' => 'your-domain',
    'isCName' => false
];

$storage = DriverService::getInstance(StorageConstant::TENCENT_DRIVER, $config);
```

### 4. 七牛云存储

```php
$config = [
    'accessKey' => 'your-access-key',
    'secretKey' => 'your-secret-key',
    'bucket' => 'your-bucket',
    'domain' => 'your-domain'
];

$storage = DriverService::getInstance(StorageConstant::QINIU_DRIVER, $config);
```

## 配置说明

| 配置项 | 类型 | 描述 | 默认值 |
|-------|------|------|--------|
| basePath | string | 基础路径 | 'uploads' |
| thumbPath | string | 压缩文件基础路径 | 'thumbs' |
| fullPath | string | 文件保存路径 | '' |
| accessKey | string | 云存储访问密钥 | - |
| secretKey | string | 云存储 secret 密钥 | - |
| bucket | string | 存储桶名称 | - |
| domain | string | 访问域名 | - |
| isCName | bool | 是否使用自定义域名 | false |

## API 参考

### DriverService

#### getInstance($type, $config = [])

创建存储服务实例

- **参数**：
  - `$type`：存储驱动类型，使用 `StorageConstant` 中的常量
  - `$config`：配置数组
- **返回值**：`DriverService` 实例
- **异常**：`StorageException`（当驱动类型不支持时）

#### saveFile($localFile, $file)

上传文件

- **参数**：
  - `$localFile`：本地文件路径
  - `$file`：保存路径
- **返回值**：`Result` 对象，包含上传结果

### BaseDriver

#### getFullPath($file, $thumb = false)

拼接最终保存的文件路径

- **参数**：
  - `$file`：文件路径
  - `$thumb`：是否为缩略图
- **返回值**：完整的文件路径

#### setUrlCallback(callable $cb)

设置 URL 回调函数

- **参数**：
  - `$cb`：回调函数，接收三个参数：`$result`（上传结果）、`$file`（文件路径）、`$driver`（驱动实例）
- **返回值**：无

## 示例代码

### 基本使用

```php
use Cje\Storage\DriverService;
use Cje\Storage\bases\StorageConstant;

// 配置
$config = [
    'accessKey' => 'your-access-key',
    'secretKey' => 'your-secret-key',
    'bucket' => 'your-bucket',
    'domain' => 'your-domain'
];

// 获取存储实例
$storage = DriverService::getInstance(StorageConstant::ALIYUN_DRIVER, $config);

// 上传文件
$localFile = '/path/to/local/file.jpg';
$savePath = 'images/file.jpg';
$result = $storage->saveFile($localFile, $savePath);

// 输出结果
echo '文件路径：' . $result->path . PHP_EOL;
echo '访问 URL：' . $result->url . PHP_EOL;
```

### 使用 URL 回调

```php
use Cje\Storage\DriverService;
use Cje\Storage\bases\StorageConstant;

// 配置
$config = [
    'accessKey' => 'your-access-key',
    'secretKey' => 'your-secret-key',
    'bucket' => 'your-bucket',
    'domain' => 'your-domain'
];

// 获取存储实例
$storage = DriverService::getInstance(StorageConstant::ALIYUN_DRIVER, $config);

// 设置 URL 回调
$storage->getDriver()->setUrlCallback(function ($result, $file, $driver) {
    // 自定义处理逻辑
    $result->url = 'https://custom-domain.com/' . $result->path;
    return $result;
});

// 上传文件
$localFile = '/path/to/local/file.jpg';
$savePath = 'images/file.jpg';
$result = $storage->saveFile($localFile, $savePath);

// 输出处理后的 URL
echo $result->url;
```

## 异常处理

```php
use Cje\Storage\DriverService;
use Cje\Storage\bases\StorageConstant;
use Cje\Storage\exceptions\StorageException;

try {
    // 配置
    $config = [
        'accessKey' => 'your-access-key',
        'secretKey' => 'your-secret-key',
        'bucket' => 'your-bucket',
        'domain' => 'your-domain'
    ];

    // 获取存储实例
    $storage = DriverService::getInstance(StorageConstant::ALIYUN_DRIVER, $config);

    // 上传文件
    $localFile = '/path/to/local/file.jpg';
    $savePath = 'images/file.jpg';
    $result = $storage->saveFile($localFile, $savePath);

    // 输出结果
    echo $result->url;
} catch (StorageException $e) {
    echo '错误：' . $e->getMessage();
}
```

## 存储常量

| 常量 | 描述 |
|------|------|
| StorageConstant::LOCAL_DRIVER | 本地存储驱动 |
| StorageConstant::ALIYUN_DRIVER | 阿里云存储驱动 |
| StorageConstant::TENCENT_DRIVER | 腾讯云存储驱动 |
| StorageConstant::QINIU_DRIVER | 七牛云存储驱动 |

## 依赖说明

| 依赖包 | 版本 | 用途 |
|-------|------|------|
| aliyuncs/oss-sdk-php | ~2.0 | 阿里云 OSS 存储 SDK |
| qcloud/cos-sdk-v5 | ~2.0 | 腾讯云 COS 存储 SDK |
| qiniu/php-sdk | ~7.0 | 七牛云存储 SDK |
| kosinix/grafika | ~2.0 | 图像处理库 |

## 扩展开发

### 添加自定义存储驱动

1. 创建驱动类，继承 `BaseDriver` 并实现 `upload` 方法
2. 在 `DriverService::getDriverClass` 方法中添加驱动类型映射

```php
// 自定义驱动类
namespace YourNamespace\Drivers;

use Cje\Storage\bases\BaseDriver;
use Cje\Storage\bases\Result;

class CustomDriver extends BaseDriver
{
    public function upload($localFile, $file)
    {
        // 实现上传逻辑
        $path = $this->getFullPath($file);
        // ... 上传代码 ...
        
        $result = new Result();
        $result->path = $path;
        $result->url = $this->domain . '/' . $path;
        return $result;
    }
}

// 在 DriverService 中添加映射
public static function getDriverClass($type)
{
    switch ($type) {
        // ... 现有驱动 ...
        case 'custom':
            return \YourNamespace\Drivers\CustomDriver::class;
            break;
        default:
            throw new StorageException('存储驱动暂时没有对接');
    }
}
```

## 常见问题

### 1. 上传失败怎么办？

检查以下几点：
- 配置是否正确（accessKey、secretKey、bucket 等）
- 网络连接是否正常
- 存储服务是否正常运行
- 文件权限是否正确

### 2. 如何处理大文件上传？

对于大文件，建议使用分片上传。不同存储服务的分片上传实现方式不同，请参考对应存储服务的 SDK 文档。

### 3. 如何设置文件访问权限？

不同存储服务的权限设置方式不同，请参考对应存储服务的 SDK 文档。

## 许可证

MIT 许可证，详见 [LICENSE](LICENSE) 文件。

## 贡献

欢迎提交 Issue 和 Pull Request。

## 联系

- 作者：cheng-jien
- 邮箱：753717010@qq.com

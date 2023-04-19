# storage
集成了多个存储服务

## 目前已经集成的存储服务

- 本地存储
- 阿里云oss存储
- 腾讯云cos存储
- 七牛云kodo存储

## 使用说明

通过创建DriverService实例，来进行存储服务的操作

```php
$diverService = \Cje\Storage\DriverService::getInstance(\Cje\Storage\bases\StorageConstant::LOCAL_DRIVER, [
    'domain' => 'http://localhost',
    'rootPath' => PROJECT_ROOT_PATH,
    'basePath' => 'web/uploads',
    'fullPath' => 'test',
    'isThumb' => true,
    'callBack' => function ($result, $file, $driver) {
        $result->url = $result->url . '?apistyle=222'
        return $result;
    }
]);
$result = $diverService->saveFile(PROJECT_ROOT_PATH . '/web/1.png', '2.png');

// ['url' => 'http://localhost/web/uploads/test/2.png?apistyle=222', 'thumbUrl' => 'http://localhost/web/uploads/thumb/test/2.png]
```

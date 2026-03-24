<?php
/**
 * Created By PhpStorm
 * User: 风哀伤
 * Date: 2023/4/19
 * Time: 11:39 AM
 * @copyright: ©2023 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

require_once(__DIR__ . '/vendor/autoload.php');
$local = [
    'domain' => 'http://localhost',
    'rootPath' => '/',
    'basePath' => 'uploads',
    'fullPath' => 'test',
    'isThumb' => true
];
$diverService = \Cje\Storage\DriverService::getInstance(\Cje\Storage\bases\StorageConstant::TENCENT_DRIVER, $tencentCos);
try {
    $result = $diverService->saveFile('1.png', '2.png');
    var_dump($result);
} catch (Exception $exception) {
    echo "<pre>";
    var_dump($exception);
    echo "</pre>";
}

// 结果
// object(Cje\Storage\bases\Result)#3 (2) {
//     ["url"]=>
//     string(122) "http://localhost/uploads/test/2.png"
//     ["thumbUrl"]=>
//     string(122) "http://localhost/uploads/thumb/test/2.png"
// }
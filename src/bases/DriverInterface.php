<?php
/**
 * Created By PhpStorm
 * User: 风哀伤
 * Date: 2023/4/19
 * Time: 10:26 AM
 * @copyright: ©2023 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace Cje\Storage\bases;

interface DriverInterface
{
    /**
     * @param string $localFile 本地文件
     * @param string $file 保存的文件名
     * @return Result
     */
    public function upload($localFile, $file);
}
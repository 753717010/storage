<?php
/**
 * Created By PhpStorm
 * User: 风哀伤
 * Date: 2023/4/19
 * Time: 10:40 AM
 * @copyright: ©2023 浙江禾匠信息科技
 * @link: http://www.zjhejiang.com
 */

namespace Cje\Storage\bases;

class StorageConstant
{
    /**
     * @description 本地存储
     */
    public const LOCAL_DRIVER = 'local';

    /**
     * @description 阿里云oss存储
     */
    public const ALIYUN_DRIVER = 'aliyun';

    /**
     * @description 腾讯云cos存储
     */
    public const TENCENT_DRIVER = 'tencent';

    /**
     * @description 七牛云kodo存储
     */
    public const QINIU_DRIVER = 'qiniu';
}
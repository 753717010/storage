<?php
if (!function_exists('get_supported_image_lib')) {
    /**
     * 获取支持的图片处理库
     * @return array
     */
    function get_supported_image_lib()
    {
        switch (true) {
            case class_exists('\Imagick') && method_exists((new \Imagick()), 'setImageOpacity'):
                return ['Imagick'];
            default:
                return ['Gd'];
        }
    }
}

if (!function_exists('make_dir')) {
    /**
     * Create the directory by pathname
     * @param string $pathname The directory path.
     * @param int $mode
     * @return bool
     */
    function make_dir($pathname, $mode = 0777)
    {
        if (is_dir($pathname)) {
            return true;
        }
        if (is_dir(dirname($pathname))) {
            return mkdir($pathname, $mode);
        }
        make_dir(dirname($pathname));
        return mkdir($pathname, $mode);
    }
}
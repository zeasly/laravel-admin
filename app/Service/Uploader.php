<?php

namespace App\Service;

/**
 * Interface Uploader
 *
 * @package App\Service
 */
interface Uploader
{
    /**
     * 上传文件
     *
     * @param $file
     * @param $name
     * @param string $dir
     * @return mixed
     */
    public static function upload($file, $name, $dir = '');

    /**
     * 保存文件
     *
     * @param $name
     * @param $dir
     * @return mixed
     */
    public function save($name, $dir);

    /**
     * 获取访问url
     *
     * @return mixed
     */
    public function url();

    /**
     * 获取文件相对路径
     *
     * @return mixed
     */
    public function path();

    /**
     * 获取文件保存路径
     *
     * @return mixed
     */
    public function fullPath();

    /**
     * 设置磁盘
     * @author xiaoze <zeasly@live.com>
     * @return mixed
     */
    public function disk($disk = null);
}

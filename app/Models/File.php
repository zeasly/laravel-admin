<?php

namespace App\Models;

use App\Service\FileUploader;

class File extends Base
{
    /**
     * 定义数据库表名
     *
     * @var string
     */
    protected $table = 'upload_file';

    protected $hidden = [
        'server',
        'path',
        'create_time',
        'update_time',
    ];

    public static $mimes = [
        'image/bmp'    => 'bmp',
        'image/gif'    => 'gif',
        'image/jpeg'   => 'jpg',
        'image/png'    => 'png',
        'image/x-icon' => 'ico',
    ];

    public function url()
    {
        return $this->url;
    }

    public static function getByMd5($md5)
    {
        return static::where('md5', $md5)->first();
    }

    public static function initByMd5($md5)
    {
        $check = static::getByMd5($md5);
        if (!$check) {
            throw new Exception('文件不存在', 500);
        }

        return $check;
    }


    public static function upload($file, $server = 'default')
    {
        if (!$file->isValid()) {
            throw new Exception('文件上传出错:' . $file->getErrorMessage());
        }

        //检查数据库是否存在
        $md5 = md5_file($file->getRealPath());
        if ($check = static::getByMd5($md5)) {
            return $check;
        }

        $extension = $file->extension();
        $re = FileUploader::upload($file, $md5 . ($extension ? '.' . $extension : ''));
        $obj = new static();
        $obj->server = $server;
        $obj->file_size = $file->getSize();
        $obj->md5 = $md5;
        $obj->file_name = $file->getClientOriginalName();

        list($width, $height) = getimagesize($re->fullPath());
        $obj->width = $width;
        $obj->height = $height;
        $obj->path = $re->path();
        $obj->url = $re->url();

        $obj->save();

        return $obj;
    }

    public static function saveFromUrl($url, $server = 'default')
    {
        try {
            $client = new \GuzzleHttp\Client();
            $get = $client->request('get', $url);
            $data = $get->getBody()->getContents();
            if (!$data) {
                throw new Exception('抓取文件失败', -1);
            }

            //检查数据库是否存在
            $md5 = md5($data);
            if ($check = static::getByMd5($md5)) {
                return $check;
            }

            $headers = get_headers($url, 1);
            $extension = static::getExtensionFromMime($headers['Content-Type']);

            $re = FileUploader::upload($data, $md5 . ($extension ? '.' . $extension : ''));
            if (!$re) {
                throw new Exception('保存文件失败', -1);
            }


            $obj = new static();
            $obj->server = $server;
            $obj->file_size = $get->getBody()->getSize();
            $obj->md5 = $md5;

            $obj->width = 0;
            $obj->height = 0;

            $obj->path = $re->path();
            $obj->url = $re->url();

            $obj->save();

            return $obj;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw $e;
        }
    }

    public function updateServer($server, $url)
    {
        $this->server = $server;
        $this->url = $url . $this->path;

        return $this;
    }

    public static function getExtensionFromMime($mime)
    {
        return static::$mimes[$mime] ?? null;
    }
}

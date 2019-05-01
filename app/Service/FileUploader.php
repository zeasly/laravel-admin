<?php

namespace App\Service;

use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * class FileUploader
 *
 * @package App\Service
 */
class FileUploader implements Uploader
{
    public $file;

    public $path;

    public $disk;

    public static function upload($file, $name = null, $dir = null)
    {
        $obj = new static();
        $obj->save($file, $name, $dir);

        return $obj;
    }


    public function url()
    {
        return $this->disk()->url($this->path);
    }

    public function path()
    {
        return $this->path;
    }

    public function fullPath()
    {
        return $this->disk()->path($this->path);
    }

    public function save($file, $name = null, $dir = null)
    {
        if (is_null($dir)) {
            $dir = date('Ymd');
        }
        if ($file instanceof File ||
            $file instanceof UploadedFile) {
            return $this->saveFromFile($file, $name, $dir);
        } else {
            return $this->saveFromData($file, $name, $dir);
        }

    }

    public function saveFromData($data, $name = null, $dir = null)
    {
        if (is_null($name)) {
            $path = $dir . '/' . md5($data);
        } else {
            $path = $dir . '/' . $name;
        }
        if (!$this->disk()->put($path, $data)) {
            return false;
        }
        $this->path = $path;

        return $this;
    }

    public function saveFromFile($file, $name = null, $dir = null)
    {
        if (is_null($name)) {
            $this->path = $this->disk()->putFile($dir, $file);
        } else {
            $this->path = $this->disk()->putFileAs($dir, $file, $name);
        }

        return $this;
    }

    /**
     * 设置磁盘
     * @author xiaoze <zeasly@live.com>
     * @return mixed
     */
    public function disk($disk = null)
    {
        if (!$this->disk || $disk) {
            $this->disk = Storage::disk($disk);
        }
        return $this->disk;
    }
}

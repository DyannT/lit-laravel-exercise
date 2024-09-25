<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadFileBackPackModelTrait
{
    public static function bootUploadFileTrait()
    {
        static::deleting(function($obj) {
            Storage::delete(Str::replaceFirst('storage/', 'public/', $obj->image));
        });
    }

    public function upload_file($value, $attribute_name, $disk = 'public', $destination_path = 'posts', $fileName = null)
    {
        if ($value == null) {
            $this->attributes[$attribute_name] = null;
            return;
        }

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName);

        $this->attributes[$attribute_name] = empty($this->attributes[$attribute_name]) ? $value : 'storage/' . $this->attributes[$attribute_name];
    }
}

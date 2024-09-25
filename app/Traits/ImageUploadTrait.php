<?php

namespace App\Traits;

trait ImageUploadTrait
{
    protected function uploadImage($file, $folderName = 'posts', $fileName = null)
    {
        if ($file) {
            if (!$file->isValid()) {
                throw new \Exception('Invalid image file.');
            }

            $newFileName = $fileName ?? md5($file->getClientOriginalName() . random_int(1, 9999) . time()) . '.' . $file->getClientOriginalExtension();

            $uploadPath = storage_path('app/public/' . $folderName);

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            $file->move($uploadPath, $newFileName);

            return 'storage/'. $folderName .'/' . $newFileName;
        }

        return null;
    }
}

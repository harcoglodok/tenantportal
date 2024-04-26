<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileStorage
{
    public function fileUpload($folder, $image)
    {
        $extension = $image->getClientOriginalExtension();
        $name = $folder . '/' . uniqid() . '.' . $extension;
        Storage::putFile($name, $image);
        return $name;
    }

    public function fileUpdate($folder, $image, $old_image)
    {
        $this->fileDelete($old_image);
        return $this->fileUpload($folder, $image);
    }

    public function fileDelete($image)
    {
        if ($image && Storage::exists($image)) {
            Storage::delete($image);
        }
    }
}

<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait FileStorage
{
    public function fileUpload($folder, $image)
    {
        $storagePath = Storage::disk('public')->put($folder, $image);
        return $storagePath;
    }

    public function fileUpdate($folder, $image, $old_image)
    {
        $this->fileDelete($old_image);
        return $this->fileUpload($folder, $image);
    }

    public function fileDelete($image)
    {
        if ($image && Storage::disk('public')->exists($image)) {
            Storage::disk('public')->delete($image);
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class FileUploaderService
{
    /**
     * Upload a file in public/uploads directory.
     *
     * @param UploadedFile $file
     * @return string
     */
    public function upload(UploadedFile $file, string $path): string
    {
        $filename = $path . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('uploads', $filename);
    }
}

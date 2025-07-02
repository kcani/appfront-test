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
    public function upload(UploadedFile $file): string
    {
        $filename = $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);
        return 'uploads/' . $filename;
    }
}

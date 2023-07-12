<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileUploader
{
    public function upload(UploadedFile $file, string $rep): string
    {
        $fileName = uniqid() . '.' . $file->guessExtension();
        try {
            $file->move($rep, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public
    function delete(?string $filename, string $rep): void
    {
        if (null != $filename) {
            if (file_exists($rep . '/' . $filename)) {
                unlink($rep . '/' . $filename);
            }
        }
    }
}
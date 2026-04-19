<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    public function __construct(
        private SluggerInterface $slugger,
        private string $targetDirectory
    ) {
    }

    public function upload(UploadedFile $file, string $folder): string
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $extension = $file->guessExtension() ?: 'bin';
        $newFilename = $safeFilename . '-' . uniqid() . '.' . $extension;

        $destination = $this->targetDirectory . '/' . $folder;

        if (!is_dir($destination)) {
            mkdir($destination, 0777, true);
        }

        $file->move($destination, $newFilename);

        return $newFilename;
    }
}
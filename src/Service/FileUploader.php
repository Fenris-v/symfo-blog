<?php

declare(strict_types=1);

namespace App\Service;

use League\Flysystem\FilesystemOperator;
use Symfony\Component\HttpFoundation\File\File;

class FileUploader
{
    public function __construct(private FilesystemOperator $articleFileSystem)
    {
    }

    public function uploadFile(File $file): string
    {
        $fileName = md5($file->getClientOriginalName() . time())
            . '.' . $file->guessExtension();

        $stream = fopen($file->getPathname(), 'r');

        $this->articleFileSystem->writeStream($fileName, $stream);
        if (is_resource($stream)) {
            fclose($stream);
        }

        return $fileName;
    }
}

<?php

namespace App\Services;

use PHPUnit\Framework\Exception;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    private $attachmentDirectory;

    public function __construct($attachmentDirectory)
    {
        $this->attachmentDirectory = $attachmentDirectory;
    }

    public function uploadAttachment(UploadedFile $file)
    {
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        try {
            $file->move($this->getAttachmentDirectory(), $fileName);
        } catch (FileException $e) {
            throw new Exception('Cant move file to target directory.');
        }

        return $fileName;
    }

    public function getAttachmentDirectory()
    {
        return $this->attachmentDirectory;
    }
}
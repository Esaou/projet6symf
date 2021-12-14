<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUpload
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file,string $target): string
    {

        $file_name = $file->getClientOriginalName();

        $id = md5(uniqid());

        empty($file_name) ? $file_name = $id.'.'.$file->guessExtension() : $file_name = $file->getClientOriginalName();

        empty($file_name) ? $path = 'images/'.$target.'/'.$id.'.'.$file->guessExtension() : $path = 'images/'.$target.'/'.$file->getClientOriginalName();

        $file->move($this->targetDirectory, $file_name);

        return $path;
    }
}
<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;

class FileUpload
{
    private KernelInterface $appKernel;

    public function __construct(KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
    }

    public function upload(UploadedFile $file,string $target): string
    {

        $pathDirectory = $this->appKernel->getProjectDir() . "/public/images/$target";

        $id = md5(uniqid());

        $file_name = $id.'.'.$file->guessExtension();

        $image = $id.'.'.$file->guessExtension();

        $file->move($pathDirectory, $file_name);

        return $image;
    }
}
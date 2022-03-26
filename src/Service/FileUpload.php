<?php


namespace App\Service;

use App\Validator\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FileUpload
{
    private KernelInterface $appKernel;

    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator, KernelInterface $appKernel)
    {
        $this->appKernel = $appKernel;
        $this->validator = $validator;
    }

    /**
     * @param UploadedFile $file
     * @return bool|array<string>
     */
    public function isValid(UploadedFile $file): bool|array
    {

        $violations = $this->validator->validate($file,new Image());

        $errors = [];

        if (0 !== count($violations)) {

            foreach ($violations as $violation){
                array_push($errors,$violation->getMessage());
            }

            return $errors;
        }

        return false;
    }

    /**
     * @param UploadedFile $file
     * @param string $target
     * @return string|array<string>
     */
    public function upload(UploadedFile $file, string $target): string|array
    {

        $violations = $this->validator->validate($file,new Image());

        $errors = [];

        if (0 !== count($violations)) {

            foreach ($violations as $violation){
                array_push($errors,$violation->getMessage());
            }

            return $errors;
        }

        $pathDirectory = $this->appKernel->getProjectDir() . "/public/images/$target";

        $id = md5(uniqid());

        $file_name = $id.'.'.$file->guessExtension();

        $image = $id.'.'.$file->guessExtension();

        $file->move($pathDirectory, $file_name);

        return (string)$image;
    }
}
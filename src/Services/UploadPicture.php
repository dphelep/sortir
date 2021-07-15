<?php


namespace App\Services;


use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadPicture
{

    public function save(String $name, UploadedFile $image, String $directory) {

        $newFilename = 'fileNameByDefault.png';

        /**
         * @var UploadedFile $file
         */
        if($image) {
            $newFilename = $name .'-'.uniqid().'.'.$image->guessExtension();
            $image->move($directory, $newFilename);
        }
        return $newFilename;
    }

}
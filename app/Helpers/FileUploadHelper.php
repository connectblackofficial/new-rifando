<?php

namespace App\Helpers;

use App\Enums\FileUploadTypeEnum;
use App\Exceptions\UserErrorException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class FileUploadHelper
{
    private $uploadedFile;
    private $type;

    function __construct(UploadedFile $uploadedFile, $type)
    {
        $this->uploadedFile = $uploadedFile;
        $this->type = $type;

        if (!in_array($type, FileUploadTypeEnum::getValues())) {
            throw new UserErrorException("Tipo inválido");
        }
    }

    function isImage()
    {
        if (!$this->uploadedFile->isValid()) {
            return false;
        }

        $imageMimeTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/bmp', 'image/svg+xml'];
        return in_array($this->uploadedFile->getMimeType(), $imageMimeTypes);
    }

    public function upload()
    {
        if ($this->type == FileUploadTypeEnum::Document) {
            $uploadPath = getSiteUploadPath() . '/documents';
        } else {
            $uploadPath = getSiteUploadPath() . '/images';
        }
        $isValid = $this->isImage();
        if (!$isValid) {
            throw new UserErrorException("Imagem inválida.");
        }
        $path = $this->uploadedFile->store($uploadPath, 'public');
        if ($path === false) {
            throw new UserErrorException("Ocorreu um erro ao realizar o upload.");
        }
        return $path;
    }
}

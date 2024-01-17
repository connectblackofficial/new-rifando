<?php

namespace App\Helpers;

use App\Enums\FileUploadTypeEnum;
use App\Exceptions\UserErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FileUploadHelper
{
    private $request;
    private $name;
    private $type;

    function __construct(Request $request, $name, $type)
    {
        $this->request = $request;
        $this->name = $name;
        $this->type = $type;
        if (!in_array($type, FileUploadTypeEnum::getValues())) {
            throw new UserErrorException("Tipo inválido");
        }

    }


    public function upload()
    {
        $name = $this->name;

        if ($this->request->hasFile($name)) {
            if ($this->type == FileUploadTypeEnum::Document) {
                $rules = [
                    $name => 'required|file|mimes:pdf,doc,docx|max:2048'
                ];
                $uploadPath = getSiteUploadPath() . '/images';
            } else {
                $rules = [
                    $name => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                ];
                $uploadPath = getSiteUploadPath() . '/documents';
            }

            $validator = Validator::make($this->request->all(), $rules);
            if ($validator->fails()) {
                throw new UserErrorException($validator->messages()->first());
            } else {
                $path = $this->request->file($name)->store($uploadPath, 'public');
                if ($path === false) {
                    throw new UserErrorException("Ocorreu um erro ao realizar o upload.");
                }
                return $path;
            }
        } else {
            throw new UserErrorException("Arquivo vazio.");
        }
    }
}

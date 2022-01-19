<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileService
{
    use HelperTrait;

    protected $privateImageFolder = 'private/images/';
    protected $privateDocsFolder = 'private/docs/';

    public $allowedImageMimes = [
        'image/gif',
        'image/png',
        'image/jpeg',
        'image/webp',
        //'image/svg+xml'
    ];

    public $allowedDocMimes = [
        /*'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',*/
        'application/pdf',
        /*'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'*/
    ];

    public $mimeToExtension = [
        'image/gif' => 'gif',
        'image/png' => 'png',
        'image/jpeg' => 'jpg',
        'image/webp' => 'webp',
        'image/svg+xml' => 'svg',
        'application/msword' => 'doc',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        'application/pdf' => 'pdf',
        'application/vnd.ms-excel' => 'xls',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => 'xlsx'
    ];    

    protected $compressExtension = 'png';

    public function uploadImage(
        object $request,
        int $width,
        int $height
    ): array {
        $file = $request->file;
        $mime = strtolower($file->getMimeType());
        if (!in_array($mime, $this->allowedImageMimes)) {
            return ['success' => false, 'msg' => 'Invalid image format.'];
        }

        try {
            //compress image
            $imgCompressed = \Image::make($file);
            if ($width > 0 && $height > 0) {
                $imgCompressed = $imgCompressed->resize($width, $height);
            }
            $imgCompressed = $imgCompressed->encode($this->compressExtension, 90);

            $originalFileName = str_replace(".{$file->getClientOriginalExtension()}", '', $file->getClientOriginalName());
            $cleanFileName = $this->sanitizeFileName($originalFileName);
            $unquieId = "_" . str_replace('-', '_', (string) Str::uuid());
            $fileName = "{$cleanFileName}.$unquieId.{$this->compressExtension}";

            Storage::disk('local')->put(
                $this->privateImageFolder . '/' . $fileName,
                $imgCompressed
            );

            return ['success' => true, 'fileName' => $fileName];
        } catch (\Throwable $e) {
            logger()->error($e->getMessage());

            return ['success' => false, 'msg' => $this->uploadErrorMessage()];
        }
    }

    /*public function uploadFile(object $request)
    {
        $filename = Storage::putFile($this->privateDocsFolder, $request->file);
        $filename = explode("/", $filename);
        $filename = end($filename);
        return $filename;
    }*/

    public function uploadFile(object $request): array
    {
        $file = $request->file;
        $mime = strtolower($file->getMimeType());
        if (!in_array($mime, $this->allowedDocMimes)) {
            return ['success' => false ,'msg' => 'Invalid file format.' ];
        }

        try {
            $originalFileName = str_replace(".{$file->getClientOriginalExtension()}", '', $file->getClientOriginalName());
            $cleanFileName = $this->sanitizeFileName($originalFileName);
            $unquieId = "_" . str_replace('-', '_', (string) Str::uuid());
            $fileName = "{$cleanFileName}.$unquieId.{$this->mimeToExtension[$mime]}";

            $file->storeAs($this->privateDocsFolder, $fileName);

            return ['success' => true , 'fileName' => $fileName];
        } catch (\Exception $e) {
            logger()->error($e->getMessage());

            return ['success' => false ,'msg' => $this->uploadErrorMessage()];
        }
    }    

    public function getPrivateImage(string $name, string $alt = null)
    {
        $path = $this->privateImageFolder . $name;

        if (Storage::exists($path)) {
            return \Image::make(storage_path('app/' . $path))->response();
        } else {
            return \Image::make(storage_path('app/' . $alt))->response();
        }
    }

    public function uploadErrorMessage(): string
    {
        return 'Unable to upload. Please try again or contact support if the problem persists.';
    }
}

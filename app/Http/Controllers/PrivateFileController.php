<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileService;

class PrivateFileController extends Controller
{
    protected $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getImage(string $name)
    {
        return $this->fileService->getPrivateImage($name);
    }

    function getOfficeFile(string $name)
    {
        return $this->fileService->getPrivateImage($name, 'private/images/office_no_logo.png');
    }

    function getPostFile(string $name)
    {
        return $this->fileService->getPrivateImage($name, 'private/images/no_post_image.png');
    }

    public function getPanelFile(string $name)
    {
        return $this->fileService->getPrivateImage($name, 'private/images/no_panel_image.png');
    }

    public function getAccessoryFile(string $name)
    {
        return $this->fileService->getPrivateImage($name, 'private/images/no_accessory_image.png');
    }

    public function documentFile(string $name)
    {
        if (strpos($name, '.pdf') !== false) {
            return response()->file(storage_path('/app/private/docs/' . $name));
        } 
        if (strpos($name, '.png') !== false) {
            return response()->file(storage_path('/app/private/images/' . $name));
        }
        if (strpos($name, '.jpg') !== false) {
            return response()->file(storage_path('/app/private/images/' . $name));
        }
        if (strpos($name, '.gif') !== false) {
            return response()->file(storage_path('/app/private/images/' . $name));
        } 
    }
}

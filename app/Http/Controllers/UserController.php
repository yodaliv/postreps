<?php

namespace App\Http\Controllers;

use App\Http\Traits\HelperTrait;
use App\Services\OfficeService;
use App\Services\RegionService;

class UserController extends Controller
{
    use HelperTrait;

    protected $officeService;
    protected $regionService;

    public function __construct(
        OfficeService $officeService,
        RegionService $regionService
     ) {
        $this->officeService = $officeService;

        $this->regionService = $regionService;

     }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'offices' => $this->officeService->getAll(),
            'states' => $this->getStates(),
            'regions' => $this->regionService->getAll(),
        ];

        return view('users.index', $data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ServiceSetting;
use Illuminate\Http\Request;

class ServiceSettingController extends Controller
{

    public function updateColumn($column, $value)
    {
        if (in_array($column, (new ServiceSetting)->getFillable()))
            return ServiceSetting::query()->update([$column => $value]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServiceSetting  $serviceSetting
     * @return \Illuminate\Http\Response
     */
    public function show(ServiceSetting $serviceSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServiceSetting  $serviceSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(ServiceSetting $serviceSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServiceSetting  $serviceSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ServiceSetting $serviceSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServiceSetting  $serviceSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(ServiceSetting $serviceSetting)
    {
        //
    }
}

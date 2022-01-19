<?php

namespace App\Http\Controllers;

use App\Http\Requests\SetPanelSetting;
use App\Http\Traits\HelperTrait;
use App\Models\PanelSetting;
use Illuminate\Http\Request;

class PanelSettingController extends Controller
{
    use HelperTrait;
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
    public function store(SetPanelSetting $request)
    {
        //
        $data = $request->all();
        PanelSetting::first()->update($data);
        return $this->backWithSuccess("Panel setting updated successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PanelSetting  $panelSetting
     * @return \Illuminate\Http\Response
     */
    public function show(PanelSetting $panelSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PanelSetting  $panelSetting
     * @return \Illuminate\Http\Response
     */
    public function edit(PanelSetting $panelSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PanelSetting  $panelSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PanelSetting $panelSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PanelSetting  $panelSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy(PanelSetting $panelSetting)
    {
        //
    }
}

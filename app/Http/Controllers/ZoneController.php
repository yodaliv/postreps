<?php

namespace App\Http\Controllers;

use App\Http\Traits\HelperTrait;
use App\Models\ServiceSetting;
use App\Models\Zone;
use App\Services\ZoneService;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    use HelperTrait;

    protected $zoneService;


    public function __construct(ZoneService $zoneService)
    {
        $this->zoneService = $zoneService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->zoneService->getAll();
    }

    public function getZonesOrderBy($column, $type)
    {
        return response()->json(Zone::whereNotNull('name')->orderBy($column, $type)->get());
    }

    public function getSettings(Request $request)
    {
        return response()->json(ServiceSetting::first());
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
        return $this->zoneService->create([
            'color' => $request->color,
            'size' => $request->size,
            'points' => json_encode($request->points),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function show(Zone $zone)
    {
        //
        return $zone;
    }



    public function updateSettings(Request $request)
    {
        $zone = $this->zoneService->findById($request->id);
        return $zone->update($request->all());
    }

    public function updateZoneFee(Request $request, Zone $zone)
    {
        if ($request->zone_fee == null or empty($request->zone_fee)) $request->zone_fee  = 0;
        return $zone->update(['zone_fee' => $request->zone_fee]);
    }

    public function delete(Zone $zone)
    {
        return $zone->delete();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function edit(Zone $zone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Zone $zone)
    {
        //
        return $zone->update(["size" => $request->size, 'points' => json_encode($request->points)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy(Zone $zone)
    {
        //
    }
}

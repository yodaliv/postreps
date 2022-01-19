<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHoliday;
use App\Models\Holiday;
use App\Services\HolidayService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HolidayController extends Controller
{


    protected $holidayService;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
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
    public function getAll(Request $request)
    {
        return $this->holidayService->getAll()->pluck('date')->map(function ($date) {
            return Carbon::parse($date)->format('m/d/Y');
        });
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
    public function store(CreateHoliday $request)
    {
        $data = $request->all();
        $date =  $data['date'];
        $dateSQL =  Carbon::parse($date)->format('Y-m-d');
        $datef =  Carbon::parse($date)->format('m-d-Y');
        $exists  = (bool) $this->holidayService->ifDateExistsDeleteIt($dateSQL);
        if (!$exists) {
            try {
                $this->holidayService->create(['date' => $dateSQL]);

                return response()->json(['msg' => "{$datef} was set as holiday."]);
            } catch (\Exception $e) {

                return response()->json($e);
            }
        }
        return response()->json(['msg' => "{$datef} was removed from holidays list."]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function show(Holiday $holiday)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function edit(Holiday $holiday)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Holiday $holiday)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        //
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateAccessory;
use App\Http\Requests\UpdateAccessory;
use App\Http\Traits\HelperTrait;
use App\Models\Accessory;
use App\Models\AccessoryAgent;
use App\Models\AccessoryDocument;
use App\Models\AccessoryOffice;
use App\Models\Agent;
use App\Models\InventoryList;
use App\Models\Office;
use App\Services\AccessoryDocumentService;
use App\Services\AccessoryService;
use App\Services\FileService;
use App\Services\InventoryListService;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{

    use HelperTrait;

    protected $accessorySerivce;

    protected $fileService;

    protected $inventoryListService;

    protected $accessoryDocumentService;

    public function __construct(AccessoryService $accessorySerivce, FileService $fileService, InventoryListService $inventoryListService, AccessoryDocumentService $accessoryDocumentService)
    {
        $this->accessorySerivce      = $accessorySerivce;

        $this->fileService           = $fileService;

        $this->inventoryListService  = $inventoryListService;

        $this->accessoryDocumentService  = $accessoryDocumentService;
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
    public function store(CreateAccessory $request)
    {
        //
        $data = $request->all();

        $data['prompt'] = isset($data['prompt']) ? true : false;
        $data['message'] = isset($data['message']) ? true : false;
        $data['inventory'] = isset($data['inventory']) ? true : false;
        $data['pull_list'] = isset($data['pull_list']) ? true : false;

        //store image if exists
        if ($request->hasFile('accessory_image')) {
            $request->file = $request->accessory_image;

            $uploadImg = $this->fileService->uploadImage($request, 0, 0);
            if (!$uploadImg['success']) {
                return $this->backWithError($uploadImg['msg']);
            }

            $data['image'] = $uploadImg['fileName'];
        } else {
            $data['image'] = 'no_accessory_image.png';
        }

        $accessory = $this->accessorySerivce->create($data);

        if ($accessory->inventory && isset($data['accessory_invetory_offices'])) {


            foreach ($data['accessory_invetory_offices'] as $index => $office_id) {

                $agent_id = $data['accessory_inventory_agents'][$index];
                $item_id = $data['accessory_inventory_item_id'][$index];
                $this->inventoryListService->create([
                    'office_id' => $office_id,
                    'agent_id' => $agent_id,
                    'accessory_id' => $accessory->id,
                    'item_id' => $item_id,

                ]);
            }
        }

        /*$access = $accessory->default == 1 ? "true" : "false";
        $this->toggleAcessAll($accessory, $access);*/

        return $this->backWithSuccess("Accessory created successfully.");
    }

    public function datatable()
    {
        return $this->accessorySerivce->datatable();
    }

    public function destroyAll()
    {
        Accessory::query()->delete();
        return $this->responseJsonSuccess((object)[]);
    }

    public function getAccessory(Accessory $accessory)
    {
        return $accessory->where('id', $accessory->id)->with('inventories')->first();
    }
    public function getAccessoryJson(Accessory $accessory)
    {
        return response()->json($accessory);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function show(Accessory $accessory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function edit(Accessory $accessory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAccessory $request, Accessory $accessory)
    {
        //
        $data = $request->all();
        $data['prompt'] = isset($data['prompt']) ? true : false;
        $data['message'] = isset($data['message']) ? true : false;
        $data['inventory'] = isset($data['inventory']) ? true : false;
        $data['pull_list'] = isset($data['pull_list']) ? true : false;
        //store image if exists
        if ($request->hasFile('accessory_image_edit')) {
            $request->file = $request->accessory_image_edit;

            $uploadImg = $this->fileService->uploadImage($request, 0, 0);
            if (!$uploadImg['success']) {
                return $this->backWithError($uploadImg['msg']);
            }

            $data['image'] = $uploadImg['fileName'];
        }

        $accessory->update($data);

        /*if ((int)$data['default'] == 1) {
            $this->toggleAcessAll($accessory, 'true');
        } else {
            $this->toggleAcessAll($accessory, 'false');
        }*/

        if ($accessory->inventory && isset($data['eaccessory_invetory_offices'])) {

            $accessory->inventories()->delete();

            foreach ($data['eaccessory_invetory_offices'] as $index => $office_id) {

                $agent_id = $data['eaccessory_inventory_agents'][$index];
                $item_id = $data['eaccessory_inventory_item_id'][$index];
                $this->inventoryListService->create([
                    'office_id' => $office_id,
                    'agent_id' => $agent_id,
                    'accessory_id' => $accessory->id,
                    'item_id' => $item_id,

                ]);
            }
        }

        return $this->backWithSuccess("Accessory updated successfully.");
    }

    public function storeDocuments(Accessory $accessory, Request $request)
    {
        $data = $request->all();
        foreach ($data as $key => $val) {
            if (str_starts_with($key, 'file')) {
                $request->file = $val;
                $uploadImg = $this->fileService->uploadImage($request, 0, 0);
                if (!$uploadImg['success']) {
                    return $this->backWithError($uploadImg['msg']);
                }
                $this->accessoryDocumentService->create(['accessory_id' => $accessory->id, 'name' => $uploadImg['fileName'], 'user_id' => auth()->user()->id]);
            }
        }
        return true;
    }

    public function updateColumn(Accessory $accessory, $column, $value)
    {
        if (in_array($column, ['price', 'loss_damage', 'time_days', 'renewal_fee'])) {
            return $accessory->update([$column => $value]);
        } else {
            abort(500);
        }
    }

    public function updateAccessoryOfficeColumn($post, $office, $column, $value)
    {
        if (in_array($column, ['price', 'locked'])) {
            $value = doubleval($value);
            AccessoryOffice::where('accessory_id', $post)->where('office_id', $office)->update([$column => $value]);
            return response(['done' => 1]);
        }
    }

    public function toggleLocked(Accessory $accessory, Office $office, $access, $price, $locked)
    {
        $price = doubleval($price);

        if ($locked == "true") {
            AccessoryOffice::updateOrCreate([
                'accessory_id' => $accessory->id,
                'office_id' => $office->id,
            ], [
                'accessory_id' => $accessory->id,
                'office_id' => $office->id,
                'price' => $price,
                'access' => $access == "true" ? 1 : 0,
                'locked' => $locked == "true" ? 1 : 0
            ]);

            return response()->json(['action' => 'create', 'ok' => 1]);
        } else {

            AccessoryOffice::where('accessory_id', $accessory->id)
                ->where('office_id', $office->id)
                ->update(['locked' => $locked == "true" ? 1 : 0]);

            return response()->json(['action' => 'create', 'ok' => 1]);
        }

        return response()->json(['msg' => 'error', 'ok' => 0]);
    }

    public function toggleAccess(Accessory $accessory, Office $office, $access, $price = 0)
    {
        $post_office = AccessoryOffice::where('accessory_id', $accessory->id)->where('office_id', $office->id);

        if ($post_office->exists()) {
            AccessoryOffice::where('accessory_id', $accessory->id)
                ->where('office_id', $office->id)
                ->update(['access' => $access == "true" ? 1 : 0]);
            return response()->json(['action' => "update", 'ok' => 1]);
        } else {
            AccessoryOffice::create([
                'accessory_id' => $accessory->id,
                'office_id' => $office->id,
                'price' => $price,
                'access' => 1,
            ]);
            return response()->json(['action' => "create", 'ok' => 1]);
        }
    }
    public function updateAccessoryAgentColumn($post, $agent, $column, $value)
    {
        if (in_array($column, ['price', 'locked'])) {
            $value = doubleval($value);
            AccessoryAgent::where('accessory_id', $post)->where('agent_id', $agent)->update([$column => $value]);
            return response(['done' => 1]);
        }
    }
    public function AgenttoggleLocked(Accessory $accessory, Agent $agent, $access, $price, $locked)
    {
        $price = doubleval($price);

        if ($locked == "true") {
            AccessoryAgent::updateOrCreate([
                'accessory_id' => $accessory->id,
                'agent_id' => $agent->id,
            ], [
                'accessory_id' => $accessory->id,
                'agent_id' => $agent->id,
                'price' => $price,
                'access' => $access == "true" ? 1 : 0,
                'locked' => $locked == "true" ? 1 : 0
            ]);

            return response()->json(['action' => 'create', 'ok' => 1]);
        } else {

            AccessoryAgent::where('accessory_id', $accessory->id)
                ->where('agent_id', $agent->id)
                ->update(['locked' => $locked == "true" ? 1 : 0]);

            return response()->json(['action' => 'create', 'ok' => 1]);
        }

        return response()->json(['msg' => 'error', 'ok' => 0]);
    }

    public static function AgenttoggleAccess(Accessory $accessory, Agent $agent, $access, $price = 0)
    {
        $post_office = AccessoryAgent::where('accessory_id', $accessory->id)->where('agent_id', $agent->id);

        if ($post_office->exists()) {
            AccessoryAgent::where('accessory_id', $accessory->id)
                ->where('agent_id', $agent->id)
                ->update(['access' => $access == "true" ? 1 : 0]);
            return response()->json(['action' => "update", 'ok' => 1]);
        } else {
            AccessoryAgent::create([
                'accessory_id' => $accessory->id,
                'agent_id' => $agent->id,
                'price' => $price,
                'access' => 1,
            ]);
            return response()->json(['action' => "create", 'ok' => 1]);
        }
    }

    public function toggleAcessAll(Accessory $accessory, $access)
    {
        $offices = Office::all();
        foreach ($offices as $office) {
            $this->toggleAccess($accessory, $office, $access);
            foreach ($office->agents as $agent) {
                self::AgenttoggleAccess($accessory, $agent, $access);
            }
        }
        return response()->json($accessory);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Accessory  $accessory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Accessory $accessory)
    {
        //
    }
}

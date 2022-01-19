<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrder;
use App\Http\Traits\HelperTrait;
use App\Models\Agent;
use App\Models\Order;
use App\Models\Office;
use App\Models\OrderAccessory;
use App\Models\OrderAttachment;
use App\Services\InstallPostAgentService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use App\Services\FileService;
use App\Services\NotificationService;
use App\Services\OrderAccessoryService;
use App\Services\OrderAttachmentService;
use Illuminate\Support\Facades\Session;
use App\Models\ServiceSetting;

class OrderController extends Controller
{

    use HelperTrait;

    protected $orderService;

    protected $installPostAgentService;

    protected $fileService;

    protected $orderAttachmentService;

    protected $orderAccessoryService;

    protected $notificationService;


    public function __construct(
        OrderService $orderService,
        InstallPostAgentService $installPostAgentService,
        FileService $fileService,
        OrderAttachmentService $orderAttachmentService,
        OrderAccessoryService $orderAccessoryService,
        NotificationService $notificationService
    ) {
        $this->orderService = $orderService;

        $this->installPostAgentService = $installPostAgentService;

        $this->fileService = $fileService;

        $this->orderAttachmentService = $orderAttachmentService;

        $this->orderAccessoryService = $orderAccessoryService;

        $this->notificationService = $notificationService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return "<h1>orders index : )</h1>";
    }
    public function datatable(Request $request)
    {
        return  $this->orderService->dataTable();
    }

    public function cancel(Order $order)
    {
        $order->status = Order::STATUS_CANCELLED;
        $order->save();
        return true;
    }

    public function markCompleted(Order $order)
    {
        $order->status = Order::STATUS_COMPLETED;
        $order->save();

        return true;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrder $request)
    {
        $data = $request->all();

        // handle files
        $files = [];
        foreach (array_keys($data) as $key) {
            if (str_starts_with($key, 'file')) {
                // get accessory id
                [$fileKey, $accessoryId] = explode("_", $key);
                $request->file = $request->{$key};

                $mime = strtolower($request->file->getMimeType());
                if (in_array($mime, $this->fileService->allowedImageMimes)) {
                    $uploadImg = $this->fileService->uploadImage($request, 0, 0);
                    if (!$uploadImg['success']) {
                        return $this->responseJsonError($uploadImg['msg']);
                    }
                    $files[] = [
                        'filename' => $uploadImg['fileName'],
                        'accessory' => (int) $accessoryId ? (int) $accessoryId : null,
                        'plat_map' => $accessoryId == "plat-map" ?  1 : 0
                    ];
                } else if (in_array($mime, $this->fileService->allowedDocMimes)) {
                    $uploadFile = $this->fileService->uploadFile($request);
                    if (!$uploadFile['success']) {
                        return $this->responseJsonError($uploadFile['msg']);
                    }
                    $files[] = [
                        'filename' => $uploadFile['fileName'],
                        'accessory' => (int) $accessoryId ? (int) $accessoryId : null,
                        'plat_map' => $accessoryId == "plat-map" ?  1 : 0
                    ];
                } else {
                    return $this->responseJsonError("Invalid file format! Accepted file formats: PDF, GIF, PNG, JPG.");
                }
            }
        }

        $marker = (object) ['lat' => null, 'lng' => null];

        if (isset($data['install_marker_position'])) {
            if ($data['install_marker_position'] !== "null") {
                $marker_position = json_decode($data['install_marker_position']);
                $marker->lat = $marker_position->lat;
                $marker->lng = $marker_position->lng;
            }
        }

        $data['install_post_select_accessories'] = json_decode($data['install_post_select_accessories']);


        $data['install_post_desired_date'] = strtolower($data['install_post_desired_date']) == "asap" ? 1 : 2;

        if ($data['install_post_desired_date'] == 2) {
            //Need to check past date and return error to user
            $installDate = date("Y-m-d", strtotime($data['install_post_custom_desired_date']));
            $today = date("Y-m-d");
            if ($installDate < $today) {
                return $this->responseJsonError("Cannot create order for past dates!");
            }

            $data["install_post_custom_desired_date"] = date("Y-m-d", strtotime($data['install_post_custom_desired_date']));
        } else {
            $data["install_post_custom_desired_date"] = null;
        }

        $data['total'] =  floatval($data['install_post_signage']) + floatval($data['install_post_zone_fee']);



        if ($data['is_create'] == "false") {
            return $this->update($this->orderService->findById($data['order_id']), $data, $marker, $request);
            exit;
        }
        // if($data['is_create'])

        $order = $this->orderService->create([
            "address"            => $data['install_post_address'],
            "property_type"      => $data['install_post_property_type'],
            "desired_date_type"  => $data['install_post_desired_date'],
            "desired_date"       => $data["install_post_custom_desired_date"],
            "office_id"          => $data['install_post_office'],
            "post_id"            => $data['install_post_select_post'],
            "panel_id"           => $data['install_post_select_sign'],
            "comment"            => $data['install_post_comment'],
            "signage_fee"        => $data['install_post_signage'],
            "zone_fee"           => $data['install_post_zone_fee'],
            "total"              => $data['total'],
            "latitude"           => $marker->lat,
            "longitude"          => $marker->lng,
            'agent_own_sign'     => $data['install_post_select_sign_type'] == "-1" ? 1 : 0,
            'sign_at_property'   => $data['install_post_select_sign_type'] == "-2" ? 1 : 0,
            'user_id'            => auth()->user()->id,
            'agent_id'           => $data['install_post_agent'] == "null" ? null : $data['install_post_agent'],
            "zone_id"            => $data['zone_id'],
        ]);

        $accessories = $data['install_post_select_accessories'];
        if (count($accessories)) {
            foreach ($accessories as $accessory) {
                $this->orderAccessoryService->create([
                    'order_id' => $order->id,
                    'accessory_id' => $accessory,
                ]);
            }
        }

        if (count($files)) {
            foreach ($files as $file) {
                $this->orderAttachmentService->create([
                    'order_id' => $order->id,
                    "name" => $file['filename'],
                    "accessory_id" => $file['accessory'],
                    "plat_map" => $file['plat_map'],
                ]);
            }
        }

        $needPayment = false;

        if ($order->office) {
            if ($order->office->payment_method == Office::PAYMENT_METHOD_PAY_AT_TIME_OF_ORDER) {
                $order->status = Order::STATUS_INCOMPLETE;
                $order->save();
                $needPayment = true;
            };
        }

        if ($order->agent) {
            if ($order->agent->payment_method == Agent::PAYMENT_METHOD_PAY_AT_TIME_OF_ORDER) {
                $order->status = Order::STATUS_INCOMPLETE;
                $order->save();
                $needPayment = true;
            }
        }

        // if property type is "New construction" or "vacant land" and no files set status imcomplete
        if ($order->property_type == Order::NEW_CONSTRUCTION or $order->property_type == Order::VACANT_LAND) {
            if ($order->platMapFiles->first() == null) {
                $order->status = Order::STATUS_INCOMPLETE;
                $order->save();
            }
        }
        //if accessories files no uploaded set status incomplete
        foreach ($order->accessories as $accessory) {
            $accessory = $accessory->accessory;
            if ($accessory->prompt) {
                $fileFound = $order->files->where('accessory_id', $accessory->id)->first();
                if ($fileFound == null) {
                    $order->status = Order::STATUS_INCOMPLETE;
                    $order->save();
                }
            }
        }

        $order->needPayment = $needPayment;

        //send email
        /*if (!$needPayment) {
            try {
                $this->notificationService->orderCreated($order);
            } catch (Throwable $e) {
                logger()->error($e->getMessage());
            }
        }*/

        Session::flash("success", "Order created successfully.");

        return response()->json(compact('order'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
        return $order->with('accessories')->with('files')->where('id', $order->id)->first();
    }

    public function deleteFile($fileId)
    {
        $file = OrderAttachment::findOrFail($fileId);
        $file->delete();
        return response()->json(true);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Order $order, $data, $marker, $request)
    {
        // handle files
        $files = [];
        foreach (array_keys($data) as $key) {
            if (str_starts_with($key, 'file')) {
                // get accessory id
                [$fileKey, $accessoryId] = explode("_", $key);
                $request->file = $request->{$key};

                $mime = strtolower($request->file->getMimeType());
                if (in_array($mime, $this->fileService->allowedImageMimes)) {
                    $uploadImg = $this->fileService->uploadImage($request, 0, 0);
                    if (!$uploadImg['success']) {
                        return $this->responseJsonError($uploadImg['msg']);
                    }
                    $files[] = [
                        'filename' => $uploadImg['fileName'],
                        'accessory' => (int) $accessoryId ? (int) $accessoryId : null,
                        'plat_map' => $accessoryId == "plat-map" ?  1 : 0
                    ];
                } else if (in_array($mime, $this->fileService->allowedDocMimes)) {
                    $uploadFile = $this->fileService->uploadFile($request);
                    if (!$uploadFile['success']) {
                        return $this->responseJsonError($uploadFile['msg']);
                    }
                    $files[] = [
                        'filename' => $uploadFile['fileName'],
                        'accessory' => (int) $accessoryId ? (int) $accessoryId : null,
                        'plat_map' => $accessoryId == "plat-map" ?  1 : 0
                    ];
                } else {
                    return $this->responseJsonError("Invalid file format! Accepted file formats: PDF, GIF, PNG, JPG.");
                }
            }
        }

        $previousTotal = $order->total;

        $order->update([
            "address"            => $data['install_post_address'],
            "property_type"      => $data['install_post_property_type'],
            "desired_date_type"  => $data['install_post_desired_date'],
            "desired_date"       => $data["install_post_custom_desired_date"],
            "office_id"          => $data['install_post_office'],
            "post_id"            => $data['install_post_select_post'],
            "panel_id"           => $data['install_post_select_sign'],
            "comment"            => $data['install_post_comment'],
            "signage_fee"        => $data['install_post_signage'],
            "zone_fee"           => $data['install_post_zone_fee'],
            "total"              => $data['total'],
            "latitude"           => $marker->lat ?? $order->latitude,
            "longitude"          => $marker->lng ?? $order->longitude,
            'agent_own_sign'     => $data['install_post_select_sign_type'] == "-1" ? 1 : 0,
            'sign_at_property'   => $data['install_post_select_sign_type'] == "-2" ? 1 : 0,
            'user_id'            => auth()->user()->id,
            'agent_id'           => $data['install_post_agent'] == "null" ? null : $data['install_post_agent'],
            "zone_id"            => $data['zone_id'],
        ]);

        //delete old
        OrderAccessory::where('order_id', $order->id)->delete();
        $accessories = $data['install_post_select_accessories'];
        if (count($accessories)) {
            foreach ($accessories as $accessory) {
                $this->orderAccessoryService->create([
                    'order_id' => $order->id,
                    'accessory_id' => $accessory,
                ]);
            }
        }

        if (count($files)) {
            foreach ($files as $file) {
                $this->orderAttachmentService->create([
                    'order_id' => $order->id,
                    "name" => $file['filename'],
                    "accessory_id" => $file['accessory'],
                    "plat_map" => $file['plat_map'],
                ]);
            }
        }

        $order->status = Order::STATUS_RECEIVED;
        $order->save();

        $needPayment = false;

        if ($order->office) {
            if ($order->office->payment_method == Office::PAYMENT_METHOD_PAY_AT_TIME_OF_ORDER) {
                $order->status = Order::STATUS_INCOMPLETE;
                $order->save();
                $needPayment = true;
            };
        }

        if ($order->agent) {
            if ($order->agent->payment_method == Agent::PAYMENT_METHOD_PAY_AT_TIME_OF_ORDER) {
                $order->status = Order::STATUS_INCOMPLETE;
                $order->save();
                $needPayment = true;
            }
        }

        if ($order->payments->isNotEmpty()) {
            $order->status = Order::STATUS_RECEIVED;
            $order->save();
            $needPayment = false;
        } else {
            $order->status = Order::STATUS_INCOMPLETE;
            $order->save();
            $needPayment = true;
        }

        // if property type is "New construction" or "vacant land" and no files set status imcomplete
        if ($order->property_type == Order::NEW_CONSTRUCTION or $order->property_type == Order::VACANT_LAND) {
            if ($order->platMapFiles->first() == null) {
                $order->status = Order::STATUS_INCOMPLETE;
                $order->save();
            }
        }
        //if accessories files no uploaded set status incomplete
        foreach ($order->accessories as $accessory) {
            $accessory = $accessory->accessory;
            if ($accessory->prompt) {
                $fileFound = $order->files->where('accessory_id', $accessory->id)->first();
                if ($fileFound == null) {
                    $order->status = Order::STATUS_INCOMPLETE;
                    $order->save();
                }
            }
        }

        $order->editOrder = true;

        /*if ($previousTotal < $order->total) {
            $order->totalChanged = true;
            $needPayment = true;
        }*/

        $order->needPayment = $needPayment;

        Session::flash("success", "Order updated successfully.");

        return response()->json(compact('order'));
    }

    public function deleteAll()
    {
        $this->orderService->deleteAll();

        return $this->responseJsonSuccess((object)[]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function loadRepairPage()
    {
        $serviceSettings = ServiceSetting::first();

        return view('orders.repair.main', compact('serviceSettings'));
    }

    public function repairOrdersDatatable()
    {
        return $this->orderService->repairOrdersDatatable();
    }

    public function getOrderForRepairModal(Order $order)
    {
        return $order->with('accessories')
            ->with('files')
            ->with('office')
            ->with('agent')
            ->with('post')
            ->with('panel')
            ->where('id', $order->id)
            ->first();
    }

    public function getRepairZone(Order $order)
    {
        return $this->responseJsonSuccess($order->zone);
    }

    public function sendEmail(Order $order)
    {
        try {
            $this->notificationService->orderCreated($order);
        } catch (Throwable $e) {
            logger()->error($e->getMessage());
        }
    }
}

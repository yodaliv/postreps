<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Http\Traits\HelperTrait;
use App\Models\Payment;
use App\Models\ServiceSetting;
use App\Models\User;
use App\Services\AccessoryService;
use App\Services\AuthorizeNetService;
use App\Services\OfficeService;
use App\Services\OrderService;
use App\Services\PanelService;
use App\Services\PaymentService;
use App\Services\PostService;

class HomeController extends Controller
{
    use HelperTrait;

    protected $notificationService;

    protected $officeService;

    protected $postService;

    protected $panelService;

    protected $accessoryService;

    protected $orderService;

    protected $authorizenetService;

    protected $paymentService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        NotificationService $notificationService,
        OfficeService $officeService,
        PostService $postService,
        PanelService $panelService,
        AccessoryService $accessoryService,
        OrderService $orderService,
        AuthorizeNetService $authorizenetService,
        PaymentService $paymentService
    ) {
        $this->notificationService = $notificationService;

        $this->officeService = $officeService;

        $this->postService = $postService;

        $this->panelService = $panelService;

        $this->accessoryService = $accessoryService;

        $this->orderService = $orderService;

        $this->authorizenetService = $authorizenetService;

        $this->paymentService = $paymentService;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $authUser = auth()->user();

        if ($authUser->role == User::ROLE_SUPER_ADMIN) {
            $offices = $this->officeService->getAll();
            $posts = $this->postService->getOrderByListingOrderAndName();
            $panels = $this->panelService->getOrderByListingOrderAndName();
            $accessories = $this->accessoryService->getOrderByListingOrderAndName();

            $service_settings = ServiceSetting::first();

            $data = compact('offices', 'accessories', 'panels', 'posts', 'service_settings');

            return view('orders.recent', $data);
        }

        if ($authUser->role == User::ROLE_OFFICE) {
            return view('orders.recent');
        }

        if ($authUser->role == User::ROLE_AGENT) {
            dd("Agent user login ");
        }

        abort(404);
    }

    public function contact(Request $request)
    {
        $this->notificationService->contact($request->all());

        return $this->backWithSuccess('Message sent successfully. We will respond shortly.');
    }
}

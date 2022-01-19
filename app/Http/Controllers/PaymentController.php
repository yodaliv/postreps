<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayment;
use App\Http\Traits\HelperTrait;
use App\Models\Accessory;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Agent;
use App\Services\AuthorizeNetService;
use App\Services\OrderService;
use App\Services\NotificationService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    use HelperTrait;

    protected $paymentService;

    protected $orderService;

    protected $authorizeNetService;
    protected $notificationService;

    public function __construct(
        PaymentService $paymentService,
        OrderService $orderService,
        AuthorizeNetService $authorizeNetService,
        NotificationService $notificationService
    ) {
        $this->paymentService = $paymentService;

        $this->orderService = $orderService;

        $this->authorizeNetService = $authorizeNetService;

        $this->notificationService = $notificationService;
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
    public function store(Request $request)
    {
        //
    }

    public function pay(CreatePayment $request)
    {
        $data = $request->all();

        //store payment
        $order = $this->orderService->findById($request->order_id);

        //
        $billTo = [
            "first_name" => "",
            "last_name" => "",
            "address" => "",
            "city" => "",
            "state" => "",
            "zipcode" => "",
            "email" => ''
        ];

        if ($order->office) {
            $billTo['first_name'] = $order->office->user->first_name;
            $billTo['last_name'] = $order->office->user->last_name;
            $billTo['address'] = $order->office->user->address;
            $billTo['city'] = $order->office->user->city;
            $billTo['state'] = $order->office->user->state;
            $billTo['zipcode'] = $order->office->user->zipcode;
            $billTo['email'] = $order->office->user->email;
            $billTo['userId'] = $order->office->user->id;
        }

        if ($order->agent) {
            $billTo['first_name'] = $order->agent->user->first_name;
            $billTo['last_name'] = $order->agent->user->last_name;
            $billTo['address'] = $order->agent->user->address;
            $billTo['city'] = $order->agent->user->city;
            $billTo['state'] = $order->agent->user->state;
            $billTo['zipcode'] = $order->agent->user->zipcode;
            $billTo['email'] = $order->agent->user->email;

            if ($order->agent->payment_method == Agent::PAYMENT_METHOD_OFFICE_PAY) {
                $billTo['userId'] = $order->office->user->id;
            } else{
                $billTo['userId'] = $order->agent->user->id;
            }
        }

        if ($data['payment_type'] == 'use_card') { //Existing card
            //Authorize payment through customer profile
            $cardOwner = User::find($billTo['userId']);

            $payment = $this->authorizeNetService->chargeCustomerProfileAuthOnly(
                $cardOwner->authorizenet_profile_id,
                $data['card_profile'],
                $order
            );

            $this->savePaymentAndUpdateOrder($order);

            Session::flash("success", "Payment submitted successfully. Thank you!");

            try {
                $this->notificationService->orderCreated($order);
            } catch (Throwable $e) {
                logger()->error($e->getMessage());
            }
        } else { //New card
            //card numbers
            $card_name = trim($data['card_name']);
            $card_number = trim($data['card_number']);
            $card_numbers = explode(' ', $card_number);
            //$last_number = encrypt(end($card_numbers));

            $exyear = trim($data['expire_date_year']);
            $exmonth = trim($data['expire_date_month']);
            $expireDate = "$exyear-$exmonth";
            $card_code = $data['card_code'];

            // card info
            $cardInfo = [
                "cardName" => $card_name,
                "cardNumber" => str_replace(' ', '', $card_number),
                "expirationDate" => $expireDate,
                "cardCode" => $card_code,
            ];

            //authorize payment
            $payment = $this->authorizeNetService->createPayment($cardInfo, $billTo, $order);
            if ($payment['messages']['resultCode'] == "Error") {
                return $payment;
            } else {
                $this->savePaymentAndUpdateOrder($order);

                Session::flash("success", "Payment submitted successfully. Thank you!");
            }
        }

        try {
            $this->notificationService->orderCreated($order);
        } catch (Throwable $e) {
            logger()->error($e->getMessage());
        }

        return $payment;
    }

    public function savePaymentAndUpdateOrder(Order $order)
    {
        $payment = $this->paymentService->create([
            "order_id" => $order->id,
            "paid_by" => auth()->id(),
            "office_id" => $order->office_id,
            "agent_id" => $order->agent_id,
            "amount" => $order->total
        ]);

        //
        $order->status = Order::STATUS_RECEIVED;

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

        $order->save();
    }

    public function getSavedCards(User $user)
    {
        $storedPaymentProfiles = $user->authorizenet_payment_profiles;
        $authorizeNetCustomerId = $user->authorizenet_profile_id;

        $returnData = [];
        if ($storedPaymentProfiles) {
            foreach ($storedPaymentProfiles as $storedPaymentProfile) {
                $paymentProfile = $this->authorizeNetService->getPaymentProfile(
                    $authorizeNetCustomerId,
                    $storedPaymentProfile->payment_profile_id
                );
                //info($paymentProfile);
                if (isset($paymentProfile['paymentProfile'])) {
                    $cardinfo = $paymentProfile['paymentProfile']['payment']['creditCard'];
                    $returnData[$storedPaymentProfile->payment_profile_id]['cardNumber'] = str_replace('XXXX', 'XXXX-', $cardinfo['cardNumber']);
                    $returnData[$storedPaymentProfile->payment_profile_id]['cardType'] = $cardinfo['cardType'];
                    $expDateArray = explode('-', $cardinfo['expirationDate']);
                    $expDate = "{$expDateArray[1]}/{$expDateArray[0]}";
                    $returnData[$storedPaymentProfile->payment_profile_id]['expDate'] = $expDate;
                }
            }
        }

        return response()->json($returnData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }
}

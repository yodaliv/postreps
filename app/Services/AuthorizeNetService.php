<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\{User, AuthorizenetPaymentProfile};

class AuthorizeNetService
{
    private $production = false;

    private $url;

    private $validationMode;

    public function __construct()
    {
        $this->production = config('authorizenet.production');
        if ($this->production) {
            $this->url = "https://api.authorize.net/xml/v1/request.api";
            $this->validationMode = 'liveMode';
        } else {
            $this->url = "https://apitest.authorize.net/xml/v1/request.api";
            $this->validationMode = 'testMode';
        }
    }

    public function template()
    {
        return json_decode('{
            "createTransactionRequest": {
                "merchantAuthentication": {
                    "name": "5KP3u95bQpv",
                    "transactionKey": "346HZ32z3fP4hTG2"
                },
                 "transactionRequest": {
                    "transactionType": "authOnlyTransaction",
                    "amount": "5",
                    "payment": {
                        "creditCard": {
                            "cardNumber": "5424000000000015",
                            "expirationDate": "2025-12",
                            "cardCode": "999"
                        }
                    },
                    "order": {
                        "invoiceNumber": "656565"
                    },                         
                    "tax": {
                        "amount": "0",
                        "name": "No tax",
                        "description": "No Tax"
                    },               
                    "billTo": {
                        "firstName": "Ellen",
                        "lastName": "Johnson",
                        "company": "Souveniropolis",
                        "address": "14 Main Street",
                        "city": "Pecan Springs",
                        "state": "TX",
                        "zip": "44628",
                        "country": "US"
                    },
                    "authorizationIndicatorType": {
                        "authorizationIndicator": "final"
                    }
                }
            }
        }');
    }

    public function createProfileTemplate()
    {
        return json_decode('{
            "createCustomerProfileRequest": {
                "merchantAuthentication": {
                    "name": "4U6Br3P7kUef",
                    "transactionKey": "2vYc9v3t93qA3MkX"
                },
                "profile": {
                    "merchantCustomerId": "Merchant_Customer_ID",
                    "description": "Profile description here",
                    "email": "customer-profile-email@here.com",
                    "paymentProfiles": {
                        "customerType": "individual",
                        "payment": {
                            "creditCard": {
                                "cardNumber": "4111111111111111",
                                "expirationDate": "2025-12"
                            }
                        }
                    }
                },
                "validationMode": "testMode"
            }
        }');
    }

    public function createPaymentProfileTemplate()
    {
        return json_decode('{
            "createCustomerPaymentProfileRequest": {
                "merchantAuthentication": {
                    "name": "5KP3u95bQpv",
                    "transactionKey": "346HZ32z3fP4hTG2"
                },
                "customerProfileId": "10000",
                "paymentProfile": {
                    "billTo": {
                        "firstName": "John",
                        "lastName": "Doe",
                        "address": "123 Main St.",
                        "city": "Bellevue",
                        "state": "WA",
                        "zip": "98004",
                        "country": "US",
                        "phoneNumber": "000-000-0000"
                    },
                    "payment": {
                        "creditCard": {
                            "cardNumber": "4111111111111111",
                            "expirationDate": "2023-12"
                        }
                    },
                    "defaultPaymentProfile": false
                },
                "validationMode": "liveMode"
            }
        }');
    }
    public function createPaymentProfile($cardInfo, $billTo, $cardOwner)
    {
        $data = $this->createPaymentProfileTemplate();

        //auth
        $data->createCustomerPaymentProfileRequest->merchantAuthentication->name = env('AUTHORIZE_NET_LOGIN_ID');
        $data->createCustomerPaymentProfileRequest->merchantAuthentication->transactionKey = env('AUTHORIZE_NET_TRANSACTION_KEY');

        $data->createCustomerPaymentProfileRequest->customerProfileId = $cardOwner->authorizenet_profile_id;
        $data->createCustomerPaymentProfileRequest->validationMode = $this->validationMode;

        //card number
        $data->createCustomerPaymentProfileRequest->paymentProfile->payment->creditCard->cardNumber        = $cardInfo['cardNumber'];
        $data->createCustomerPaymentProfileRequest->paymentProfile->payment->creditCard->expirationDate    = $cardInfo['expirationDate'];
        $data->createCustomerPaymentProfileRequest->paymentProfile->payment->creditCard->cardCode          = $cardInfo['cardCode'];

        //bill to
        $data->createCustomerPaymentProfileRequest->paymentProfile->billTo->firstName  = $billTo['first_name'];
        $data->createCustomerPaymentProfileRequest->paymentProfile->billTo->lastName   = $billTo['last_name'];
        $data->createCustomerPaymentProfileRequest->paymentProfile->billTo->address    = $billTo['address'];
        $data->createCustomerPaymentProfileRequest->paymentProfile->billTo->city       = $billTo['city'];
        $data->createCustomerPaymentProfileRequest->paymentProfile->billTo->state      = $billTo['state'];
        $data->createCustomerPaymentProfileRequest->paymentProfile->billTo->zip        = $billTo['zipcode'];

        //send request
        $response = Http::post($this->url, (array)$data);

        //get response
        $createPaymentProfile = $this->jsonDecode(trim($response->body()));

        return $createPaymentProfile;
    }

    public function createPayment($cardInfo, $billTo, $order)
    {        
        //transaction request template
        $data = $this->template();

        //auth
        $data->createTransactionRequest->merchantAuthentication->name = env('AUTHORIZE_NET_LOGIN_ID');
        $data->createTransactionRequest->merchantAuthentication->transactionKey = env('AUTHORIZE_NET_TRANSACTION_KEY');

        //card number
        $data->createTransactionRequest->transactionRequest->payment->creditCard->cardNumber        = $cardInfo['cardNumber'];
        $data->createTransactionRequest->transactionRequest->payment->creditCard->expirationDate    = $cardInfo['expirationDate'];
        $data->createTransactionRequest->transactionRequest->payment->creditCard->cardCode          = $cardInfo['cardCode'];

        //bill to
        $data->createTransactionRequest->transactionRequest->billTo->firstName  = $billTo['first_name'];
        $data->createTransactionRequest->transactionRequest->billTo->lastName   = $billTo['last_name'];
        $data->createTransactionRequest->transactionRequest->billTo->address    = $billTo['address'];
        $data->createTransactionRequest->transactionRequest->billTo->city       = $billTo['city'];
        $data->createTransactionRequest->transactionRequest->billTo->state      = $billTo['state'];
        $data->createTransactionRequest->transactionRequest->billTo->zip        = $billTo['zipcode'];

        //amount
        $data->createTransactionRequest->transactionRequest->amount = $order->total;

        $data->createTransactionRequest->transactionRequest->order->invoiceNumber =  $order->order_number.$order->id;

        //send request
        $response = Http::post($this->url, (array)$data);

        //get response
        $transaction = $this->jsonDecode(trim($response->body()));

        //Get user of the card owner
        $cardOwner = User::find($billTo['userId']);

        //check if profile exists
        $getProfile = $this->getCustomerProfile($cardOwner->authorizenet_profile_id);
        if (isset($getProfile['profile'])) {
            //Add card to existing customer profile by creating payment profile
            $createPaymentProfile = $this->createPaymentProfile($cardInfo, $billTo, $cardOwner);

            //Store the payment profile in db
            if (isset($createPaymentProfile['customerPaymentProfileId'])) {
                $storePaymentProfile = AuthorizenetPaymentProfile::firstOrCreate(
                    ['payment_profile_id' => $createPaymentProfile['customerPaymentProfileId']],
                    ['user_id' => $cardOwner->id]                    
                );
            }        
        } else {
            //create profile
            //if customer profile doesn't exist then create one
            $profile = $this->createProfile(
                [
                    "email" => $billTo['email'],
                    "cardNumber" => $cardInfo['cardNumber'],
                    "expirationDate" => $cardInfo['expirationDate'],
                    "cardOwner" => $cardOwner
                ]
            );            
        }        

        return $transaction;
    }

    public function jsonDecode($jsonString)
    {
        return json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $jsonString), true);
    }

    public function genId($prefix)
    {
        return str_replace('.', '_', uniqid($prefix . microtime(true)));
    }

    public function createProfile($params)
    {
        $data = $this->createProfileTemplate();
        // auth
        $data->createCustomerProfileRequest->merchantAuthentication->name = env('AUTHORIZE_NET_LOGIN_ID');
        $data->createCustomerProfileRequest->merchantAuthentication->transactionKey = env('AUTHORIZE_NET_TRANSACTION_KEY');
        $data->createCustomerProfileRequest->profile->merchantCustomerId = "";
        $data->createCustomerProfileRequest->profile->email = $params['email'];
        $data->createCustomerProfileRequest->profile->paymentProfiles->payment->creditCard->cardNumber = $params['cardNumber'];
        $data->createCustomerProfileRequest->profile->paymentProfiles->payment->creditCard->expirationDate = $params['expirationDate'];
        //send request
        $response = Http::post($this->url, (array)$data);
        //get response
        $transaction = $this->jsonDecode(trim($response->body()));

        //Store authorize customer profile 
        if (isset($transaction['customerProfileId'])) {
            $cardOwner = $params['cardOwner'];
            $cardOwner->authorizenet_profile_id = $transaction['customerProfileId'];
            $cardOwner->save();

            //Store payment profiles in DB for faster queries
            $paymentProfilesList = $transaction['customerPaymentProfileIdList'];
            foreach ($paymentProfilesList as $paymentProfileId) {
                $storePaymentProfile = AuthorizenetPaymentProfile::firstOrCreate(
                    ['payment_profile_id' => $paymentProfileId],
                    ['user_id' => $cardOwner->id]                    
                );
            }            
        }

        return $transaction;
    }

    public function getCustomerProfileTemplate()
    {
        return json_decode('
                    {
                        "getCustomerProfileRequest": {
                            "merchantAuthentication": {
                                "name": "4U6Br3P7kUef",
                                "transactionKey": "2vYc9v3t93qA3MkX"
                            },
                            "customerProfileId": "10000",
                            "includeIssuerInfo": "true"
                        }
                    }
        ');
    }

    public function getCustomerProfile($authorizeNetCustomerId)
    {
        $data = $this->getCustomerProfileTemplate();
        // auth
        $data->getCustomerProfileRequest->merchantAuthentication->name = env('AUTHORIZE_NET_LOGIN_ID');
        $data->getCustomerProfileRequest->merchantAuthentication->transactionKey = env('AUTHORIZE_NET_TRANSACTION_KEY');
        $data->getCustomerProfileRequest->customerProfileId = $authorizeNetCustomerId;
        //send request
        $response = Http::post($this->url, (array)$data);
        //get response
        $transaction = $this->jsonDecode(trim($response->body()));
        return $transaction;
    }

    public function CaptureTemplate()
    {
        return json_decode('
        {
            "createTransactionRequest": {
                "merchantAuthentication": {
                    "name": "4U6Br3P7kUef",
                    "transactionKey": "2vYc9v3t93qA3MkX"
                },
                "refId": "123456",
                "transactionRequest": {
                    "transactionType": "priorAuthCaptureTransaction",
                    "amount": "5",
                    "refTransId": "1234567890"
                }
            }
        }

          ');
    }

    public function capture($amount, $transId)
    {
        $data = $this->CaptureTemplate();
        // auth
        $data->createTransactionRequest->merchantAuthentication->name = env('AUTHORIZE_NET_LOGIN_ID');
        $data->createTransactionRequest->merchantAuthentication->transactionKey = env('AUTHORIZE_NET_TRANSACTION_KEY');
        $data->createTransactionRequest->transactionRequest->amount = $amount;
        $data->createTransactionRequest->transactionRequest->refTransId = $transId;
        //send request
        $response = Http::post($this->url, (array)$data);
        //get response
        $transaction = $this->jsonDecode(trim($response->body()));
        return $transaction;
    }

    public function getPaymentProfileTemplate()
    {
        return json_decode(
            '{
                "getCustomerPaymentProfileRequest": {
                    "merchantAuthentication": {
                        "name": "6S7euv4PZhK",
                        "transactionKey": "2w522hMg22EBEbVf"
                    },
                    "customerProfileId": "903341534",
                    "customerPaymentProfileId": "20000",
                    "unmaskExpirationDate": "true",
                    "includeIssuerInfo": "false"                   
                }
            }'
        );
    }

    public function getPaymentProfile($authorizeNetCustomerId, $customerPaymentProfileId)
    {
        $data = $this->getPaymentProfileTemplate();
        // auth
        $data->getCustomerPaymentProfileRequest->merchantAuthentication->name = env('AUTHORIZE_NET_LOGIN_ID');
        $data->getCustomerPaymentProfileRequest->merchantAuthentication->transactionKey = env('AUTHORIZE_NET_TRANSACTION_KEY');
        $data->getCustomerPaymentProfileRequest->customerProfileId = "$authorizeNetCustomerId";
        $data->getCustomerPaymentProfileRequest->customerPaymentProfileId = "$customerPaymentProfileId";

        //send request
        $response = Http::post($this->url, (array)$data);

        //get response
        $paymentProfile = $this->jsonDecode(trim($response->body()));

        return $paymentProfile;
    }

    public function chargeCustomerProfileAuthOnlyTemplate()
    {
        return json_decode(
            '{
                "createTransactionRequest": {
                    "merchantAuthentication": {
                        "name": "9bSaKC66uHg",
                        "transactionKey": "8xszx7B7674QxHqe"
                    },
                    "transactionRequest": {
                        "transactionType": "authOnlyTransaction",
                        "amount": "45",
                        "profile": {
                            "customerProfileId": "40338125",
                            "paymentProfile": { "paymentProfileId": "1000177237" }
                        },
                        "order": {
                            "invoiceNumber": "656565"
                        },                         
                        "tax": {
                            "amount": "0",
                            "name": "No tax",
                            "description": "No Tax"
                        }, 
                        "authorizationIndicatorType": {
                            "authorizationIndicator": "final"
                        }
                    }
                }
            }'
        );
    }

    public function chargeCustomerProfileAuthOnly($authorizeNetCustomerId, $customerPaymentProfileId, $order)
    {
        $data = $this->chargeCustomerProfileAuthOnlyTemplate();
        // auth
        $data->createTransactionRequest->merchantAuthentication->name = env('AUTHORIZE_NET_LOGIN_ID');
        $data->createTransactionRequest->merchantAuthentication->transactionKey = env('AUTHORIZE_NET_TRANSACTION_KEY');

        $data->createTransactionRequest->transactionRequest->profile->customerProfileId = "$authorizeNetCustomerId";
        $data->createTransactionRequest->transactionRequest->profile->paymentProfile->paymentProfileId = "$customerPaymentProfileId";
        $data->createTransactionRequest->transactionRequest->order->invoiceNumber =  $order->order_number.$order->id;

        $data->createTransactionRequest->transactionRequest->amount = $order->total;

        //send request
        $response = Http::post($this->url, (array)$data);

        //get response
        $transaction = $this->jsonDecode(trim($response->body()));

        return $transaction;
    }
}

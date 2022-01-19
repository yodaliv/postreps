<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <style>
        @import url(https://fonts.googleapis.com/css?family=Nunito);

        html {
            max-width: 100%;
            overflow-x: hidden;
        }

        @media screen and (min-width: 300px) and (max-width: 767px) and (orientation: landscape) {
            html {
                transform: rotate(-90deg);
                transform-origin: left top;
                width: 100vh;
                overflow-x: hidden;
                position: absolute;
                top: 100%;
                left: 0;
            }
        }

        body {
            max-width: 100%;
            overflow-x: hidden;
        }
        body {
            margin: 0;
            font-family: "Nunito", sans-serif;
            font-size: 0.9rem;
            font-weight: 400;
            line-height: 1.6;
            color: #212529;
            text-align: left;
            background-color: #f8fafc;
        }

        .container {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
        }

        .text-orange {
            color: #FF9047;
        }

        .text-center {
            text-align: center !important;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .p-3 {
            padding: 1rem !important;
        }

        .text-primary {
            color: #3490dc !important;
        }

        a.link {
            color: #3030b1;
            text-decoration: none;
            cursor: pointer;
        }

        .pt-5,
        .py-5 {
            padding-top: 3rem !important;
        }

        .pb-5,
        .py-5 {
            padding-bottom: 3rem !important;
        }

        h3,
        h4 {
            font-weight: 700;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin-top: 0;
            margin-bottom: 0.5rem;
        }


        h4,
        .h4 {
            font-size: 1.35rem;
        }

        @media (min-width: 1200px) {
            .container-xl,
            .container-lg,
            .container-md,
            .container-sm,
            .container {
                max-width: 1140px;
            }
        }

        @media (min-width: 576px) {
            .container-sm,
            .container {
                max-width: 540px;
            }
        }

        @media (min-width: 768px) {
            .container-md,
            .container-sm,
            .container {
                max-width: 720px;
            }
        }

        @media (min-width: 992px) {
            .container-lg,
            .container-md,
            .container-sm,
            .container {
                max-width: 960px;
            }
        }

        .container,
        .container-fluid,
        .container-xl,
        .container-lg,
        .container-md,
        .container-sm {
            width: 100%;
            padding-right: 15px;
            padding-left: 15px;
            margin-right: auto;
            margin-left: auto;
        }

    </style>
</head>

<body class="py-5">

    <h4 class="text-center text-orange"><strong>Post Reps
            {{ $order->getOrderType() == 'install' ? 'Installation' : '' }} Notification </strong></h4>
    <div class="container bg-white p-3">
        <h4><strong>{{ $order->agent ? $order->agent->user->name : '' }}</strong></h4>
        <p>{{ $order->office ?$order->office->user->name : '' }}</p>
        <p>Notice for {{ $order->getOrderType() == 'install' ? 'installation' : '' }} order
            #{{ $order->order_number }}
            at:
        </p>
        <h4 class="text-primary"><strong>{{ $order->address }}</strong></h4>

        <p>
            <strong>Date of service</strong>: {{ $order->created_at->format('m/d/Y') }}<br>
            <strong>Order status</strong>: {{ ucfirst($order->getStatus()) }} <br>
            <strong>Order comment</strong>: {{ $order->comment }}
        </p>

        <p>
            For further infomation, you can verfiy the address , status, and order details by logging in online at
            <a href="{{ env('APP_URL') }}" class="link">{{ env('APP_URL') }}</a>
        </p>

        <p>Regards,</p>
        <p>The PostReps Team</p>

        <hr>
        <p>This is an auto generated email, Don not try reply to this email, replies will not be received. if you need to
            contact
            us, please visit us online at <a href="{{ env('APP_URL') }}"
                class="link">{{ env('APP_URL') }}</a>
        </p>
    </div>

</body>

</html>

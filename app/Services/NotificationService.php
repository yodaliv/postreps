<?php

declare(strict_types=1);

namespace App\Services;

use App\Mail\AgentRegister;
use App\Mail\AgentRegisterWithoutOFfice;
use App\Mail\OrderCreated;
use App\Models\Agent;
use App\Models\Office;
use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\{Mail, App};

class NotificationService
{
    public function contact(array $data): void
    {
        $recipient = env('CONTACT_EMAIL');

        if (App::environment('local')) {
            $recipient = 'eldon@ecbctech.com';
        }

        $from = env('MAIL_FROM_ADDRESS');

        $content = nl2br($data['message']);

        $html = "<b>Name:</b> {$data['name']}<br>";
        $html .= "<b>Email:</b> {$data['email']}<br>";
        $html .= "<b>Message:</b><br> {$content}";

        Mail::send([], [], function ($message) use ($html, $recipient, $from, $data) {
            $message->to($recipient)
                ->subject('PostReps Contact form')
                ->from($from)
                ->replyTo($data['email'])
                ->setBody($html, 'text/html');
        });
    }

    public function newAgentRegister($agent)
    {
        Mail::to(env('CONTACT_EMAIL'))->send(new AgentRegister($agent));
        return  true;
    }
    public function newAgentRegisterWithoutOffice($agent)
    {
        Mail::to(env('CONTACT_EMAIL'))->send(new AgentRegisterWithoutOFfice($agent));
        return  true;
    }

    public function verifyUserEmail(User $user)
    {
        event(new Registered($user));
        return true;
    }

    public function orderCreated(Order $order)
    {
        $email  = new OrderCreated($order);
        //send email to contact email
        Mail::to(env('CONTACT_EMAIL'))->send($email);
        //if no agent send email to office
        if ($order->agent_id == null) {
            Mail::to(Office::find($order->office_id)->user->email)->send($email);
        } else {
            Mail::to(Agent::find($order->agent_id)->user->email)->send($email);
        }
        return true;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\OrderPending;
use Illuminate\Support\Facades\Mail;

class MailSending extends Controller
{
    public function sendOrderPendingEmail($order)
    {
        Mail::to($order->user->email)->send(new OrderPending($order));
    }
}

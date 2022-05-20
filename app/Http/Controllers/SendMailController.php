<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendMailController extends Controller
{
    public function send_mail(Request $request){
        $data = $request->content;
        $email = $request->email ?? '';

        Mail::to($email)->send(new SendMail($data));
        return response()->json([ 'message' => 'Gửi email thành công!', $data], 200);
    }
}

<?php

namespace App\Http\Controllers\Backoffice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\User;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bookings = Booking::all();
        return view('backoffice.bookings.index',compact('bookings'));
    }

    public function set_cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'cancel';
        $booking->is_active = 0;
        $booking->save();
        $this->sendFcm(
            $booking->patient->user->fcmTokens,
            "Booking anda telah dibatalkan",
            "Booking kamu dibatalkan ya.",
            "Booking kamu sudah dibatalkan, ayo booking Lagi!"
        );
        return redirect()->route('bookings.index')
                        ->with('success','Booking berhasil dibatalkan');
    }

    public function set_done($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->status = 'done';
        $booking->is_active = 0;
        $booking->save();
        $this->sendFcm(
            $booking->patient->user->fcmTokens,
            "Booking anda telah selesai",
            "Terima kasih sudah menggunakan Aplikasi Halodoc!.",
            "Booking kamu sudah selesai, ayo booking Lagi!"
        );
        return redirect()->route('bookings.index')
                        ->with('success','Booking selesai');
    }

    public function send_notif($id)
    {
        $booking = Booking::findOrFail($id);
        $this->sendFcm(
            $booking->patient->user->fcmTokens,
            $booking->doctor->name." Sudah Nungguin kamu nih? Kuy",
            "Hai ".$booking->patient->user->name." Ayo selesaikan bookingan kamu",
            "Saat nya bertemu Dokter!"
        );
        return redirect()->route('bookings.index')
                        ->with('success','Booking notif berhasil dikirim');
    }

    public function sendFcm($tokens, $title,  $short_desc, $content_text) 
    {
        foreach($tokens as $token) {
            $response = Http::withHeaders([
                'Authorization' => getenv('FCM_KEY'),
                'Content-Type' => 'application/json'
            ])->post('https://fcm.googleapis.com/fcm/send', [
                'to' => $token->token,
                'notification' => [
                    "body" => $short_desc,
                    "title"=> $title
                ],
                "data" => [
                    "body" => $content_text,
                    "title"=> $title
                ]
            ]);
        }
    }

    public function send_notif_message(Request $request)
    {
        $request->validate([
            'to' => ['required', 'string', 'max:255'],
            'title' => ['required'],
            'short_desc' => ['required', 'string'],
            'content_text' => ['required',],
            'tag' => ['required',],
        ]);
        $user = User::where('email', $request->to)->first();
        $tokens = $user->fcmTokens;
        if(count($tokens) > 0) {
            foreach($tokens as $token) {
                $response = Http::withHeaders([
                    'Authorization' => getenv('FCM_KEY'),
                    'Content-Type' => 'application/json'
                ])->post('https://fcm.googleapis.com/fcm/send', [
                    'to' => $token->token,
                    'notification' => [
                        "body" => $request->short_desc,
                        "title"=> $request->title
                    ],
                    "data" => [
                        "body" => $request->content_text,
                        "title"=> $request->title
                    ]
                ]);
            }
            Notification::create([
                'user_id'=> $user->id,
                'title' => $request->title,
                'short_desc' => $request->short_desc,
                'content_text' => $request->content_text,
                'image_url' => $request->image_url,
                'hasRead' => 0,
                'tag' => $request->tag,
                'date' => now()
            ]);
            return redirect()->route('notif_send')
                        ->with('success','Notif berhasil dikirim');
        }
    }
}

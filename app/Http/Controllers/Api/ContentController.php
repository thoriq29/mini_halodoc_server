<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Http;

use App\Models\Content;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Notification;

class ContentController extends Controller
{
    public $successStatus = 200;

    public function list(Request $request, Content $content)
    {
        $content = $content->newQuery();

        // dd($request->get('category'));

        $category = $request->get('category');
        if($request->has('category')) {
            $content->whereIn('content_category_id', function($query) use($category){
                $query->from('content_categories')->select('id')->whereIn('slug', $category);
            });

        }
        $content
            ->join('content_categories', 'content_categories.id', '=', 'contents.content_category_id')
            ->join('hospitals', 'hospitals.id', '=', 'contents.hospital_id')
            ->select(
                'contents.title',
                'contents.desc',
                'contents.image_url',
                'contents.date',
                'content_categories.name as category',
                'hospitals.name as hospital'
            );
        return response()->json(
            [
                'success' => true,
                'data'=> $content->get()
            ], $this->successStatus
        );
    }

    public function search(Request $request, Content $content, Doctor $doctor)
    {
        $content = $content->newQuery();
        $doctor = $doctor->newQuery();

        $query = $request->get('query');

        if($request->has('query')) {
            $content->where('title', 'LIKE', '%'.$query.'%');
            $doctor
                ->where('doctors.name', 'LIKE', '%'.$query.'%')
                ->orWhere('spesialist_id', function($queri) use($query){
                    $queri->from('spesialists')->select('id')->where('spesialists.name',  'LIKE', '%'.$query.'%');
                });
        }
        
        $content->join('content_categories', 'content_categories.id', '=', 'contents.content_category_id')
                ->get();
        
        $doctor->join('spesialists', 'spesialists.id', '=', 'doctors.spesialist_id')
                ->select(
                    'doctors.id as doc_id',
                    'doctors.name as title',
                    'doctors.image_url as img_url',
                    'spesialists.name as desc'
                );
        $data = $content->get()->merge($doctor->get());
        return response()->json(
            [
                'success' => true,
                'data'=> $data
            ], $this->successStatus
        );
    }

    public function sendUserNotification(Request $request)
    {

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
                'image_url' => "",
                'hasRead' => 0,
                'tag' => $request->tag,
                'date' => now()
            ]);
            return response()->json(
                [
                    'success' => true,
                    'data'=> "Notifikasi berhasil dikirim"
                ], $this->successStatus
            );
        }
        return response()->json(
            [
                'success' => true,
                'data'=> "Tidak ada token"
            ], $this->successStatus
        );
    }
}

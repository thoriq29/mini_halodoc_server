<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Content;
use App\Models\Doctor;

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
                'hospitals.name'
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
                ->select(
                    'contents.id as id',
                    'contents.title as title',
                    'contents.image_url as img_url',
                    'content_categories.name as desc'
                )->get();
        
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
}

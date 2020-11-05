<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hospital;

class HospitalController extends Controller
{

    public $successStatus = 200;

    public function detail(Request $request, $id)
    {
        $hospital = Hospital::find($id);
        return response()->json(
            [
                'success' => true,
                'data'=> $hospital->with(['schedules.doctors', 'contents'])->get()
            ], $this->successStatus
        );
    }
}

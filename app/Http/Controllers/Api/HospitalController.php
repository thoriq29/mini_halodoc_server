<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Hospital;
use App\Models\Department;

use Carbon\Carbon;

class HospitalController extends Controller
{

    public $successStatus = 200;


    public function hospitals(Request $request, $id)
    {
        $hospitals = Department::find($id)->hospitals();
        return response()->json(
            [
                'success' => true,
                'data'=> $hospitals->with(['schedules.doctors', 'hospital_type'])->get()
            ], $this->successStatus
        );
    }
    
    public function detail(Request $request, $dep_id, $hospital_id)
    {   
        $hospital = Department::find($dep_id)->hospitals->find($hospital_id);
        return response()->json(
            [
                'success' => true,
                'data'=> $hospital->with(['schedules.doctors', 'contents', 'hospital_type'])->get()
            ], $this->successStatus
        );
    }
}

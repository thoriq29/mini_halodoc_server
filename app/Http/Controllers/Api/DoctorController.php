<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Doctor;

class DoctorController extends Controller
{

    public $successStatus = 200;

    public function doctors(Request $request)
    {
        $doctors = Doctor::with('spesialist')->get();
        return response()->json(
            [
                'success' => true,
                'data'=> $doctors
            ], $this->successStatus
        );
    }

    public function doctor(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor['spesialist'] = $doctor->spesialist;
        $doctor['schedules'] = $doctor->schedules;
        return response()->json(
            [
                'success' => true,
                'data'=> $doctor
            ], $this->successStatus
        );
    }
}

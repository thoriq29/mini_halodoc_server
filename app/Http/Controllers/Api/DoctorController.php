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
        $doctors = Doctor::with('spesialist', 'bookings')->get();
        return response()->json(
            [
                'success' => true,
                'data'=> $doctors
            ], $this->successStatus
        );
    }

    public function doctor(Request $request, $id)
    {
        $doctor = Doctor::where('id', $id);
        return response()->json(
            [
                'success' => true,
                'data'=> $doctor->with(
                    [
                    'spesialist'=> function($query) {
                        $query->select(['id', 'slug', 'name', 'description']);
                    },
                    'schedules'=> function($query) {
                        $query->with(['hospital' => function($hospital) use($query) {
                            $query->select(['id', 'day', 'start_at', 'end_at'])->orderBy('created_at', 'ASC');
                            $hospital->select(['id', 'name']);
                        }]);

                    },
                ])->first(),
            ], $this->successStatus
        );
    }
}

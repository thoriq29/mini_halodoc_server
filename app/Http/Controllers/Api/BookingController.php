<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Booking;

use Carbon\Carbon;
use Validator;

class BookingController extends Controller
{

    public $successStatus = 200;

    public function patientBookings(Request $request)
    {
        $user = Auth::user();
        if(!$user->isPatient()) {
            return response()->json([
                'success' => false,
                'data' => "Anda bukan pasien"
            ], $this->successStatus);
        } 

        $bookings = $user->patient()->first()->bookings()->with('doctor', 'hospital', 'doctor.spesialist')->get();
        return response()->json([
            'success' => false,
            'data' => $bookings
        ], $this->successStatus);
    }

    public function booking(Request $request, $id)
    {
        $user = Auth::user();
        if(!$user->isPatient()) {
            return response()->json([
                'success' => false,
                'data' => "Anda bukan pasien"
            ], $this->successStatus);
        } 

        $bookings = $user->patient()->first()
                        ->bookings
                        ->find($id);
        $bookings['hospital'] = $bookings->hospital;
        $bookings['booking_patient_information'] = $bookings->booking_patient_information;
        $bookings['patient'] = $bookings->patient;
        $bookings['doctor'] = $bookings->doctor;
        $bookings['doctor']['spesialist'] = $bookings->doctor->spesialist;

        
        return response()->json([
            'success' => false,
            'data' => $bookings
        ], $this->successStatus);
    }

    public function makeBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doctor_id' => 'required',
            'hospital_id' => 'required',
            'patient_id' => 'required',
            'booking_for' => 'required',
            'date' => 'required',
            'message' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error'=> true,
                'message' =>$validator->errors()
            ], 400);            
        }

        $user = Auth::user();

        $input = $request->all();
        $date = new Carbon($input['date']);
        $input['booking_code'] = "B".(now()->year-2000).now()->month.rand(9000, ($date->year + $date->month + $date->day+ $date->minute));
        $input['status'] = 'active';
        $input['patient_id'] = $user->patient->id;

        if(!$user->isPatient()) {
            return response()->json([
                'error'=>true,
                'message' => 'Anda bukan pasien!'
            ], 400);
        }
        $userBookings = $user->patient()
                        ->first()->bookings()->whereYear('date', '=', $date->year)
                        ->whereMonth('date', '=', $date->month)
                        ->whereDay('date', '=', $date->day);

        if($userBookings->get()->count() >= 2) {
            return response()->json([
                'error'=>true,
                'message' => 'Sudah melebihi batas booking harian'
            ], 400);

        } else if($userBookings->first() != null && $userBookings->first()->isActive() &&  $userBookings->first()->doctor_id == $input['doctor_id']) {
            return response()->json([
                'error'=>true,
                'message' => 'Sudah membooking dokter ini hari ini!'
            ], 400);
        } else if($userBookings->first() != null && $userBookings->first()->isActive() 
            && ($date->diffInMinutes(new Carbon($userBookings->first()->date)) / 60) < 3) {
                return response()->json([
                    'error'=>true,
                    'message' => 'Ada booking yang masih aktif!'
                ], 400);
        }

        $input['date'] = $date;
        $input['is_active'] = 1;
        $booking = Booking::create($input);
        $booking->booking_patient_information()->create([
            'booking_id' => $booking->id,
            'name' => $request->get('info_patient_name'), 
            'status' => $request->get('info_patient_status'),
            'sex' => $request->get('info_patient_sex')
        ]);

        return response()->json([
            'success'=>true,
            'data'=> Booking::find($booking->id)
        ], $this->successStatus);
    }
}

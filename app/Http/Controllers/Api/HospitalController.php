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
        $dept = Department::find($id);
        $hospitals = $dept->hospitals()->with(['schedules.doctors', 'hospital_type'])->get();
        $hospitals[0]['bpjs'] = [
            "title" => "BPJS",
            'weekday' => "Senin-Jumat: 07.00 - 14.00, 16.00 - 19.00",
            'weekend' => "Sabtu: 07.00 - 12.00"
        ];
        $hospitals[0]['open_hour'] = [
            'weekday' => "Senin-Jumat: 08.00 - 20.00",
            'weekend' => "Sabtu: 08.00 - 17.00"
        ];
        if(count($hospitals) > 1) {
            $hospitals[1]['open_hour'] = [
                'weekday' => "Senin-Jumat: 08.00 - 20.00",
                'weekend' => "Sabtu: 08.00 - 13.00"
            ];
        }
        $data = [
            'about' => $dept,
            'layanan_darurat' => [
                [
                    'title' => 'Unit Gawat Darurat',
                    'desc' => 'Setiap Hari 24 Jam',
                ],
                [
                    'title' => 'Ambulan Siaga',
                    'desc' => '+12123312',
                ]
            ],
            'hospitals' => $hospitals
        ];
        
        return response()->json(
            [
                'success' => true,
                'data'=> $data
            ], $this->successStatus
        );
    }
    
    public function detail(Request $request, $dep_id, $hospital_id)
    {   
        $hospital = Department::find($dep_id)->hospitals->find($hospital_id);
        // dd($hospital);
        return response()->json(
            [
                'success' => true,
                'data'=> $hospital->with(['schedules.doctors', 'contents', 'hospital_type'])->first()
            ], $this->successStatus
        );
    }
}

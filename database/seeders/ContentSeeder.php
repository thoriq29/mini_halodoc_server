<?php

namespace Database\Seeders;

use Carbon\Carbon;

use Illuminate\Database\Seeder;

use App\Models\Content;
use App\Models\ContentCategory;
use App\Models\Hospital;

use App\Models\Department;
use App\Models\HospitalType;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $type = HospitalType::create([
            'slug'=> 'hospital',
            'name'=> 'Hospital'
        ]);

        $dept = Department::create([
            'name' => "SMKDev",
            "desc" => "Department SMKDev",
            "tagline" => "SMK Bisa!",
            "motto" => "Hidup Mandiri, Jangan jadi beban keluarga!",
            "date_of_establishment" => new Carbon('2000-10-10 09:00:00')
        ]);

        $hospital = Hospital::create([
            'name' => 'SMKDev Hospital',
            'email' => "info@smk.dev",
            'phone' => "+622700000",
            'address' => "Jl Margacinta NO 29",
            'lat' => -6.9552316,
            'lng' => 107.6480657,
            'department_id' => $dept->id,
            'hospital_type_id' => $type->id
        ]);

        $categories = [
            'Event',
            'Promo',
            'Partner',
            'Layanan',
            'Fasilitas',
            'Berita'
        ];

        $contents = [
            [
                "title" => 'Donor Darah Yuk!',
                "desc" => "Hayu kita donor darah.",
                "image_url" => "https://res.cloudinary.com/dk0z4ums3/image/upload/v1542197706/attached_image/berbagai-manfaat-donor-darah-untuk-kesehatan.jpg",
                "hospital_id" => $hospital->id,
            ],
            [
                "title" => 'Berobat disc 20%',
                "desc" => "Diskon 20% untuk biaya MCU*",
                "image_url" => "https://rsmitraplumbon.com/wp-content/uploads/2018/04/Promo-2-MCU.jpg",
                "hospital_id" => $hospital->id,
            ],
            [
                "title" => 'LinkAja!',
                "desc" => "Partner Pembayaran Syariah dengan LinkAja!",
                "image_url" => "https://upload.wikimedia.org/wikipedia/commons/thumb/8/85/LinkAja.svg/1200px-LinkAja.svg.png",
                "hospital_id" => $hospital->id,
            ],
            [
                "title" => 'Periksa Gigi',
                "desc" => "Layanan periksa gigi gratis*",
                "image_url" => "https://cdn2.tstatic.net/jakarta/foto/bank/images/ruangan-pemeriksaan-poli-forensik-dan-medikolegal-di-rsud-kota-bekasi.jpg",
                "hospital_id" => $hospital->id,
            ],
            [
                "title" => 'Ruang Pemeriksaan',
                "desc" => "Nikmati ruang pemeriksaan yang nyaman saat anda berobat!",
                "image_url" => "https://cdn2.tstatic.net/jakarta/foto/bank/images/ruangan-pemeriksaan-poli-forensik-dan-medikolegal-di-rsud-kota-bekasi.jpg",
                "hospital_id" => $hospital->id,
            ],
            [
                "title" => 'Kunjungan Bupati SMKDev',
                "desc" => "Kunjungan Bupati SMKDev th 2020!",
                "image_url" => "https://karawangkab.go.id/sites/default/files/berita/1_4.jpg",
                "hospital_id" => $hospital->id,
            ],
        ];

        foreach ($categories as $key => $category) {
            $data = $contents[$key];
            $cat = ContentCategory::create([
                'name' => $category,
                'slug' => strtolower(str_replace(' ', '-', $category))
            ]);
            $this->saveContent(
                $data['title'], 
                $data['desc'],
                $data['image_url'],
                $data['hospital_id'], 
                $cat->id
            );
            ++$key;
        }
    }

    public function saveContent($title, $desc, $image_url, $hospital_id, $category_id)
    {
        Content::create([
            'title'=> $title,
            'desc' => $desc,
            'image_url' => $image_url,
            'date' => date('Y-m-d H:i:s'),
            'hospital_id' => 1,
            'content_category_id' => $category_id
        ]);
    }
}

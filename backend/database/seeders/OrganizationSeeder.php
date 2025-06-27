<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organization;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organizations = [
            [
                'name' => 'Dinas Komunikasi dan Informatika',
                'description' => 'Dinas yang mengelola komunikasi dan informatika Kabupaten Gorontalo',
                'type' => 'Dinas',
                'website' => 'https://diskominfo.gorontalokab.go.id',
                'email' => 'diskominfo@gorontalokab.go.id',
                'phone' => '(0435) 881234',
                'address' => 'Jl. 23 Januari No. 43, Limboto, Gorontalo',
                'is_active' => true,
            ],
            [
                'name' => 'Badan Perencanaan Pembangunan Daerah',
                'description' => 'Badan yang mengelola perencanaan pembangunan daerah',
                'type' => 'Badan',
                'website' => 'https://bappeda.gorontalokab.go.id',
                'email' => 'bappeda@gorontalokab.go.id',
                'phone' => '(0435) 881235',
                'address' => 'Jl. 23 Januari No. 45, Limboto, Gorontalo',
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Kesehatan',
                'description' => 'Dinas yang mengelola kesehatan masyarakat',
                'type' => 'Dinas',
                'website' => 'https://dinkes.gorontalokab.go.id',
                'email' => 'dinkes@gorontalokab.go.id',
                'phone' => '(0435) 881236',
                'address' => 'Jl. 23 Januari No. 47, Limboto, Gorontalo',
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Pendidikan',
                'description' => 'Dinas yang mengelola pendidikan di Kabupaten Gorontalo',
                'type' => 'Dinas',
                'website' => 'https://disdik.gorontalokab.go.id',
                'email' => 'disdik@gorontalokab.go.id',
                'phone' => '(0435) 881237',
                'address' => 'Jl. 23 Januari No. 49, Limboto, Gorontalo',
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Kependudukan dan Pencatatan Sipil',
                'description' => 'Dinas yang mengelola kependudukan dan catatan sipil',
                'type' => 'Dinas',
                'website' => 'https://disdukcapil.gorontalokab.go.id',
                'email' => 'disdukcapil@gorontalokab.go.id',
                'phone' => '(0435) 881238',
                'address' => 'Jl. 23 Januari No. 51, Limboto, Gorontalo',
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'description' => 'Dinas yang mengelola pekerjaan umum dan penataan ruang',
                'type' => 'Dinas',
                'website' => 'https://dpupr.gorontalokab.go.id',
                'email' => 'dpupr@gorontalokab.go.id',
                'phone' => '(0435) 881239',
                'address' => 'Jl. 23 Januari No. 53, Limboto, Gorontalo',
                'is_active' => true,
            ],
        ];

        foreach ($organizations as $organization) {
            Organization::create($organization);
        }
    }
}
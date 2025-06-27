<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dataset;
use App\Models\Resource;
use App\Models\Category;
use App\Models\Organization;
use App\Models\User;
use App\Models\Group;

class DatasetSeeder extends Seeder
{
    public function run(): void
    {
        $kependudukanCategory = Category::where('name', 'Kependudukan')->first();
        $ekonomiCategory = Category::where('name', 'Ekonomi')->first();
        $kesehatanCategory = Category::where('name', 'Kesehatan')->first();
        $pendidikanCategory = Category::where('name', 'Pendidikan')->first();
        $infrastrukturCategory = Category::where('name', 'Infrastruktur')->first();

        $diskominfo = Organization::where('name', 'Dinas Komunikasi dan Informatika')->first();
        $bappeda = Organization::where('name', 'Badan Perencanaan Pembangunan Daerah')->first();
        $dinkes = Organization::where('name', 'Dinas Kesehatan')->first();
        $disdik = Organization::where('name', 'Dinas Pendidikan')->first();
        $dpupr = Organization::where('name', 'Dinas Pekerjaan Umum dan Penataan Ruang')->first();

        $publisher = User::where('email', 'siti.nurhaliza@gorontalokab.go.id')->first();
        $publisherDinkes = User::where('email', 'andi.pratama@gorontalokab.go.id')->first();
        $orgAdmin = User::where('email', 'ahmad.wijaya@gorontalokab.go.id')->first();

        $prioritasGroup = Group::where('name', 'Data Prioritas')->first();
        $statistikGroup = Group::where('name', 'Data Statistik')->first();

        // Dataset 1: Data Penduduk
        $dataset1 = Dataset::create([
            'title' => 'Data Penduduk Kabupaten Gorontalo 2024',
            'description' => 'Dataset lengkap mengenai jumlah penduduk, pertumbuhan demografis, dan distribusi usia di seluruh kecamatan Kabupaten Gorontalo tahun 2024.',
            'notes' => 'Data ini diperbarui setiap bulan berdasarkan laporan dari kecamatan dan kelurahan.',
            'category_id' => $kependudukanCategory->id,
            'organization_id' => $bappeda->id,
            'license' => 'CC-BY-4.0',
            'status' => 'published',
            'visibility' => 'public',
            'featured' => true,
            'tags' => ['penduduk', 'demografi', 'statistik'],
            'metadata' => [
                'source' => 'Dinas Kependudukan dan Pencatatan Sipil',
                'update_frequency' => 'monthly',
                'coverage' => 'Kabupaten Gorontalo',
            ],
            'published_at' => now()->subDays(10),
            'created_by' => $publisher->id,
            'approved_by' => $orgAdmin->id,
            'approved_at' => now()->subDays(9),
        ]);

        Resource::create([
            'dataset_id' => $dataset1->id,
            'name' => 'Data Penduduk CSV',
            'description' => 'Data penduduk dalam format CSV',
            'type' => 'file',
            'format' => 'csv',
            'file_size' => 2516582,
            'mime_type' => 'text/csv',
            'encoding' => 'UTF-8',
            'is_preview_available' => true,
            'created_by' => $publisher->id,
        ]);

        $dataset1->groups()->attach([$prioritasGroup->id, $statistikGroup->id]);

        // Dataset 2: PDRB
        $dataset2 = Dataset::create([
            'title' => 'PDRB dan Indikator Ekonomi 2023',
            'description' => 'Produk Domestik Regional Bruto, tingkat inflasi, dan indikator ekonomi utama Kabupaten Gorontalo tahun 2023.',
            'notes' => 'Data PDRB berdasarkan harga berlaku dan harga konstan tahun 2010.',
            'category_id' => $ekonomiCategory->id,
            'organization_id' => $bappeda->id,
            'license' => 'CC-BY-4.0',
            'status' => 'published',
            'visibility' => 'public',
            'featured' => true,
            'tags' => ['ekonomi', 'pdrb', 'inflasi', 'statistik'],
            'metadata' => [
                'source' => 'BPS Kabupaten Gorontalo',
                'update_frequency' => 'yearly',
                'coverage' => 'Kabupaten Gorontalo',
            ],
            'published_at' => now()->subDays(15),
            'created_by' => $publisher->id,
            'approved_by' => $orgAdmin->id,
            'approved_at' => now()->subDays(14),
        ]);

        Resource::create([
            'dataset_id' => $dataset2->id,
            'name' => 'PDRB Excel',
            'description' => 'Data PDRB dalam format Excel',
            'type' => 'file',
            'format' => 'xlsx',
            'file_size' => 1887437,
            'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'created_by' => $publisher->id,
        ]);

        $dataset2->groups()->attach([$prioritasGroup->id, $statistikGroup->id]);

        // Dataset 3: Fasilitas Kesehatan
        $dataset3 = Dataset::create([
            'title' => 'Fasilitas Kesehatan dan Tenaga Medis',
            'description' => 'Data komprehensif rumah sakit, puskesmas, dokter, dan tenaga kesehatan di Kabupaten Gorontalo.',
            'notes' => 'Termasuk data lokasi, kapasitas, dan jenis layanan yang tersedia.',
            'category_id' => $kesehatanCategory->id,
            'organization_id' => $dinkes->id,
            'license' => 'CC-BY-4.0',
            'status' => 'published',
            'visibility' => 'public',
            'featured' => false,
            'tags' => ['kesehatan', 'rumah-sakit', 'puskesmas', 'dokter'],
            'metadata' => [
                'source' => 'Dinas Kesehatan Kabupaten Gorontalo',
                'update_frequency' => 'quarterly',
                'coverage' => 'Kabupaten Gorontalo',
            ],
            'published_at' => now()->subDays(20),
            'created_by' => $publisherDinkes->id,
            'approved_by' => $orgAdmin->id,
            'approved_at' => now()->subDays(19),
        ]);

        Resource::create([
            'dataset_id' => $dataset3->id,
            'name' => 'Fasilitas Kesehatan CSV',
            'description' => 'Data fasilitas kesehatan dalam format CSV',
            'type' => 'file',
            'format' => 'csv',
            'file_size' => 3251200,
            'mime_type' => 'text/csv',
            'encoding' => 'UTF-8',
            'is_preview_available' => true,
            'created_by' => $publisherDinkes->id,
        ]);

        // Dataset 4: Pendidikan (Review)
        $dataset4 = Dataset::create([
            'title' => 'Statistik Pendidikan Dasar dan Menengah',
            'description' => 'Jumlah sekolah, siswa, guru, dan rasio kelulusan di tingkat SD, SMP, dan SMA sederajat.',
            'notes' => 'Data berdasarkan tahun ajaran 2023/2024.',
            'category_id' => $pendidikanCategory->id,
            'organization_id' => $disdik->id,
            'license' => 'CC-BY-4.0',
            'status' => 'review',
            'visibility' => 'public',
            'featured' => false,
            'tags' => ['pendidikan', 'sekolah', 'siswa', 'guru'],
            'metadata' => [
                'source' => 'Dinas Pendidikan Kabupaten Gorontalo',
                'update_frequency' => 'yearly',
                'coverage' => 'Kabupaten Gorontalo',
            ],
            'created_by' => $publisher->id,
        ]);

        Resource::create([
            'dataset_id' => $dataset4->id,
            'name' => 'Statistik Pendidikan CSV',
            'description' => 'Data statistik pendidikan dalam format CSV',
            'type' => 'file',
            'format' => 'csv',
            'file_size' => 1992294,
            'mime_type' => 'text/csv',
            'encoding' => 'UTF-8',
            'created_by' => $publisher->id,
        ]);

        // Dataset 5: Infrastruktur (Draft)
        $dataset5 = Dataset::create([
            'title' => 'Data Infrastruktur Jalan dan Jembatan',
            'description' => 'Kondisi jalan, jembatan, dan infrastruktur transportasi di Kabupaten Gorontalo.',
            'notes' => 'Data mencakup panjang jalan, kondisi, dan status pemeliharaan.',
            'category_id' => $infrastrukturCategory->id,
            'organization_id' => $dpupr->id,
            'license' => 'CC-BY-4.0',
            'status' => 'draft',
            'visibility' => 'public',
            'featured' => false,
            'tags' => ['infrastruktur', 'jalan', 'jembatan', 'transportasi'],
            'metadata' => [
                'source' => 'Dinas Pekerjaan Umum dan Penataan Ruang',
                'update_frequency' => 'yearly',
                'coverage' => 'Kabupaten Gorontalo',
            ],
            'created_by' => $publisher->id,
        ]);

        Resource::create([
            'dataset_id' => $dataset5->id,
            'name' => 'Infrastruktur Jalan CSV',
            'description' => 'Data infrastruktur jalan dalam format CSV',
            'type' => 'file',
            'format' => 'csv',
            'file_size' => 4194304,
            'mime_type' => 'text/csv',
            'encoding' => 'UTF-8',
            'created_by' => $publisher->id,
        ]);

        // Simulate some downloads and views
        $this->simulateActivity($dataset1, $dataset2, $dataset3);
    }

    private function simulateActivity($dataset1, $dataset2, $dataset3)
    {
        // Simulate downloads for dataset 1
        for ($i = 0; $i < 50; $i++) {
            $dataset1->downloads()->create([
                'ip_address' => '192.168.1.' . rand(1, 254),
                'downloaded_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Simulate views for dataset 1
        for ($i = 0; $i < 200; $i++) {
            $dataset1->views()->create([
                'ip_address' => '192.168.1.' . rand(1, 254),
                'viewed_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Simulate downloads for dataset 2
        for ($i = 0; $i < 30; $i++) {
            $dataset2->downloads()->create([
                'ip_address' => '192.168.1.' . rand(1, 254),
                'downloaded_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Simulate views for dataset 2
        for ($i = 0; $i < 150; $i++) {
            $dataset2->views()->create([
                'ip_address' => '192.168.1.' . rand(1, 254),
                'viewed_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Simulate downloads for dataset 3
        for ($i = 0; $i < 20; $i++) {
            $dataset3->downloads()->create([
                'ip_address' => '192.168.1.' . rand(1, 254),
                'downloaded_at' => now()->subDays(rand(1, 30)),
            ]);
        }

        // Simulate views for dataset 3
        for ($i = 0; $i < 100; $i++) {
            $dataset3->views()->create([
                'ip_address' => '192.168.1.' . rand(1, 254),
                'viewed_at' => now()->subDays(rand(1, 30)),
            ]);
        }
    }
}
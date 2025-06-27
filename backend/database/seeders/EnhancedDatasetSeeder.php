<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dataset;
use App\Models\Resource;
use App\Models\Category;
use App\Models\Organization;
use App\Models\User;
use App\Models\Group;
use App\Models\DatasetDownload;
use App\Models\DatasetView;

class EnhancedDatasetSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creating enhanced datasets with resources...');

        // Get existing data
        $categories = Category::all();
        $organizations = Organization::all();
        $users = User::all();
        $groups = Group::all();

        // Create 50 additional datasets
        for ($i = 0; $i < 50; $i++) {
            $category = $categories->random();
            $organization = $organizations->random();
            $creator = $users->random();

            $dataset = Dataset::create([
                'title' => $this->generateDatasetTitle($category->name),
                'description' => $this->generateDescription($category->name),
                'notes' => $this->faker()->optional(0.7)->paragraph(),
                'category_id' => $category->id,
                'organization_id' => $organization->id,
                'license' => $this->faker()->randomElement(['CC-BY-4.0', 'CC-BY-SA-4.0', 'CC0-1.0']),
                'status' => $this->faker()->randomElement(['published', 'published', 'published', 'review', 'draft']),
                'visibility' => 'public',
                'featured' => $this->faker()->boolean(15), // 15% featured
                'tags' => $this->generateTags($category->name),
                'metadata' => [
                    'source' => $organization->name,
                    'update_frequency' => $this->faker()->randomElement(['daily', 'weekly', 'monthly', 'quarterly', 'yearly']),
                    'coverage' => 'Kabupaten Gorontalo',
                    'quality_score' => $this->faker()->numberBetween(70, 100),
                ],
                'published_at' => $this->faker()->optional(0.8)->dateTimeBetween('-2 years', 'now'),
                'created_by' => $creator->id,
                'updated_by' => $creator->id,
                'approved_by' => $this->faker()->optional(0.8)->randomElement($users->pluck('id')->toArray()),
                'approved_at' => $this->faker()->optional(0.8)->dateTimeBetween('-2 years', 'now'),
            ]);

            // Add to random groups
            if ($groups->isNotEmpty()) {
                $randomGroups = $groups->random($this->faker()->numberBetween(0, 3));
                $dataset->groups()->attach($randomGroups->pluck('id'));
            }

            // Create 1-4 resources per dataset
            $resourceCount = $this->faker()->numberBetween(1, 4);
            for ($j = 0; $j < $resourceCount; $j++) {
                $this->createResource($dataset, $creator);
            }

            // Generate realistic download and view statistics
            if ($dataset->status === 'published') {
                $this->generateStatistics($dataset);
            }
        }

        $this->command->info('Enhanced datasets created successfully!');
    }

    private function generateDatasetTitle(string $categoryName): string
    {
        $titles = [
            'Kependudukan' => [
                'Data Penduduk per Kecamatan',
                'Statistik Kelahiran dan Kematian',
                'Distribusi Usia Penduduk',
                'Data Migrasi Penduduk',
                'Kepadatan Penduduk per Wilayah',
            ],
            'Ekonomi' => [
                'PDRB per Sektor Ekonomi',
                'Tingkat Inflasi Bulanan',
                'Data Ekspor Impor',
                'Investasi Daerah',
                'Pertumbuhan Ekonomi Regional',
            ],
            'Kesehatan' => [
                'Fasilitas Kesehatan per Kecamatan',
                'Data Tenaga Medis',
                'Statistik Penyakit',
                'Program Imunisasi',
                'Angka Kematian Ibu dan Bayi',
            ],
            'Pendidikan' => [
                'Data Sekolah dan Siswa',
                'Tingkat Kelulusan',
                'Fasilitas Pendidikan',
                'Data Guru dan Tenaga Pendidik',
                'Program Beasiswa',
            ],
            'Infrastruktur' => [
                'Kondisi Jalan per Ruas',
                'Data Jembatan',
                'Fasilitas Air Bersih',
                'Infrastruktur Listrik',
                'Transportasi Publik',
            ],
        ];

        $categoryTitles = $titles[$categoryName] ?? ['Data ' . $categoryName];
        $baseTitle = $this->faker()->randomElement($categoryTitles);
        $year = $this->faker()->numberBetween(2020, 2024);
        
        return $baseTitle . ' ' . $year;
    }

    private function generateDescription(string $categoryName): string
    {
        $descriptions = [
            'Kependudukan' => 'Dataset komprehensif mengenai data kependudukan yang mencakup distribusi demografis, pertumbuhan penduduk, dan karakteristik sosial masyarakat.',
            'Ekonomi' => 'Informasi ekonomi regional yang meliputi indikator makro ekonomi, sektor unggulan, dan perkembangan perekonomian daerah.',
            'Kesehatan' => 'Data kesehatan masyarakat termasuk fasilitas pelayanan kesehatan, program kesehatan, dan indikator kesehatan utama.',
            'Pendidikan' => 'Statistik pendidikan yang mencakup data sekolah, siswa, guru, dan capaian pendidikan di berbagai jenjang.',
            'Infrastruktur' => 'Informasi infrastruktur publik meliputi kondisi, ketersediaan, dan kualitas infrastruktur pendukung pembangunan.',
        ];

        return $descriptions[$categoryName] ?? 'Dataset yang berisi informasi penting untuk mendukung transparansi dan pengambilan keputusan berbasis data.';
    }

    private function generateTags(string $categoryName): array
    {
        $tagSets = [
            'Kependudukan' => ['penduduk', 'demografi', 'statistik', 'sensus', 'migrasi'],
            'Ekonomi' => ['ekonomi', 'pdrb', 'inflasi', 'investasi', 'perdagangan'],
            'Kesehatan' => ['kesehatan', 'rumah-sakit', 'puskesmas', 'tenaga-medis', 'imunisasi'],
            'Pendidikan' => ['pendidikan', 'sekolah', 'siswa', 'guru', 'kurikulum'],
            'Infrastruktur' => ['infrastruktur', 'jalan', 'jembatan', 'transportasi', 'utilitas'],
        ];

        $availableTags = $tagSets[$categoryName] ?? ['data', 'informasi', 'publik'];
        $selectedTags = $this->faker()->randomElements($availableTags, $this->faker()->numberBetween(2, 4));
        
        return $selectedTags;
    }

    private function createResource(Dataset $dataset, User $creator): void
    {
        $formats = ['csv', 'json', 'xlsx', 'pdf', 'xml'];
        $types = ['file', 'api', 'link'];
        
        $format = $this->faker()->randomElement($formats);
        $type = $this->faker()->randomElement($types);

        $mimeTypes = [
            'csv' => 'text/csv',
            'json' => 'application/json',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'pdf' => 'application/pdf',
            'xml' => 'application/xml',
        ];

        Resource::create([
            'dataset_id' => $dataset->id,
            'name' => $dataset->title . ' - ' . strtoupper($format),
            'description' => 'Data dalam format ' . strtoupper($format),
            'type' => $type,
            'format' => $format,
            'url' => $type === 'link' ? 'https://example.com/data/' . $dataset->id : null,
            'file_path' => $type === 'file' ? "datasets/{$dataset->id}/data_{$format}_{$dataset->id}.{$format}" : null,
            'file_size' => $type === 'file' ? $this->faker()->numberBetween(1024, 10485760) : null, // 1KB - 10MB
            'mime_type' => $type === 'file' ? $mimeTypes[$format] : null,
            'encoding' => 'UTF-8',
            'is_preview_available' => in_array($format, ['csv', 'json']),
            'created_by' => $creator->id,
            'updated_by' => $creator->id,
        ]);
    }

    private function generateStatistics(Dataset $dataset): void
    {
        // Generate downloads (0-500 downloads)
        $downloadCount = $this->faker()->numberBetween(0, 500);
        for ($i = 0; $i < $downloadCount; $i++) {
            DatasetDownload::create([
                'dataset_id' => $dataset->id,
                'resource_id' => $dataset->resources->random()->id ?? null,
                'user_id' => $this->faker()->optional(0.3)->randomElement(User::pluck('id')->toArray()),
                'ip_address' => $this->faker()->ipv4(),
                'user_agent' => $this->faker()->userAgent(),
                'downloaded_at' => $this->faker()->dateTimeBetween($dataset->published_at, 'now'),
            ]);
        }

        // Generate views (downloads * 3-8)
        $viewCount = $downloadCount * $this->faker()->numberBetween(3, 8);
        for ($i = 0; $i < $viewCount; $i++) {
            DatasetView::create([
                'dataset_id' => $dataset->id,
                'user_id' => $this->faker()->optional(0.2)->randomElement(User::pluck('id')->toArray()),
                'ip_address' => $this->faker()->ipv4(),
                'user_agent' => $this->faker()->userAgent(),
                'viewed_at' => $this->faker()->dateTimeBetween($dataset->published_at, 'now'),
            ]);
        }
    }

    private function faker(): \Faker\Generator
    {
        return \Faker\Factory::create('id_ID');
    }
}
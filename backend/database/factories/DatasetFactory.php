<?php

namespace Database\Factories;

use App\Models\Dataset;
use App\Models\Category;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatasetFactory extends Factory
{
    protected $model = Dataset::class;

    public function definition(): array
    {
        $statuses = ['draft', 'review', 'published', 'archived'];
        $licenses = ['CC-BY-4.0', 'CC-BY-SA-4.0', 'CC0-1.0', 'ODbL-1.0'];
        
        $tags = [
            ['penduduk', 'demografi', 'statistik'],
            ['ekonomi', 'pdrb', 'inflasi'],
            ['kesehatan', 'rumah-sakit', 'puskesmas'],
            ['pendidikan', 'sekolah', 'siswa'],
            ['infrastruktur', 'jalan', 'transportasi'],
            ['lingkungan', 'air', 'udara'],
            ['ketenagakerjaan', 'tenaga-kerja'],
            ['keamanan', 'ketertiban'],
        ];

        return [
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(3),
            'notes' => $this->faker->optional()->paragraph(),
            'category_id' => Category::factory(),
            'organization_id' => Organization::factory(),
            'license' => $this->faker->randomElement($licenses),
            'status' => $this->faker->randomElement($statuses),
            'visibility' => 'public',
            'featured' => $this->faker->boolean(20), // 20% chance of being featured
            'tags' => $this->faker->randomElement($tags),
            'metadata' => [
                'source' => $this->faker->company(),
                'update_frequency' => $this->faker->randomElement(['daily', 'weekly', 'monthly', 'yearly']),
                'coverage' => 'Kabupaten Gorontalo',
            ],
            'published_at' => $this->faker->optional(0.7)->dateTimeBetween('-1 year', 'now'),
            'created_by' => User::factory(),
            'updated_by' => function (array $attributes) {
                return $attributes['created_by'];
            },
        ];
    }

    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'featured' => true,
            'status' => 'published',
            'published_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ]);
    }
}
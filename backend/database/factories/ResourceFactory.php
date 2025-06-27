<?php

namespace Database\Factories;

use App\Models\Resource;
use App\Models\Dataset;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition(): array
    {
        $types = ['file', 'api', 'link'];
        $formats = ['csv', 'json', 'xlsx', 'pdf', 'xml', 'geojson'];
        $mimeTypes = [
            'csv' => 'text/csv',
            'json' => 'application/json',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'pdf' => 'application/pdf',
            'xml' => 'application/xml',
            'geojson' => 'application/geo+json',
        ];

        $format = $this->faker->randomElement($formats);
        $type = $this->faker->randomElement($types);

        return [
            'dataset_id' => Dataset::factory(),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->optional()->sentence(),
            'type' => $type,
            'format' => $format,
            'url' => $type === 'link' ? $this->faker->url() : null,
            'file_path' => $type === 'file' ? "datasets/{$this->faker->uuid()}.{$format}" : null,
            'file_size' => $type === 'file' ? $this->faker->numberBetween(1024, 52428800) : null,
            'mime_type' => $type === 'file' ? $mimeTypes[$format] : null,
            'encoding' => 'UTF-8',
            'schema' => $this->faker->optional()->randomElement([
                ['columns' => ['id', 'name', 'value']],
                ['fields' => ['timestamp', 'data', 'status']],
            ]),
            'metadata' => [
                'created_with' => $this->faker->randomElement(['Excel', 'CSV Export', 'API Export']),
                'last_modified' => $this->faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
            ],
            'is_preview_available' => $format === 'csv' || $format === 'json',
            'created_by' => User::factory(),
            'updated_by' => function (array $attributes) {
                return $attributes['created_by'];
            },
        ];
    }

    public function csvFile(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'file',
            'format' => 'csv',
            'mime_type' => 'text/csv',
            'is_preview_available' => true,
        ]);
    }

    public function jsonFile(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'file',
            'format' => 'json',
            'mime_type' => 'application/json',
            'is_preview_available' => true,
        ]);
    }

    public function apiResource(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'api',
            'url' => 'https://api.example.com/data',
            'file_path' => null,
            'file_size' => null,
            'mime_type' => null,
        ]);
    }
}
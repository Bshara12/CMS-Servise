<?php

namespace Database\Factories;

use App\Models\DataType;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
<<<<<<< HEAD

class DataTypeFactory extends Factory
{
  protected $model = DataType::class;

  public function definition()
  {
    return [
      'project_id' => Project::factory(),
      'name' => $this->faker->word(),
      'slug' => $this->faker->slug(),
      'description' => $this->faker->sentence(),
      'is_active' => true,
      'settings' => [],
    ];
  }
}
=======
use Illuminate\Support\Str;

class DataTypeFactory extends Factory
{
    protected $model = DataType::class;

    public function definition()
    {
        return [
            'project_id' => Project::factory(),   // REQUIRED (FK)
            'name' => $this->faker->words(2, true),
            'slug' => $this->faker->unique()->slug(), // UNIQUE per project
            'description' => $this->faker->sentence(),
            'is_active' => true,
            'settings' => null,
        ];
    }
}
>>>>>>> f4a86fb9649ba8d167b864add396550e197cb9e1

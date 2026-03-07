<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
<<<<<<< HEAD
  protected $model = Project::class;

  public function definition()
  {
    return [
      'public_id' => Str::uuid()->toString(),
      'name' => $this->faker->company(),
      'owner_id' => User::factory(),
    ];
  }
}
=======
    protected $model = Project::class;

    public function definition()
    {
        return [
            'public_id' => Str::uuid()->toString(),
            'name' => $this->faker->word(),
            'owner_id' => User::factory(),   // 👈 الحل الحقيقي
        ];
    }
}
>>>>>>> f4a86fb9649ba8d167b864add396550e197cb9e1

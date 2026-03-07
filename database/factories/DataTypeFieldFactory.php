<?php

namespace Database\Factories;

<<<<<<< HEAD
use App\Models\DataType;
use App\Models\DataTypeField;
=======
use App\Models\DataTypeField;
use App\Models\DataType;
>>>>>>> f4a86fb9649ba8d167b864add396550e197cb9e1
use Illuminate\Database\Eloquent\Factories\Factory;

class DataTypeFieldFactory extends Factory
{
<<<<<<< HEAD
  protected $model = DataTypeField::class;

  public function definition()
  {
    return [
      'data_type_id' => DataType::factory(),
      'name' => $this->faker->unique()->word(),
      'type' => 'text',
      'required' => false,
      'translatable' => false,
      'validation_rules' => ['string'],
      'settings' => [],
      'sort_order' => 1,
    ];
  }
}
=======
    protected $model = DataTypeField::class;

    public function definition()
    {
        return [
            'data_type_id' => DataType::factory(),
            'name' => $this->faker->word(),
            'type' => 'text',
            'required' => false,
            'translatable' => false,
            'validation_rules' => null,
            'settings' => null,
            'sort_order' => 0,
        ];
    }
}
>>>>>>> f4a86fb9649ba8d167b864add396550e197cb9e1

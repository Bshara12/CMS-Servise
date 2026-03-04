<?php

namespace Database\Factories;

use App\Models\DataTypeField;
use App\Models\DataType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataTypeFieldFactory extends Factory
{
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
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataTypeField extends Model
{
  protected $casts = [
        'validation_rules' => 'array',
    ];

    public function dataType() {
        return $this->belongsTo(DataType::class);
    }
}

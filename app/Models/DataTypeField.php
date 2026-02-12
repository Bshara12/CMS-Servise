<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataTypeField extends Model
{

  protected $fillable = [
    'data_type_id',
    'name',
    'type',
    'required',
    'translatable',
    'validation_rules',
    'settings',
    'sort_order',
  ];

  protected $casts = [
    'validation_rules' => 'array',
    'settings' => 'array',
    'required' => 'boolean',
    'translatable' => 'boolean',
    'sort_order' => 'integer',
  ];

  public function dataType()
  {
    return $this->belongsTo(DataType::class);
  }
}

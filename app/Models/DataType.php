<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataType extends Model
{
  use SoftDeletes;

  protected $fillable = [
    'project_id',
    'name',
    'slug',
    'description',
    'is_active',
    'settings'
  ];

  protected $casts = [
    'settings' => 'array',
    'is_active' => 'boolean',
  ];


  public function project()
  {
    return $this->belongsTo(Project::class);
  }

  public function fields()
  {
    return $this->hasMany(DataTypeField::class);
  }

  public function entries()
  {
    return $this->hasMany(DataEntry::class);
  }

  public function relations()
  {
    return $this->hasMany(DataTypeRelation::class);
  }
  public function relatedRelations()
  {
    return $this->hasMany(DataTypeRelation::class, 'related_data_type_id');
  }
}

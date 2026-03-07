<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataEntry extends Model
{
  use SoftDeletes;
  use HasFactory;
  protected $guarded = [];
  public function dataType()
  {
    return $this->belongsTo(DataType::class);
  }

  public function values()
  {
    return $this->hasMany(DataEntryValue::class, 'data_entry_id');
  }

  public function versions()
  {
    return $this->hasMany(DataEntryVersion::class);
  }


  public function relations()
  {
    return $this->hasMany(DataEntryRelation::class);
  }
}

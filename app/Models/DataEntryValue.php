<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataEntryValue extends Model
{
  use SoftDeletes;
  public function entry()
  {
    return $this->belongsTo(DataEntry::class, 'data_entry_id');
  }

  public function field()
  {
    return $this->belongsTo(DataTypeField::class);
  }
}

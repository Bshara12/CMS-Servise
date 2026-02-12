<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataEntryValue extends Model
{
  protected $guarded = [];
  public function entry()
  {
    return $this->belongsTo(DataEntry::class, 'data_entry_id');
  }

  public function field()
  {
    return $this->belongsTo(DataTypeField::class);
  }
}

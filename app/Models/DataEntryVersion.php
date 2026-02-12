<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataEntryVersion extends Model
{
  protected $guarded = [];
  protected $casts = [
    'snapshot' => 'array',
  ];

  public function entry()
  {
    return $this->belongsTo(DataEntry::class);
  }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataEntry extends Model
{
     public function dataType() {
        return $this->belongsTo(DataType::class);
    }

    public function values() {
        return $this->hasMany(DataEntryValue::class);
    }

    public function versions() {
        return $this->hasMany(DataEntryVersion::class);
    }
}

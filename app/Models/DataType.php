<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataType extends Model
{
     public function project() {
        return $this->belongsTo(Project::class);
    }

    public function fields() {
        return $this->hasMany(DataTypeField::class);
    }

    public function entries() {
        return $this->hasMany(DataEntry::class);
    }
}

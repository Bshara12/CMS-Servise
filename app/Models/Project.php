<?php

namespace App\Models;

use App\Traits\BelongsToProject as TraitsBelongsToProject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
  use SoftDeletes;
  use HasFactory;

  protected $fillable = ['name', 'owner_id', 'supported_languages', 'enabled_modules', 'public_id'];


  protected $casts = [
    'supported_languages' => 'array',
    'enabled_modules' => 'array',
  ];

  public function users()
  {
    return $this->belongsToMany(User::class, 'project_user');
  }

  public function collections()
  {
    return $this->hasMany(DataCollection::class);
  }

  use TraitsBelongsToProject; // يضمن أي عملية create تحوي project_id
}

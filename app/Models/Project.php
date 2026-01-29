<?php

namespace App\Models;

use App\Traits\BelongsToProject as TraitsBelongsToProject;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
  protected $fillable = ['name', 'owner_id', 'supported_languages', 'enabled_modules'];

    protected $casts = [
        'supported_languages' => 'array',
        'enabled_modules' => 'array',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    use TraitsBelongsToProject; // يضمن أي عملية create تحوي project_id
}

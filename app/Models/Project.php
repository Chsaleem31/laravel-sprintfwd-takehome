<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [ 'name' ];

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class, 'project_member', 'project_id', 'member_id');
    }
}

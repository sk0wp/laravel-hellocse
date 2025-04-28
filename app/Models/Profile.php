<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    use HasFactory;

    protected $table = "profiles";

    protected $fillable = [
        'firstname',
        'lastname',
        'status',
        'administrator_id',
        'image'
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}

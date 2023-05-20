<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'client',
        'start_date',
        'expected_delivery_date',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('role_id');
    }
}

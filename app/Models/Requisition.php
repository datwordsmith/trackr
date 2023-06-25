<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_id',
        'quantity',
        'activity',
        'status',
    ];

    public function budget()
    {
        return $this->belongsTo(ProjectBudget::class, 'budget_id');
    }
}

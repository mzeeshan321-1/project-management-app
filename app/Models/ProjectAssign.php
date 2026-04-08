<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectAssign extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanent_id',
        'project_id',
        'expert_id',
        'note',
        'budget',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function expert()
    {
        return $this->belongsTo(Expert::class);
    }

    public function tanent()
    {
        return $this->belongsTo(Tanent::class);
    }
    
}

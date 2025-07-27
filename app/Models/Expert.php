<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expert extends Model
{

    protected $fillable = [
        'user_id',
        'tanent_id',
        'specialization',
        'skills',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tanent()
    {
        return $this->belongsTo(Tanent::class);
    }

     public function projectAssigns()
    {
        return $this->hasMany(ProjectAssign::class);
    }
    
}

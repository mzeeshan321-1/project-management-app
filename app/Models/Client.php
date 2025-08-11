<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'user_id',
        'tanent_id',
        'industry',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tanent()
    {
        return $this->belongsTo(Tanent::class);
    }

     public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

}

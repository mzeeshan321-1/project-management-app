<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function experts()
    {
        return $this->hasMany(Expert::class, 'tanent_id');
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'tanent_id');
    }


    public function projects()
    {
        return $this->hasMany(Project::class, 'tanent_id');
    }
    
    public function payments()
    {
        return $this->hasMany(Payment::class, 'tanent_id');
    }

}

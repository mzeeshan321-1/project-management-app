<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'tanent_id',
        'client_id',
        'title',
        'description',
        'start_date',
        'deadline',
        'budget',
        'status',
    ];

    public function tanent()
    {
        return $this->belongsTo(Tanent::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function projectAssigns()
    {
        return $this->hasMany(ProjectAssign::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

}

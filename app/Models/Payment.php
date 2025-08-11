<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'reciever_id',
        'project_id',
        'sender_id',
        'type',
        'amount',
        'note',
        'upload_invoice',
        'status',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'reciever_id');
    }

    public function tanent()
    {
        return $this->belongsTo(Tanent::class);
    }
    
}

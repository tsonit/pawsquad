<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'contacts';
    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'status',
        'service_id',
        'scheduled_at',
        'note'
    ];
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}

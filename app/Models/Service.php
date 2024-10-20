<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'services'; 
    protected $fillable = [
        'name',
        'slug',
        'image',
        'short_description',
        'html_content',
        'status',
        'meta_title',
        'meta_description',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    public $table = "sliders";
    public $fillable = [
        'image',
        'category',
        'title',
        'description',
        'price',
        'button_text',
        'status',
        'button_link_text',
        'order'
    ];
    public function danhmuc()
    {
        return $this->belongsTo(Category::class, 'category', 'id');
    }
}

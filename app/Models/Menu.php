<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;
    public $table = "menus";
    public $fillable = [
        'text',
        'url',
        'parent_id',
        'order',
        'depth',
    ];
    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('order');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $table = 'options';

    protected $fillable = ['name', 'value'];

    public static function updateOrCreate(array $attributes, array $values = [])
    {
        return static::query()->updateOrInsert($attributes, $values);
    }
}

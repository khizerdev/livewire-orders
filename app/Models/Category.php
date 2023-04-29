<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'is_active','position'];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->position = $model->id;
            $model->save();
        });
    }
}

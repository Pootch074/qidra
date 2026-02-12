<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Window extends Model
{
    protected $fillable = ['category_id', 'window_number'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($window) {
    //         if ($window->window_number == 1) {
    //             $exists = Window::where('step_id', $window->step_id)
    //                 ->where('window_number', 1)
    //                 ->exists();

    //             if ($exists) {
    //                 throw new \Exception('Window number 1 already exists for this step.');
    //             }
    //         }
    //     });
    // }

    // public static function existsForStepAndSection($stepId, $sectionId, $windowNumber)
    // {
    //     return self::where('step_id', $stepId)
    //         ->where('section_id', $sectionId)
    //         ->where('window_number', $windowNumber)
    //         ->exists();
    // }
}

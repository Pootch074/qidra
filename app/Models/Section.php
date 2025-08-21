<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $table = 'sections';

    public function division()
    {
        return $this->belongsTo(Division::class);
    }


    public function users()
    {
        return $this->hasMany(User::class, 'section_id');
    }
}

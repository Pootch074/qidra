<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'divisions';
    public function dswd()
    {
        return $this->belongsTo(Dswd::class);
    }


    public function sections()
    {
        return $this->hasMany(Section::class, 'division_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'division_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dswd extends Model
{
    protected $table = 'dswd';
    public function divisions()
    {
        return $this->hasMany(Division::class, 'dswd_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Window extends Model
{
    public function step()
    {
        return $this->belongsTo(Step::class);
    }
}

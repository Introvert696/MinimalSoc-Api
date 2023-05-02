<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribe_to_group extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function group()
    {
        return $this->belongsTo(Group::class, "group_id");
    }
}

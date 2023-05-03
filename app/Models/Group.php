<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $guarded = false;
    public function posts()
    {
        return $this->hasMany(Group_post::class, "creater", "id");
    }
}

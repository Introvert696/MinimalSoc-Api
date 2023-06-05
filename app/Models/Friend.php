<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Friend extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = false;
    public function firstuser()
    {
        return $this->belongsTo(User::class, "first_user", "id");
    }
    public function seconduser()
    {
        return $this->belongsTo(User::class, "second_user", 'id');
    }
}

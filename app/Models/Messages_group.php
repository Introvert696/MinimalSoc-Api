<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use PHPUnit\Framework\MockObject\Stub\ReturnReference;

class Messages_group extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = false;

    public function messages()
    {
        return $this->hasMany(Message::class, 'message_group', 'id');
    }
    public function firstuser()
    {
        return $this->belongsTo(User::class, "first_user");
    }
    public function seconduser()
    {
        return $this->belongsTo(User::class, "second_user");
    }
}

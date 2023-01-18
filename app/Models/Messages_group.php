<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages_group extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function messages()
    {
        return $this->hasMany(Message::class, 'message_group', 'id');
    }
}

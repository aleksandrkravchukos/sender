<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{

    protected $table = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function messageTime()
    {
        return $this->HasMany(MessageTime::class, 'message_id');
    }
}

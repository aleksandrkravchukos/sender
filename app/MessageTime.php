<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class MessageTime extends Model
{

    protected $connection = 'mysql';

    protected $table = 'message_time';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message_id',
        'start_time',
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

    ];


    public function message()
    {
        return $this->belongsTo('App\Message');
    }

    public function messageTimeInTimeZone()
    {
        return $this->HasMany(MessageTimeInTimeZone::class, 'message_time_id');
    }
}

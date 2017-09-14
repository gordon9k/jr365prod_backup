<?php

namespace jobready365;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class package_key extends Model
{
    use Notifiable;
    protected $casts = [
    		'key_code' => 'string'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key_code','generate_time', 'generate_by','expire_time'
    ];
}

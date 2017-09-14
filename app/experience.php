<?php

namespace jobready365;

use Illuminate\Database\Eloquent\Model;

class experience extends Model
{
    protected $casts = [
    		'id' => 'string'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','user_id','organization', 'rank', 'start_date','end_date'
    ];
}

<?php

namespace jobready365;

use Illuminate\Database\Eloquent\Model;

class application extends Model
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
        'id','applicant_id','job_id', 'date_apply'
    ];
}

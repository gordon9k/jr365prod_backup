<?php

namespace jobready365;

use Illuminate\Database\Eloquent\Model;

class candidate extends Model
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
        'id','employer_id','applicant_id','contact_info', 'description'
    ];
}

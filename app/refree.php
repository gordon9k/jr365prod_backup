<?php

namespace jobready365;

use Illuminate\Database\Eloquent\Model;

class refree extends Model
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
			'id','applicant_id','first_name', 'last_name', 'organization','rank','mobile_no','email'
	];
}

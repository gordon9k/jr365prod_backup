<?php

namespace jobready365;

use Illuminate\Database\Eloquent\Model;

class applicant extends Model
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
			'id','user_id','name', 'father_name', 'marital_status','gender','date_of_birth', 'nrc','religion','photo','mobile_no','email', 'address','township_id','postal_code','nationality','city_id','country_id','cv_views','driving_license','job_category_id','job_category'
    ];
			
			
}

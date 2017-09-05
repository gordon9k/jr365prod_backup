<?php

namespace jobready365;

use Illuminate\Database\Eloquent\Model;

class employer extends Model
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
			'id','user_id','name', 'mobile_no','email', 'address','township','postal_code','city_id','country_id','job_category_id','job_category','expired_date'
	];
	
}

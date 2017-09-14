<?php

namespace jobready365;

use Illuminate\Database\Eloquent\Model;

class company extends Model
{
	protected $casts = [
			'id' => 'string'
	];
	
	protected $fillable = [
			'id','user_id','company_name','company_logo','address','township_id','postal_code','city_id','country_id','mobile_no','email', 'website','description'
	];
	
}

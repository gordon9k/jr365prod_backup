<?php

namespace jobready365;

use Illuminate\Database\Eloquent\Model;

class job extends Model
{
	protected $casts = [
			'id' => 'string'
	];
	
	protected $fillable = [
			'id','employer_id','job_category_id', 'job_nature_id','job_title', 'company_id', 'min_salary','max_salary','summary','description','requirement','township_id','city_id','country_id','open_date','close_date','contact_info','graduate','accomodation','male','female','unisex','min_age','max_age','single','food_supply','ferry_supply','language_skill','training','is_active'
	];
	
}

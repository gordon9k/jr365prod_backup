<?php

namespace jobready365;

use Illuminate\Database\Eloquent\Model;

class education extends Model
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
			'id','applicant_id','university', 'degree', 'start_date', 'end_date'
	];
}

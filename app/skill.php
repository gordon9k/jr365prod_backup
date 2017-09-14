<?php

namespace jobready365;

use Illuminate\Database\Eloquent\Model;

class skill extends Model
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
			'id','user_id','type', 'level'
	];
}

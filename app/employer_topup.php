<?php

namespace jobready365;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class employer_topup extends Model
{
	use Notifiable;
	protected $casts = [
			'id' => 'bigint'
	];
	
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
			'key_code','user_id', 'topup_date','expire_date','key_type'
	];
}

<?php

namespace jobready365;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class applicant_topup extends Model
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
		'active_state','user_id', 'topup_date','expire_date'
	];
}

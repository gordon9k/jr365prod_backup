<?php

namespace jobready365;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    protected $casts = [
    		'id' => 'string'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','login_name', 'email','telephone_no' ,'password','is_admin','is_active','user_type','activation_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function confirmation($request)
    {
        $telephone_no = $request->input('telephone_no');
        $activation_code = $request->input('activation_code');
        $id = $request->input('id');

        return static::where('id', $id)
            ->where('telephone_no', $telephone_no)
            ->where('activation_code', $activation_code)
            ->first();
    }

    public function isActive()
    {
        return $this->is_active;
    }
}

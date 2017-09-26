<?php
namespace jobready365;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class TmpUsers extends Authenticatable
{
    // // use Notifiable;
    // // protected $casts = [
    // // 		'id' => 'string'
    // // ];
    
    // /**
    //  * The attributes that are mass assignable.
    //  *
    //  * @var array
    //  */
    // protected $fillable = [
    //     'id','telephone_no' ,'password','user_role','is_active','user_type','activation_code'
    // ];

    // /**
    //  * The attributes that should be hidden for arrays.
    //  *
    //  * @var array
    //  */
    // protected $hidden = [
    //     'password', 'remember_token',
    // ];

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
        'id','telephone_no' ,'password','user_role','is_active','user_type','activation_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
}

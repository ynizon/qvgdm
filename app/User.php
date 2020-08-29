<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	
	public function quizz($paginate = -1, $name = ""){
		if ($paginate == -1){
			if ($name == ""){
				$r = $this->hasMany('App\Quizz');
			}else{
				$r = $this->hasMany('App\Quizz')->where("nom","like","%".$name."%");
			}
		}else{
			if ($name == ""){
				$r = $this->hasMany('App\Quizz')->paginate($paginate);
			}else{
				$r = $this->hasMany('App\Quizz')->where("nom","like","%".$name."%")->paginate($paginate);
			}
		}
		
		return $r;
	}
}

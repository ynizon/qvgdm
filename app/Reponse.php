<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;

class Reponse extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'reponses';
	public $timestamps = false;
	public $incrementing = false;
	
	public function question()
    {
        return $this->belongsToOne('App\Quizz',"quizzs");
    }	
}

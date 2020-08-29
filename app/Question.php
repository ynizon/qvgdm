<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Mail;
use App\Reponse;

class Question extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'questions';
	public $timestamps = false;
	public $incrementing = false;
	
	public function quizz()
    {
        return $this->belongsToOne('App\Quizz',"quizzs");
    }
	
	public function reponses()
    {
        return $this->hasMany('App\Reponse',"question_id")->get()->sortBy("num");
    }
	
	public function createReponses(){
		for ($k=1; $k<=4; $k++){
			$reponse = new Reponse();
			$reponse->id = uniqid();
			$reponse->vote = 20;
			if ($k==1){$reponse->valide = 1;$reponse->vote = 40;}
			
			$reponse->question_id = $this->id;
			$reponse->num = $k;
			$reponse->save();
		}
	}
}

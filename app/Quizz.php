<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Question;
use App\Reponse;
use App\Quizz;
use DB;
use Mail;

class Quizz extends Model 
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quizzs';
	public $timestamps = true;
	public $incrementing = false;
	
	public function users()
    {
        return Quizz::belongsToMany('App\User',"users");
    }
	
	public function questions()
    {
        return Quizz::hasMany('App\Question',"quizz_id")->orderBy("num")->get();
    }
	
	//Export DB Vers fichiers
	public static function export(){
		set_time_limit(0);
		$tables = ["quizzs","questions","reponses"];
		foreach ($tables as $table){			
			$dir= base_path()."/database/seeds/import/".$table;
			if (!is_dir($dir)){
				mkdir($dir);
			}
			$cat = 1;
			$debut = 0;
			$fin = 10;
			$first = true;
			while ($first or count($quizz) >0){
				$first = false;
				$quizz = DB::select("select * from ".$table ." LIMIT ".$debut.", ".$fin);
				foreach ($quizz as $q){				
					if ($debut % 65000 == 0){
						$cat++;
					}
					$subdir = $dir ."/".$cat;
					if (!is_dir($subdir)){
						mkdir($subdir);
					}
					file_put_contents($subdir."/".$q->id.".dmp",serialize($q));
				}
				$debut = $debut+10;
			}			
		}
	}
	
}

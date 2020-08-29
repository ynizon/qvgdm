<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Quizz;
use App\Reponse;
use App\Question;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	
	
	public function accueil(Request $request){	
		//Quizz::export();		
		$name  = $request->input("name");
		$last_quizzs = Quizz::where("status","=","1")->orderBy("created_at","desc")->take(5)->get();
		if ($name != ""){
			$quizzs = Quizz::where("status","=","1")->where("nom","like","%".$name."%")->orderBy("nb","desc")->paginate(50);
		}else{
			$quizzs = Quizz::where("status","=","1")->orderBy("nb","desc")->paginate(50);
		}
		
		return view('welcome',compact('name','quizzs','last_quizzs'));
	}

	public function contact(){
		return view('page/contact');
	}	
}

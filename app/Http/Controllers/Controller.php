<?php

namespace App\Http\Controllers;

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
	
	
	public function accueil(){	
		//Quizz::export();		
		$quizzs = Quizz::where("status","=","1")->orderBy("created_at","desc")->paginate(50);
		return view('welcome',compact('quizzs'));
	}

	public function contact(){
		return view('page/contact');
	}	
}

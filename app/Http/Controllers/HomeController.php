<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Quizz;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		if (Auth::user()->id == 2){
			Auth::logout();
			return redirect("/");
		}else{
			$quizz = Auth::user()->quizz(50);
			return view('home', compact("quizz"));
		}
    }
	
}

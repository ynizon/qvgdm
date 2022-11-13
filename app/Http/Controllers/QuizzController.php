<?php

namespace App\Http\Controllers;

use Session;
use View;
use Auth;
use DB;
use Storage;
use App\Quizz;
use App\Question;
use App\Reponse;
use Illuminate\Http\Request;
use App\Providers\HelperServiceProvider as Helpers;

class QuizzController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

	public function redirectquizz(Request $request){
		$id_quizz = $request->input("num_quizz");
		$quizz = Quizz::where("id_quizz","=",$id_quizz)->first();
		
		if ($quizz != null){
			return redirect("/quizz/".$quizz->id);
		}
	}
	
	public function redirectadminquizz(Request $request){
		$id_quizz = $request->input("num_quizz");
		$password_quizz = $request->input("password_quizz");
		$quizz = Quizz::where("id_quizz","=",$id_quizz)->first();
		
		if ($quizz != null){
			if ($quizz->pass_quizz == $password_quizz){
				$quizz->user_id = 2;
				$quizz->save();
				Auth::loginUsingId(2, true);
				return redirect("/quizz/".$quizz->id."/edit");
			}			
		}
	}
	//http://qvgdm.test/admin.php?num_quizz=d2bc6196f35765856bc7dbdea27ff792&password_quizz=yoyoquizz
	
	public function create()
	{	
		$this->middleware('auth');
		$quizz = new Quizz();	
        $quizz->langue = 'fr';
		$quizz->id = 0;
		
		$method = "POST";
		return view('quizz/edit',compact('quizz','method'));
	}
	
	public function store(Request $request)
    {
		$this->middleware('auth');
		$quizz = new Quizz();
		if ($quizz->id == ""){
			$quizz->id = uniqid();
		}
		$quizz->id_quizz = $quizz->id;
		$quizz->user_id = Auth::user()->id;
		$quizz->intro = "";
		$quizz->conclusion = "";
		$quizz->save();
		$quizz = $this->save($quizz, $request);
		
		$question = New Question();		
		$question->quizz_id = $quizz->id;
		$question->num = 1;
		$question->id = uniqid();
		$question->pj = "";
		$question->save();
		$question->createReponses();			
		
		return redirect('/quizz/'.$quizz->id.'/edit?questions=1')->withOk("Le quizz " . $quizz->name . " a été enregistré .");
    }
	
	public function show($id, Request $request)
	{
		$quizz = Quizz::find($id);
		$uid = 0;
		if (Auth::user() != null){
			$uid = Auth::user()->id;
		}
		if ($quizz->status == 0 and $quizz->user_id != $uid){
			return view('errors/403',  array());
			exit();
		}
		
		return view('quizz/show',compact('quizz',"request"));
	}
	
	public function show_question($id, $num, Request $request)
	{		
		if ($request->input("nom_joueur") != ""){
			Session::put('nom_joueur', $request->input("nom_joueur"));
		}
		$quizz = Quizz::find($id);
		$collection = $quizz->questions();
		$questions = iterator_to_array ($collection);
		$question = new Question();
		if (isset($questions[$num-1])){
			$question = $questions[$num-1];
		}
		
		if ($quizz->status == 0 and $quizz->user_id != Auth::user()->id){
			return view('errors/403',  array());
			exit();
		}
		
		if ($request->input("mode")=="start"){
			Session::put('reprise_num_question', '0');
			Session::put('score', '0');
			Session::put('pallier', '1');
			Session::put('v50', 'OK');
			Session::put('v50_num_garde', '0');
			Session::put('vote', 'OK');
			Session::put('tel', 'OK');			
		}
		
		if ($num == 1){//Fin du jeu
			if ($request->input("nom_joueur") != ""){
				$quizz->nb = $quizz->nb +1;
				$quizz->save(); 
			}
		}
		
		if ($num == 16){//Fin du jeu
			$quizz->nbgagner = $quizz->nbgagner +1;
			$quizz->save(); 
			$question->num = 16;
		}
		
		if ($request->input("joker") == "tel"){
			Session::put('tel', '');
		}
		
		Session::put('num_question', $question->num);
		
		return view('quizz/question',compact('quizz',"request","question"));
	}
	
	public function valide($id, $num_question, $num_reponse, Request $request)
	{
		$quizz = Quizz::find($id);
		$collection = $quizz->questions();
		$questions = iterator_to_array ($collection);
		$question = $questions[$num_question-1];
		$reponses = iterator_to_array ($question->reponses());
		if ($quizz->status == 0 and $quizz->user_id != Auth::user()->id){
			return view('errors/403',  array());
			exit();
		}
		
		$bValide=false;	
		$r = 0;
		foreach ($reponses as $reponse){
			$r++;
			if ($reponse->valide == 1 and $num_reponse == $r){
				$bValide=true;	
			}
		}
		
		//Augmentation du score
		if ($bValide){
			Session::put('score', session('score')+1);
			if ($num_question==1 or $num_question==5 or $num_question==10 or $num_question==15){
				Session::put('pallier', session('score'));
			}
			Session::put('reprise_num_question', $num_question+1);
			
			//Question suivante
			Session::put('num_question', session('num_question')+1);		
			return redirect("/quizz/".$id."/question/".($num_question+1));
		}else{			
			if ($request->input("joker") != ""){
				//Depense du joker
				Session::put($request->input("joker"),"");
				return redirect("/quizz/".$id."/question/".$num_question."?joker=".$request->input("joker"));
			}else{
				//Perdu
				return redirect("/quizz/".$id."/question/".$num_question."?perdu");
			}			
		}
		
	}

	private function save($quizz, $request)
	{		
		$this->middleware('auth');
		if ($quizz->id != 0 and Helpers::checkPermission($quizz->id) == false){
			return view('errors/403',  array());
			exit();		
		}
		
		$inputs = $request->all();
		$fields = ["nom","intro","conclusion","langue","status","type"] ;
		foreach ($fields as $field){
			$quizz->$field = $inputs[$field];
		}
		
		if (count($quizz->questions())<>15){
			$quizz->status = 0;
		}
		if ($inputs["reset"] == 1){
			$quizz->nb = 0;
			$quizz->nbgagner = 0;
		}
		
		if (empty($quizz->user_id)) {
			$quizz->user_id = Auth::user()->id;
		}
        $quizz->save();
		 
		return $quizz;
	}
	
	public function edit(Request $request, $id)
	{	
		$this->middleware('auth');
		$quizz = Quizz::find($id);
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
		if ($quizz){
			$method = "PUT";
			return view('quizz/edit',compact('quizz','method'));
		}
	}
	
	public function update(Request $request, $id)
	{
		$this->middleware('auth');
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$quizz = Quizz::find($id);
		$quizz = $this->save($quizz, $request);
		return redirect('home')->withOk("Le quizz " . $quizz->name . " a été enregistré .");
	}
	
	public function destroy($id)
	{	
		$this->middleware('auth');
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
          
		Quizz::destroy($id);
		return redirect()->back();
	}	
	
	public function addquestion($id){
		$this->middleware('auth');
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
		
		$quizz = Quizz::find($id);
		$question = New Question();
		
		$num_question = count($quizz->questions());
		if ($num_question <15){
			$question->id = uniqid();
			$question->quizz_id = $id;
			$question->num = ($num_question+1);
			$question->pj = "";
			$question->save();

			$question->createReponses();
		}
		return redirect('quizz/'.$id.'/edit?questions=1');
	}

	public function update_questions($id, Request $request){
		$this->middleware('auth');
		if (Helpers::checkPermission($id) == false){
			return view('errors/403',  array());
			exit();		
		}
		$quizz = Quizz::find($id);
		$collection = $quizz->questions();
		$questions = iterator_to_array ($collection);
		
		$inputs = $request->all();

		for ($q=1; $q<=15; $q++){
			if (isset($questions[$q-1])){
				$question=$questions[$q-1];
				$fields = ["num","libelle","pj","cacher_media","background"];
				foreach ($fields as $field){
					$question->$field = "";
					if (isset($inputs[$field ."_".$q])){
						$question->$field = $inputs[$field."_".$q];
					}
				}
				
				$question->save();
				
				$reponses = iterator_to_array ($question->reponses());
				for ($r = 1; $r<=4; $r++){
					$reponse= $reponses[$r-1];
					$fields = ["valide","libelle","vote"];
					foreach ($fields as $field){
						if ($field == "valide"){
							$reponse->$field = 0;
						}else{
							$reponse->$field = "";
						}
						
						if (isset($inputs[$field ."_".$q."_".$r])){
							$reponse->$field = $inputs[$field."_".$q."_".$r];
						}
					}
					$reponse->save();
				}
			}
		}
		
		if (!isset($questions[14])){
			return redirect('quizz/'.$id.'/addquestion');
		}else{
			return redirect('quizz/'.$id.'/edit?questions=1');
		}
	}
	
	public function createimagewithword(Request $request){
		//Creation d'une image  JPG avec un texte dessus (necessite l'extension PHP GD)
		$num_question= (int) $request->input("num_question");  // le texte a afficher sur l'image 
		$num_quizz=$request->input("num_quizz");
		$perdu=$request->input("perdu");
		$nom_image = $request->input("image_filename");  // le nom de votre image avec l'extension jpeg
		$quizz = Quizz::find($num_quizz);
		$questions = iterator_to_array ($quizz->questions());
		$question = new Question();
		if (isset($questions[$num_question-1])){
			$question = $questions[$num_question-1];
		}
		$reponses = iterator_to_array ($question->reponses());
		$question=utf8_decode($question->libelle);
		$iValideReponse = 0;
		$reponse1 = "";
		$reponse2 = "";
		$reponse3 = "";
		$reponse4 = "";
		if (isset($reponses[0])){
			$reponse1 = $reponses[0]->libelle;
		}
		if (isset($reponses[1])){
			$reponse2 = $reponses[1]->libelle;
		}
		if (isset($reponses[2])){
			$reponse3 = $reponses[2]->libelle;
		}
		if (isset($reponses[3])){
			$reponse4 = $reponses[3]->libelle;
		}
		
		for( $z = 0; $z<=3; $z++){
			if (isset($reponses[$z])){
				if ($reponses[$z]->valide == 1){
					$iValideReponse=$reponses[$z]->num;
				}
			}
		}
		
		//Si c'est perdu
		if ($perdu==1){
			$question="Aie Aie Aie , c'est perdu ! Voulez-vous retenter votre chance (pensez aux jokers) ?";
			$reponse1="Oui, je refais tout";
			$reponse2="Oui, mais je reprends ou   j'ai perdu";
			$reponse3="Non, un autre quiz";
			$reponse4="Je suis décu, j'arrete là. Il est nul ce jeu !";	
		}

		//Si c'est gagné
		if ($num_question==16){			
			$question=$quizz->conclusion;
			$reponse1="Un autre quizz ?";
			$reponse2="";	
			$reponse3="";	
		}

		//On supprime l'extension a l affichage
		$texte = "";
		if ($texte!=''){
			$texte=substr($texte,0,strlen($texte)-strlen($sExt)-1);
			$texteExt=$sExt;	
		}


		srand();
		//Joker=>On supprime 2 reponses
		$v50 = session('v50');
		$v50_num_garde = session('v50_num_garde');
		
		if ($v50!="OK" and $v50!=$num_question){
			Session::put('v50',"");
		}

		if ($request->input("joker") == "v50"){							
			$iReponse1 = rand(1, 4);
			$iReponse2 = rand(1, 4);
			
			while ($iReponse1==$iReponse2 or $iReponse1 == $iValideReponse or $iReponse2 == $iValideReponse){
				$iReponse1 = rand(1, 4);
				$iReponse2 = rand(1, 4);	
			}
			
		
			if ($iReponse1==1 or $iReponse2==1 ){
				$reponse1="";
			}
			if ($iReponse1==2 or $iReponse2==2 ){
				$reponse2="";
			}
			if ($iReponse1==3 or $iReponse2==3 ){
				$reponse3="";
			}
			if ($iReponse1==4 or $iReponse2==4 ){
				$reponse4="";
			}						
		}
		
 
		$question=Helpers::removeSpecialChar((($question)));
		$reponse1=(Helpers::removeSpecialChar(($reponse1)));
		$reponse2=(Helpers::removeSpecialChar(($reponse2)));
		$reponse3=(Helpers::removeSpecialChar(($reponse3)));
		$reponse4=(Helpers::removeSpecialChar(($reponse4)));

		header ("Content-type: image/jpeg");
		
		$image = imagecreatefromjpeg(public_path().$nom_image);
		$couleur = imagecolorallocate($image, 255, 255, 255);
		if ($num_question!=""){
			imagestring($image, 5, 700, 10,$num_question."/15", $couleur);
		}
		if ($question!=""){
			if (strlen($question)<70){
				imagestring($image, 5, 75, 380,$question, $couleur);
			}else{
				imagestring($image, 5, 75, 370,substr($question,0,70), $couleur);
				imagestring($image, 5, 75, 390,substr($question,70,70), $couleur);
			}
		}
		if ($reponse1!=""){	
			if (strlen($reponse1)<27){
				imagestring($image, 5, 110, 450,$reponse1, $couleur);	
			}else{
				imagestring($image, 5, 110, 440,substr($reponse1,0,27), $couleur);
				imagestring($image, 5, 110, 455,substr($reponse1,27,27), $couleur);
			}
		}
		if ($reponse2!=""){	
			if (strlen($reponse2)<27){
				imagestring($image, 5, 460, 450,$reponse2, $couleur);	
			}else{
				imagestring($image, 5, 460, 440,substr($reponse2,0,27), $couleur);
				imagestring($image, 5, 460, 455,substr($reponse2,27,27), $couleur);
			}
		}
		if ($reponse3!=""){
			if (strlen($reponse3)<27){
				imagestring($image, 5, 110, 502,$reponse3, $couleur);	
			}else{
				imagestring($image, 5, 110, 492,substr($reponse3,0,27), $couleur);
				imagestring($image, 5, 110, 507,substr($reponse3,27,27), $couleur);
			}	
		}
		if ($reponse4!=""){	
			if (strlen($reponse4)<27){
				imagestring($image, 5, 460, 502,$reponse4, $couleur);	
			}else{
				imagestring($image, 5, 460, 492,substr($reponse4,0,27), $couleur);
				imagestring($image, 5, 460, 507,substr($reponse4,27,27), $couleur);
			}	
		}

		imagejpeg($image);
	}
	
	// Joker tel
	public function google($id, $num, Request $request){
		$quizz = Quizz::find($id);
		$collection = $quizz->questions();
		$questions = iterator_to_array ($collection);
		$question = new Question();
		if (isset($questions[$num-1])){
			$question = $questions[$num-1];
		}
		
		if ($quizz->status == 0 and $quizz->user_id != Auth::user()->id){
			return view('errors/403',  array());
			exit();
		}		
		
		if ($request->input("joker") == "tel"){
			Session::put('tel', '');
		}
		
		$url ="https://www.google.com/search?site=&hl=". $quizz->langue."&q=". str_replace('"'," ",$question->libelle);
		return redirect($url);
	}
}

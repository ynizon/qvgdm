<?php

use Illuminate\Database\Seeder;

use App\Quizz;
use App\Question;
use App\Reponse;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
		
		//DB::raw(file_get_contents("ynizon.sql"));
		// Insert 1 admin
		DB::table('users')->insert(
			array(
				'email' => 'ynizon@gmail.com',
				'name' => 'Admin',
				'password'=>bcrypt("admin")
			)
		); 
		
		//User temporaire pour recup les admins des precedents quizz
		DB::table('users')->insert(
			array(
				'email' => 'anonymous@gmail.com',
				'name' => 'Admin',
				'password'=>bcrypt("admin")
			)
		); 
		
		if (env('DB_CONNECTION') == "sqlite"){
			if ($this->import() == false){
				$this->createFirstQuizz();
			}
		}else{
			$this->createFirstQuizz();
			
			if (file_exists("database/seeds/import/ynizon.sql")){
				DB::unprepared(file_get_contents("database/seeds/import/ynizon.sql"));
				$this->cleanup();
			}
		}
    }
	
	private function createFirstQuizz(){
		$quizz = new Quizz();
		$quizz_id = uniqid();
		$quizz->id = $quizz_id;
		$quizz->nom = "test";
		$quizz->user_id = 1;
		$quizz->intro = "";
		$quizz->conclusion = "";
		$quizz->save();
		
		for($q = 1; $q<=15; $q++){
			$question = new Question ();
			$question_id = uniqid();
			$question->id = $question_id;
			$question->libelle = "Question ".$q;
			$question->num = $q;
			$question->pj = "";
			$question->quizz_id = $quizz_id;
			$question->save();
			
			for ($r = 1; $r<=4 ; $r++){
				$reponse = new Reponse();
				$reponse_id = uniqid();
				$reponse->id = $reponse_id;
				$reponse->libelle = "Réponse ".$r;
				$reponse->num = $r;
				$reponse->question_id  = $question_id;

				if ($r == 1){
					$reponse->valide = 1;
				}
				$reponse->save();
			}
		}
	}
	
	
	//Import de mySQL vers sqlLite
	private function import(){
		$dirbase= base_path()."/database/seeds/import";
		$import = false;
		
		$dir = "quizzs";
		if (is_dir($dirbase."/".$dir)){
			$import = true;
			$subdirs = scandir($dirbase."/".$dir);
			foreach ($subdirs as $subdir){
				if ($subdir != "." and $subdir != ".."){
					$files = scandir($dirbase."/".$dir."/".$subdir);
					$i=0;
					foreach ($files as $file){
						if ($file != "." and $file != ".."){
							$data = unserialize(file_get_contents($dirbase."/".$dir ."/".$subdir."/".$file));
							$quizz = New Quizz();
							foreach ($data as $k=>$v){
								$quizz->$k=$v;
							}									
							$quizz->save();
						}
						$i++;
						echo "Quizz: ".$i."/".count($files)."\n";
					}
				}
			}
		}

		$dir = "questions";
		if (is_dir($dirbase."/".$dir)){
			$import = true;
			
			$subdirs = scandir($dirbase."/".$dir);
			foreach ($subdirs as $subdir){
				if ($subdir != "." and $subdir  != ".."){
					$files = scandir($dirbase."/".$dir."/".$subdir);
					$i=0;
					foreach ($files as $file){
						if ($file != "." and $file != ".."){
							$data = unserialize(file_get_contents($dirbase."/".$dir ."/".$subdir."/".$file));
							$question = New Question();
							foreach ($data as $k=>$v){
								$question->$k=$v;
							}									
							$question->save();
						}
						$i++;
						echo "Question: ". $i."/".count($files)."\n";
					}
				}
			}
		}
		
		$dir = "reponses";
		if (is_dir($dirbase."/".$dir)){
			$import = true;
			
			$subdirs = scandir($dirbase."/".$dir);
			foreach ($subdirs as $subdir){
				if ($subdir != "." and $subdir  != ".."){
					$files = scandir($dirbase."/".$dir."/".$subdir);
					$i=0;
					foreach ($files as $file){
						if ($file != "." and $file != ".."){
							$data = unserialize(file_get_contents($dirbase."/".$dir ."/".$subdir."/".$file));
							$reponse = New Reponse();
							foreach ($data as $k=>$v){
								$reponse->$k=$v;
							}									
							$reponse->save();
						}
						$i++;
						echo "Reponse: ". $i."/".count($files)."\n";
					}
				}
			}
		}	

		return $import;		
	}
	
	//Nettoyage de la base precedente
	private function cleanup(){
		echo "Import Quizz\n";
		
		DB::delete("delete from qvgdm_question where libelle_question = ''");
		DB::delete("delete from qvgdm_question where id_quizz IN (select id_quizz from (select count(id_quizz) as nb,id_quizz from qvgdm_question group by id_quizz) x where x.nb != 15)");
		DB::delete("delete from qvgdm_quizz where id_quizz NOT IN (select DISTINCT id_quizz from qvgdm_question)");
		DB::delete("delete from qvgdm_reponse where id_quizz NOT IN (select id_quizz from qvgdm_quizz)");
		
		$quizz = DB::select("select * from qvgdm_quizz");
		$nb = count($quizz);
		$i=0;
		foreach ($quizz as $quizz){
			$i++;
			echo $i."/".$nb."\n";
			
			$quizz_id = uniqid();
			$q = new Quizz();
			$q->id = $quizz_id;
			$q->user_id = 1;
			$q->intro = $this->dec($quizz->intro_quizz);
			try {
				$q->conclusion = $this->dec($quizz->conclusion_quizz);
			}catch(Exception $e){
				$q->conclusion = substr($this->dec($quizz->conclusion_quizz),0,300);
			}
			$q->type = $this->dec($quizz->type_quizz);
			$q->nom = $this->dec($quizz->nom_quizz);
			$q->nb = $quizz->nb_quizz;
			$q->id_quizz = $quizz->id_quizz;
			$q->nbgagner = $quizz->nbgagner_quizz;
			$q->nbvotes = 0;
			$q->status = $quizz->publi_quizz;
			$q->langue = $quizz->langue_quizz;
			$q->pass_quizz = $quizz->pass_quizz;
			$date = date("Y-m-d");
			if ($quizz->date_quizz != ""){
				$date = substr($quizz->date_quizz,6,2)."-".substr($quizz->date_quizz,4,2)."-".substr($quizz->date_quizz,0,4);
			}
			
			$q->created_at = $date;
			$q->save();
			
			$nb_reponse = 0;
			$questions = DB::select("select * from qvgdm_question where id_quizz = '".$quizz->id_quizz."'");
			foreach ($questions as $question){
				$qu = new Question();
				$question_id = uniqid();
				$qu->id = $question_id;
				$qu->quizz_id = $quizz_id;
				$qu->num = $question->num_question;
				$qu->libelle = $this->dec($question->libelle_question);
				$qu->pj = $question->pj_question;
				$qu->cacher_media = $question->cacher_media;
				
				$reponses = DB::select("select * from qvgdm_reponse where id_quizz = '".$quizz->id_quizz."' and num_question=".$question->num_question);
				if (count($reponses) == 4){
					$qu->save();
					
					foreach ($reponses as $reponse){
						$nb_reponse++;
						$r = new Reponse();
						$reponse_id = uniqid();
						$r->id = $reponse_id;
						$r->question_id = $question_id ;
						$r->num = $reponse->num_reponse;
						$r->valide = $reponse->valide_reponse;
						$r->vote = $reponse->vote_reponse;
						$r->libelle = $this->dec($reponse->libelle_reponse);
						$r->save();
					}
				}
			}
			
			if ($q->status == 1){
				if (count($q->questions()) != 15){
					$q->status = 0;
					$q->save();
				}
			}
			
			//On supprime tous les quizz non finis
			if ($nb_reponse != (4*15)){
				$q->delete();
			}
		}
		
			//Fix DEMO
			$info = '<iframe width="560" height="315" src="https://www.youtube.com/embed/FGBhQbmPwH8" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
			DB::update("update questions set pj = ? where id = 18", [$info]);
			
			$info = '<iframe width="961" height="721" src="https://www.youtube.com/watch?v=Gm5S43YC2uo&feature=youtu.be&t=84&autoplay=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
			DB::update("update questions set pj = ? where id = 22", [$info]);
			
			$info = "<img width='400' src='https://upload.wikimedia.org/wikipedia/commons/thumb/7/70/Stegosaurus_BW.jpg/290px-Stegosaurus_BW.jpg'/>";
			DB::update("update questions set pj = ? where id = 23", [$info]);
			
			$info = "<img width='400' src='https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Las_Vegas_Paris_By_Night.jpg/1280px-Las_Vegas_Paris_By_Night.jpg'/>";
			DB::update("update questions set pj = ? where id = 27", [$info]);
			
			DB::delete("DROP TABLE qvgdm_question");
			DB::delete("DROP TABLE qvgdm_quizz");
			DB::delete("DROP TABLE qvgdm_reponse");
	}
	
	
	private function dec($s){
		$s = str_replace("\\\\","",utf8_decode($s));
		return ($s);
	}
}

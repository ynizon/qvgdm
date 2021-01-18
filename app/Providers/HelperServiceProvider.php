<?php

namespace App\Providers;

use DB;
use Illuminate\Support\ServiceProvider;
use Request;
use Auth;
use App\Quizz;
abstract class HelperServiceProvider extends ServiceProvider
{
	/* Convertit une date de 2016-01-31 a 01/2016 */
	public static function showMonth($sDate, $bJustDate = false)
	{
		if ($sDate == "" or $sDate == "1970-01-01"){
			return "";
		}else{
			if ($bJustDate){
				return substr($sDate,5,2)."/".substr($sDate,0,4);
			}else{
				return substr($sDate,5,2)."/".substr($sDate,0,4).substr($sDate,10);
			}
		}
	}
	
	/* Convertit une date de 2016-01-31 a 31/01/2016 */
	public static function formatDateFR($sDate, $bJustDate = false)
	{
		if ($sDate == "" or $sDate == "1970-01-01"){
			return "";
		}else{
			if ($bJustDate){
				return substr($sDate,8,2)."/".substr($sDate,5,2)."/".substr($sDate,0,4);
			}else{
				return substr($sDate,8,2)."/".substr($sDate,5,2)."/".substr($sDate,0,4).substr($sDate,10);
			}
		}
	}
	
	/* Convertit une date de 31/01/2016 a 2016-01-31*/
	public static function formatDateSQL($sDate)
	{
		return substr($sDate,6,4)."-".substr($sDate,3,2)."-".substr($sDate,0,2);
	}
	
	/* Renvoie une date pour les calendriers JS avec new Date(2018,12,31) a partir de 31-12-2018 */
	public static function formatDateCalendarJS($sDate){
		$iJour = substr($sDate,0,2);
		$iMois = substr($sDate,3,2);
		$iAnnee = substr($sDate,6,4);
		return "new Date(".$iAnnee.",".$iMois.",".$iJour.")" ;
	}
	
	public static function formatDureeHeureMin($iSecondes)
	{
		$iHeure = 0;
		$iMin = 0;
		$iSec = 0;
		while ($iSecondes>3600){
			$iHeure++;
			$iSecondes  = $iSecondes - 3600;
		}
		while ($iSecondes>60){
			$iMin++;
			$iSecondes  = $iSecondes - 60;
		}
		$iSec = $iSecondes;
		
		$sHeure = $iHeure;
		if (strlen($iHeure)<2){
			$sHeure = "0".$iHeure;
		}
		$sMin = $iMin;
		if (strlen($iMin)<2){
			$sMin = "0".$iMin;
		}
		
		return $sHeure.":".$sMin;
	}
	
	public static function formatDureeHeureMinSec($iSecondes)
	{
		$iHeure = 0;
		$iMin = 0;
		$iSec = 0;
		while ($iSecondes>3600){
			$iHeure++;
			$iSecondes  = $iSecondes - 3600;
		}
		while ($iSecondes>60){
			$iMin++;
			$iSecondes  = $iSecondes - 60;
		}
		$iSec = $iSecondes;
		
		$sHeure = $iHeure;
		if (strlen($iHeure)<2){
			$sHeure = "0".$iHeure;
		}
		$sMin = $iMin;
		if (strlen($iMin)<2){
			$sMin = "0".$iMin;
		}
		$sSec = $iSec;
		if (strlen($iSec)<2){
			$sSec = "0".$iSec;
		}
		return $sHeure.":".$sMin.":".$sSec;
	}
	
	
	/* Renvoie le chiffre avec les bons separateurs */
	public static function showNumber($sNumber, $sCurrency, $iVirgule = 0){	
		$r = number_format($sNumber, $iVirgule, ',', ' ');
		if ($sCurrency != ""){
			$r .= " " .$sCurrency;
		}
		return $r;
	}
	
	 /**
     * Affiche un nombre avec les bons séparateurs (>FR) 10 000.00
     * @param unknown_type $s
     */
    public static function num($number, $bEuro = true, $iDecimale = 2){
    	if ($number == ""){ 
			$number = 0;
		}
		if (round($number,$iDecimale) == 0){
			$number = 0;
		}
		$s = number_format($number, $iDecimale, ',', ' ');
		if ($bEuro){
			$s .= " &euro;";
		}
		return $s;
    }
	
	/**
	 * Renvoie le nom du mois
	 * @param unknown_type $iMois
	 */
	public static function getMois($iMois, $bPrefixe = false) {
		$iMois = (int) $iMois;
		$sMois = "";
		$sPrefix = "de ";
		switch ($iMois){
			case 0:
				$sMois = "Décembre";
				break;
			case 1:
				$sMois = "Janvier";
				break;
			case 2:
				$sMois = "Février";
				break;
			case 3:
				$sMois = "Mars";
				break;
			case 4:
				$sPrefix = "d'";
				$sMois = "Avril";
				break;
			case 5:
				$sMois = "Mai";
				break;
			case 6:
				$sMois = "Juin";
				break;
			case 7:
				$sMois = "Juillet";
				break;
			case 8:
				$sPrefix = "d'";
				$sMois = "Août";
				break;
			case 9:
				$sMois = "Septembre";
				break;
			case 10:
				$sPrefix = "d'";
				$sMois = "Octobre";
				break;
			case 11:
				$sMois = "Novembre";
				break;
			case 12:
				$sMois = "Décembre";
				break;
			case 13:
				$sMois = "Janvier";
				break;
		}	

		if (!$bPrefixe){
			return $sMois;
		}else{
			return $sPrefix . $sMois;
		}
	}
	
	/* Effectue le total d'un champ d'un tableau */
	public static function sum($tab, $field){
		$r = 0;
		foreach ($tab as $t){
			$r = $r + $t[$field];
		}
		return $r;
	}
	
	/* Effectue la moyenne d'un champ d'un tableau */
	public static function average($tab, $field){
		$k=0;
		$r = 0;
		foreach ($tab as $t){
			$r = $r + $t[$field];
			$k++;
		}
		if ($k>0){
			return ($r/$k);
		}else{
			return $r;	
		}
		
	}
	
	/* Remplace les accents */
	public static function remove_accents($str, $charset='utf-8'){
		$str = htmlentities($str, ENT_NOQUOTES, $charset);
		
		$str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
		$str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
		$str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
		
		return $str;
	}
	
	/**
	Obtenir le domaine a partir d'une url
	*/
	public static function getdomain($url) {
		
	    preg_match (
	        "/^(http:\/\/|https:\/\/)?([^\/]+)/i",
	        $url, $matches
	    );
		
		$host = "";
		if (isset($matches[2])){
			$host = $matches[2]; 
		}
	    preg_match (
	        "/[^\/]+\.[^\.\/]+$/", 
	        $host, $matches
	    );
	    
		if (isset($matches[0])){
			return strtolower("{$matches[0]}");
		}else{
			return "";
		}
	} 
	
	/**
	Obtenir le domaine a partir d'une url sans sous domaine
	*/
	public static function getmaindomain($url) {
		
	    preg_match (
	        "/^(http:\/\/|https:\/\/)?([^\/]+)/i",
	        $url, $matches
	    );
		
		$host = "";
		if (isset($matches[2])){
			$host = $matches[2]; 
		}
	    preg_match (
	        "/[^\/]+\.[^\.\/]+$/", 
	        $host, $matches
	    );
		
		$info = $matches[0];
		
		$matches2 = explode(".",$info);
		
		if (count($matches2)>2){
			$matches = $matches2[1] .".".$matches2[2];
		}else{
			$matches = $info;
		}

		return $matches;
		
	} 
	
	/**
	Obtenir le sous repertoire a partir d'une url et les autres infos
	*/
	public static function getsubdirfromurl($url) {
		
		$matches = parse_url($url);
		$matches["subdirs"] = array();
		if (isset($matches["path"])){
			$matches["subdirs"]  = explode("/",$matches["path"]);	
		}
		return $matches;
		
	} 
	
	/* Transforme une chaine en regex */
	public static function transformToRegex($s){
		$s = str_replace(".","\.",$s);
		$s = str_replace("/","\/",$s);
		return $s;
	}
	
	// Renomme lurl .. en HP
	public static function renameurl($url){
		$shorturl = $url;
		if ($shorturl == "." or $shorturl == ".."){
			$shorturl = "-HP-";
		}
		return $shorturl;
	}
	
	// Ecrit une duree au format Heure:minute:seconde
	public static function formatTime($iDuree){
		$iDuree = round($iDuree,0);
		$iHeure = 0;
		$iMin = 0;
		$iSec = 0;
		$iHeure = round(floor($iDuree/3600),0);
		$iDuree = $iDuree - $iHeure*3600;
		$iMin = round(floor($iDuree/60),0);
		$iDuree = $iDuree - $iMin*60;
		$iSec = $iDuree;
		
		$result = sprintf("%02d",$iHeure).":".sprintf("%02d",$iMin).":".sprintf("%02d",$iSec);
		return $result;
	}
	
	//Calcul la variation entre un kpi A T et a T-1
	public static function variation($fPresent, $fPasse){
		$r = "";
		if ($fPasse!=0){
			$r = round(($fPresent-$fPasse)*100/$fPasse);
			if ($r>0){
				$r = "+".$r;
			}
			$r.="%";
		}
		return $r;
	}
	
	//Calcul la pourcentage entre 2 chiffres
	public static function pourcentage($num, $total){
		$r = "";
		if ($total!=0){
			$r = round($num/$total*100);
			
			$r.="%";
		}
		return $r;
	}
	
	/* Encode name for python script */
	public static function encName($s, $bSpace = true){
		
		  $r = strtolower(str_replace("\r","",str_replace("\n","_",str_replace("-","_",str_replace("'","",self::skip_accents($s))))));
		  if ($bSpace) {
			  $r = str_replace(" ","_",$r);
		  }
          $k = strpos($r,"?");
          if ($k !== false){
               $r = substr($r,0,$k);
          }
          return $r;
	}
     
     /* Encode name for python script */
	public static function encLine($s){
		
		$r = str_replace("\"","''",str_replace("\r","",str_replace("\n","\\n",$s)));
		  
          return $r;
	}
	
	public static function skip_accents( $str, $charset='utf-8' ) {
 
		$str = htmlentities( $str, ENT_NOQUOTES, $charset );
		
		$str = preg_replace( '#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
		$str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
		$str = preg_replace( '#&[^;]+;#', '', $str );
		
		return $str;
	}

	/* Checking permissions */
	public static function checkPermission($quizz_id){
		$r = false;
		$uid = 0;
		if (Auth::user()){
			$uid = Auth::user()->id;
		}
		$lst = Quizz::where("user_id","=",$uid)->get();
		if (count($lst)>0){
			$r = true;
		}
		
		return $r;
	}
	
	public static function zip_r($from, $zip, $base=false) {
		if (!file_exists($from) OR !extension_loaded('zip')) {return false;}
		if (!$base) {$base = $from;}
		$base = trim($base, '/');
		$zip->addEmptyDir($base);
		$dir = opendir($from);
		while (false !== ($file = readdir($dir))) {
			if ($file == '.' OR $file == '..') {continue;}

			if (is_dir($from . '/' . $file)) {
				self::zip_r($from . '/' . $file, $zip, $base . '/' . $file);
			} else {
				$zip->addFile($from . '/' . $file, $base . '/' . $file);
			}
		}
		return $zip;
	}
     
     // Function to delete all files 
     // and directories 
     public static function deleteAll($str) { 
           
         // Check for files 
         if (is_file($str)) { 
               
             // If it is file then remove by 
             // using unlink function 
             return unlink($str); 
         } 
           
         // If it is a directory. 
         elseif (is_dir($str)) { 
               
             // Get the list of the files in this 
             // directory 
             $scan = glob(rtrim($str, '/').'/*'); 
               
             // Loop through the list of files 
             foreach($scan as $index=>$path) { 
                   
                 // Call recursive function 
                 self::deleteAll($path); 
             } 
               
             // Remove the directory itself 
             return @rmdir($str); 
         } 
     } 
        
     private static function random_color_part() {
         return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
     }

     /* Generate Hexa Code */
     public static function random_color($bCompletelyRandom = true) {
         if ($bCompletelyRandom){
               return self::random_color_part() . self::random_color_part() . self::random_color_part();
         }else{
              $defines = ["2f7fd0","074482","de295b","92304b","6c9230","629a0a","9a6e0a","5f4306","e25930","259ca9","1d3c1c","8f9041","d20808",
              "7073cc","252bd2","8a8de2","e28adf","a53ea1","ea1fe3","7b0477","ab1a3c","d07a8e","e09cac","164e0c"];
              $randIndex = array_rand($defines);
              return $defines[$randIndex];
         }
     }
	 
	public static function removeSpecialChar($str){ 
		$unwanted_array = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );
				
		$find = false;
		$str2 = utf8_encode($str);
		foreach ($unwanted_array as $char=>$rep){
			$pos = strpos($str2, $char);
			if ($pos !== false){
				$str2 = str_replace($char, $rep, $str2);
				$find = true;
			}
		}
		
		if ($find == false){
			$str2 = $str;
			$str2 = strtr( $str, $unwanted_array );
		}
		
		return $str2; 
	} 
}

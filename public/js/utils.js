$(document).ready(function() {	
	$.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
	
	 $("#country").countrySelect({
		defaultCountry: "France",
		preferredCountries: [],
		responsiveDropdown: true
	});	
});	


function check(){
	
	if (document.getElementById("nom").value==''){
		alert ('Renseigner le nom du quizz.');
		document.getElementById("nom").focus();
		return false;
	}
	for (var i=1;i<16;i++){		
		if (document.getElementById('libelle_'+i).value!=''){
			var iReponse=0;			
			var iTotal=0;
	
			for (var k=1;k<5;k++){
				if (document.getElementById('valide_'+i+'_'+k).checked){
					iReponse++;					
				}	
				iTotal=iTotal+parseInt(document.getElementById('vote_'+i+'_'+k).value);				
			}
			if (iReponse!=1){
				alert ('Cochez une reponse et une seule pour la question '+i);
				document.getElementById('libelle_'+i).focus();
				return false;
			}
			if (iTotal!=100){
				alert ('Le total des votes doit etre de 100 pour la question '+i);
				document.getElementById('libelle_'+i).focus();
				return false;
			}			
		}
	}
	return true;	
}

function recalcul_check(iNumQuestion,iNumReponse){
	for (var i=1;i<5;i++){
		if (i!=iNumReponse){
			document.getElementById('valide_'+iNumQuestion+"_"+i).checked=false;
		}
	}
}

function recalcul_vote(iNumQuestion,iNumReponse){
	var iTotal=0;
	var iVal=100-parseInt(document.getElementById('vote_'+iNumQuestion+"_"+iNumReponse).value);
	
	if (iNumReponse!=1){
		iTotal=iTotal+parseInt(document.getElementById('vote_'+iNumQuestion+"_1").value);
	}
	if (iNumReponse!=2){
		iTotal=iTotal+parseInt(document.getElementById('vote_'+iNumQuestion+"_2").value);
	}
	if (iNumReponse!=3){
		iTotal=iTotal+parseInt(document.getElementById('vote_'+iNumQuestion+"_3").value);
	}
	if (iNumReponse!=4){
		iTotal=iTotal+parseInt(document.getElementById('vote_'+iNumQuestion+"_4").value);
	}
	
	//Recalcul
	for (var i=1;i<5;i++){
		if (i!=iNumReponse){
			var iCalcul=parseInt(document.getElementById('vote_'+iNumQuestion+"_"+i).value)*iVal/iTotal;
			document.getElementById('vote_'+iNumQuestion+"_"+i).value=Math.round(iCalcul);
		}
	}
	
	//Se debrouille pour que le total soit 100
	iTotal=0;
	for (var i=1;i<4;i++){
		iTotal=iTotal+parseInt(document.getElementById('vote_'+iNumQuestion+"_"+i).value);
	}
	document.getElementById('vote_'+iNumQuestion+"_4").value=100-iTotal;
}
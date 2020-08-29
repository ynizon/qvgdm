@extends('layouts.game')

@section('content')

<?php
$nom_joueur = session('nom_joueur');
if ($nom_joueur == ""){
	$nom_joueur = "Jean Pierre";	
}

$tel = session('tel');
$vote = session('vote');
$pallier = session('pallier');
$v50 = session('v50');
$reprise_num_question = session('reprise_num_question');
$num_quizz = $quizz->id;
?>

<div class="container black">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <table cellpadding="0" cellspacing="0">
				<tr>
				<td valign='top'>				
				<img border="0" src='/images/icone_joker.jpg' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/><br/>
				<?php
				if ($vote=="OK"){
				?>
					<a href='/quizz/<?php echo $num_quizz."/valide/".$question->num."/0?joker=vote";?>'><img border="0" src='/images/icone_vote.jpg' /></a><br/><br/>
				<?php
				}else{
					echo "<img border='0' src='/images/icone_voteKC.jpg' /><br/><br/>";
				}
				
					
				if ($tel=="OK"){
				?>
					<a href='/quizz/<?php echo $num_quizz."/valide/".$question->num."/0?joker=tel";?>' onclick="window.open('http://www.google.com/search?site=&hl=<?php echo $quizz->langue;?>&q=<?php echo str_replace('"'," ",$question->libelle);?>');"><img border="0" src='/images/icone_tel.jpg' /></a><br/><br/>
				<?php
				}else{
					echo "<img border='0' src='/images/icone_telKC.jpg' /><br/><br/>";
				}	
				if ($v50=="OK"){
				?>
					<a href='/quizz/<?php echo $num_quizz."/valide/".$question->num."/0?joker=v50";?>'><img border="0" src='/images/icone_50.jpg' /></a>
				<?php
				}else{
					echo "<img border='0' src='/images/icone_50KC.jpg' />";
				}	
			
				?>
				</td>
				<td>
				<?php
				if ($question->num==16){?>
					<img src='/images/feu-artifice-a.jpg'/>'<br/>
				<?php
				}else{
				?>	
					<img border="0" src="/createimagewithword?image_filename=/images/backgrounds/<?php echo $question->background;?>&num_question=<?php echo $question->num;?>&num_quizz=<?php echo $num_quizz;?>&perdu=<?php if (isset($_GET["perdu"])){echo "1";}?>&joker=<?php if (isset($_GET["joker"])){echo $_GET["joker"];}?>" usemap="#Map" />
					<br/>
					<br/>
					<?php
					$display = "display:none";
					if (isset($_GET["joker"])){
						if ("vote" == $_GET["joker"]){
							$display = "";	
						}
					}
					?>
					<div id="container" style="<?php echo $display;?>;padding-left:90px;margin:auto;width:100%; height:400px;"></div>
					<script>
						var colors = ['#631186', '#363bc5', '#631186', '#363bc5'];
						document.addEventListener('DOMContentLoaded', function () {
							var myChart = Highcharts.chart('container', {
							  chart: {
								type: 'column',
								backgroundColor:'#000000'
							  },
							  title: {
								text: 'Votes du public',
								style:{"color":"#ffffff"}
							  },							  
							  credits:false,
							  subtitle: {
								text: ''
							  },
							  labels:{
								  style:{"color":"#ffffff"}
							  },	
							  xAxis: {
								categories: [
								  'A',
								  'B',
								  'C',
								  'D'								  
								],
								labels:{
									style:{"color":"#ffffff"}
								},
							  },
							  yAxis: {
								min: 0,
								title: {
								  text: ''
								},
								labels:{
									style:{"color":"#ffffff"}
								},								
							  },
							  tooltip: {
								
							  },
							  plotOptions: {
								column: {
								  pointPadding: 0,
								  borderWidth: 0,
								  groupPadding: 0,
								  shadow: false
								}
							  },
							  <?php
							  $reponses = iterator_to_array($question->reponses());
							  ?>
							  series: [{
								showInLegend: false,
								name: 'Réponses',
								colorByPoint: true,
								colors:colors,
								data: [<?php echo $reponses[0]->vote;?>,<?php echo $reponses[1]->vote;?>,<?php echo $reponses[2]->vote;?>,<?php echo $reponses[3]->vote;?>]
							  }]
							});

						});
					</script>
					
					
				<?php
					//Affichage du media
					if (trim($question->pj) != ""){		
						$display = "";
						if ($question->cacher_media==1){
							$display = "display:none;";
						}
						?>
						<div style="<?php echo $display;?>">
							<?php						
							echo strip_tags($question->pj,"<a><iframe><img><audio><video><p><br><hr><media>");	
							?>
						</div>
						<?php
					}
				}
				?>

				<map name="Map" id="Map">
				<?php
				//C'est perdu
				$sLien1="";
				$sLien2="";
				$sLien3="";
				$sLien4="";
				if (isset($_GET["perdu"])){
					$sLien1="onclick=\"goto('/quizz/".$num_quizz."/question/1?mode=start');\"";
					$sLien2="onclick=\"goto('/quizz/".$num_quizz."/question/".$question->num."?mode=start');\"";
					$sLien3="onclick=\"goto('/');\"";
					$sLien4="onclick=\"goto('https://www.google.com');\"";
				?>
					<area shape="rect" coords="74,442,349,473" style='cursor:pointer' onmouseover='select(1);'/>
					<area shape="rect" coords="419,442,695,475" style='cursor:pointer' onmouseover='select(2);' />
					<area shape="rect" coords="74,491,343,525" style='cursor:pointer' onmouseover='select(3);' />
					<area shape="rect" coords="425,493,695,526" style='cursor:pointer' onmouseover='select(4);' />

				<?php	
				}else{
					//Quizz normal
					$sLien1="onclick=\"goto('/quizz/".$num_quizz."/valide/".$question->num."/1');\"";
					$sLien2="onclick=\"goto('/quizz/".$num_quizz."/valide/".$question->num."/2');\"";
					$sLien3="onclick=\"goto('/quizz/".$num_quizz."/valide/".$question->num."/3');\"";
					$sLien4="onclick=\"goto('/quizz/".$num_quizz."/valide/".$question->num."/4');\"";
				?>
					<area shape="rect" coords="74,442,349,473" style='cursor:pointer'  onmouseover='select(1);'/>
					<area shape="rect" coords="419,442,695,475" style='cursor:pointer' onmouseover='select(2);' />
					<area shape="rect" coords="74,491,343,525" style='cursor:pointer' onmouseover='select(3);' />
					<area shape="rect" coords="425,493,695,526" style='cursor:pointer' onmouseover='select(4);' />
				<?php
				}

				$iPallier=$question->num;
				if ($question->num==0){
					$iPallier=$pallier;
				}

				?>	
				</map>
				</td>
				<td valign='top'>
					<table>
						<tr><td><?php if ($iPallier==16 or $iPallier==15){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='orange'><b>1 MILLION &#8364;</b></td></tr>
						<tr><td><?php if ($iPallier==14){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>300 000 &#8364;</td></tr>
						<tr><td><?php if ($iPallier==13){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>150 000 &#8364;</td></tr>
						<tr><td><?php if ($iPallier==12){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>100 000 &#8364;</td></tr>
						<tr><td><?php if ($iPallier==11){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>72 000 &#8364;</td></tr>
						<tr><td><?php if ($iPallier==10){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'><b>48 000 &#8364;</b></td></tr>
						<tr><td><?php if ($iPallier==9){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>24 000 &#8364;</td></tr>
						<tr><td><?php if ($iPallier==8){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>12 000 &#8364;</td></tr>
						<tr><td><?php if ($iPallier==7){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>6 000 &#8364;</td></tr>
						<tr><td><?php if ($iPallier==6){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>3 000 &#8364;</td></tr>
						<tr><td><?php if ($iPallier==5){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'><b>1 500 &#8364;</b></td></tr>
						<tr><td><?php if ($iPallier==4){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>800 &#8364;</td></tr>
						<tr><td><?php if ($iPallier==3){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>500 &#8364;</td></tr>
						<tr><td><?php if ($iPallier==2){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>300 &#8364;</td></tr>
						<tr><td><?php if ($iPallier==1){echo "<img src='/images/fleche.jpg'/>";}?></td><td class='blanc'>200 &#8364;</td></tr>

				<?php 
				//Affichage du media
				if (trim($question->pj) == ""){							
					//Ajout d'un son'					
					$joker = "";
					if (isset($_GET["joker"])){
						$joker = $_GET["joker"];
					}
					if (!isset($_GET["perdu"])){
						if ($joker ==""){
							if ($question->num==1){
								echo '<tr><td><div style="display:none"><audio controls="controls" autoplay="autoplay">
									  <source src="/mp3/debut.mp3" type="audio/mp3" />
										Votre navigateur n est pas compatible
									  </audio></div></td></tr>';
								//echo '<tr><td><embed type="audio/x-mpegurl" autostart="true" hidden="true" src="mp3/debut.mp3"></embed></td></tr>';
							}else{							
								if ($question->num==15){
									echo '<tr><td><div style="display:none"><audio controls="controls" autoplay="autoplay">
									  <source src="/mp3/bonne_rep.mp3" type="audio/mp3" />
										Votre navigateur n est pas compatible
									  </audio></div></td></tr>';
									//echo '<tr><td><embed type="audio/x-mpegurl" autostart="true" hidden="true" src="mp3/bonne_rep.mp3"></embed></td></tr>';
								}else{
									echo '<tr><td><div style="display:none"><audio controls="controls" autoplay="autoplay">
									  <source src="/mp3/bonne_rep_seule.mp3" type="audio/mp3" />
										Votre navigateur n est pas compatible
									  </audio></div></td></tr>';
									//echo '<tr><td><embed type="audio/x-mpegurl" autostart="true" hidden="true" src="mp3/bonne_rep_seule.mp3"></embed></td></tr>';
								}
							}
						}
					}
				}
				?>
					</table>
				</td>
				</tr>
				</table>

				<img id='img_reponse1' src='/images/survol.png' style='position:absolute;top:441px;left:150px;cursor:pointer' <?php echo $sLien1; ?>/>
				<img id='img_reponse2' src='/images/survol.png' style='position:absolute;top:441px;left:498px;cursor:pointer' <?php echo $sLien2; ?>/>
				<img id='img_reponse3' src='/images/survol.png' style='position:absolute;top:491px;left:150px;cursor:pointer' <?php echo $sLien3; ?>/>
				<img id='img_reponse4' src='/images/survol.png' style='position:absolute;top:491px;left:498px;cursor:pointer' <?php echo $sLien4; ?>/>

				<script language='javascript'>
				function goto(s){
					var i=1;
				<?php	
					//	Question de JP
					$rand = rand(1, 5);
					if ($rand==2){
						echo "if (!window.confirm(\"C'est votre dernier mot ".$nom_joueur." ?\")){i=0;}";
					}
				?>	
					
					if (i==1){
						window.location=s;
					}
				}

				//Cache tous les selects
				function cacheTout(){
					document.getElementById('img_reponse1').style.display='none';
					document.getElementById('img_reponse2').style.display='none';
					document.getElementById('img_reponse3').style.display='none';
					document.getElementById('img_reponse4').style.display='none';
					
				}

				//Affiche la bonne reponse
				function select(iReponse){
					cacheTout();		
					document.getElementById('img_reponse'+iReponse).style.display='inline';
				}
				cacheTout();
				</script>
			
        </div>
    </div>
</div>
@endsection

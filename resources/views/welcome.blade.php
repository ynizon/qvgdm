@extends('layouts.app')

@section('content')
        
<div class="flex-center position-ref xfull-height">	
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header"><h1 style="font-size:0.9rem">Faites votre quizz: "Qui veut gagner des millions"</h1></div>

					<div class="card-body">
						Réalisez facilement votre quizzz. Pour cela, <a href='/register'>inscrivez-vous</a> puis suivez le guide.<br/>
						Partagez ensuite votre création. Tout est GRATUIT !
					</div>
				</div>
				<br/>
				<div class="card">
					<div class="card-header">Liste des quizz disponibles</div>

					<div class="card-body">
						<div class="row">
							<div class="col-md-6">
								Nom du quizz (type) - nombre de fois gagné / nombre de fois joué<br/>
								<ul>									
									<?php
									if (!isset($_GET["page"])){
										foreach ($last_quizzs as $quizz){
											?>
											<li><a href="/quizz/<?php echo $quizz->id;?>"><?php echo $quizz->nom;?><?php if ($quizz->type != ""){echo " (".$quizz->type.")";} echo " - ".$quizz->nbgagner."/".$quizz->nb;?></a></li>
											<?php
										}
										?>
										<li style="list-style:none"><hr/></li>
									<?php
									}
									foreach ($quizzs as $quizz){
										?>
										<li><a href="/quizz/<?php echo $quizz->id;?>"><?php echo $quizz->nom;?><?php if ($quizz->type != ""){echo " (".$quizz->type.")";} echo " - ".$quizz->nbgagner."/".$quizz->nb;?></a></li>
										<?php
									}
									?>						
								</ul>		

								{{ $quizzs->links() }}							
							</div>
							<div class="col-md-6" style="text-align:right">
								<img src="/images/logo_transp.png" />
								<br/><br/>
								<form method="get">
									{{ csrf_field() }}
									<input type="text" name="name" placeholder="nom du quizz" value="<?php echo $name;?>" /><br/><br/>
									<input class="btn btn-primary" type="submit" value="Chercher un quizz" />
								</form>
							</div>
							
						</div>						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection

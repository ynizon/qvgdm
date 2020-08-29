@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
				<div class="card-header"><?php echo $quizz->nom;?></div>

                <div class="card-body">
					Indiquez votre nom 
					<form method="post" action="/quizz/<?php echo $quizz->id;?>/question/1?mode=start">
						{{ csrf_field() }}
						<?php
						$nom_joueur = $request->session()->get('nom_joueur');
						
						?>
						<input type="text" required class="form-control" name="nom_joueur" value="<?php echo $nom_joueur;?>" /><br/>
						
						<p>
							<?php
							echo $quizz->intro;
							?>
						</p>
						<input type="submit" value="Démarrer" class="btn btn-primary" />
					</form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

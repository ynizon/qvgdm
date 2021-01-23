@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <h2 class="card-header">Editer</h2>
                <div class="card-body">
					<?php
					if ($quizz->id != ""){
						$route = ['route' => ['quizz.update',$quizz->id],'files'=>true, 'method' => $method, 'class' => 'form-horizontal panel', 'onsubmit'=> 'return check()'];
					}else{
						$route = ['url' => ['quizz'],'files'=>true, 'method' => $method, 'class' => 'form-horizontal panel', 'onsubmit'=> 'return check()'];
					}
					
					?>
					<ul class="nav nav-tabs" role="tablist">
						<li class="nav-item <?php if (!isset($_GET["questions"])){echo "active";}?>">
							<a class="nav-link <?php if (!isset($_GET["questions"])){echo "active";}?>" data-toggle="tab" href="#quizz">Quizz</a>
						</li>
						<li class="nav-item <?php if (isset($_GET["questions"])){echo "active";}?>">
							<a class="nav-link <?php if (isset($_GET["questions"])){echo "active";}?>" data-toggle="tab" href="#questions"><?php echo count($quizz->questions());?>/15 questions</a>
						</li>
					</ul>
					
					<div class="tab-content">
						<div id="quizz" class="tab-pane fade <?php if (!isset($_GET["questions"])){echo "show active";}?>">
							<br/>
							{!! Form::model($quizz, $route) !!}
								{{ csrf_field() }}

							<div class="form-group{{ $errors->has('info') ? ' has-error' : '' }}">
								<label for="nom" class="col-md-10">
									<?php
									if ($quizz->id>0){
									?>
										<a href='<?php echo config("app.url");?>/quizz/<?php echo $quizz->id;?>' target="_blank">Lien vers votre quizz: <?php echo config("app.url");?>/quizz/<?php echo $quizz->id;?></a>
									<?php
									}else{
										?>Ici vous enregistrez les informations générales du quizz. Vos questions et vos reponses sont dans l'autre onglet. 
										Vous pouvez ajouter des medias (videos, audios...) provenant de deezer, youtube... Pour cela, il faut inserer le code HTML provenant du site dans la case correspondante. Sur deezer, vous pouvez creer des blind test musicaux et pour eviter de voir le titre de la chanson , il vous faudra cacher le media.
										Enfin, quand vos questions sont terminées, cliquez sur Enregistrer, puis tester les via l'adresse ecrite dans "Lien direct". Celle ci apparaitra après votre premier enregistrement. Sélectionner le compteur pour une remise a 0 des visites, puis sélectionner le statut publié et réenregistrer. Votre quizz est terminé et visible par tous.
										<?php
									}
									?>
								</label>
							</div>
						
							
							<div class="form-group{{ $errors->has('nom') ? ' has-error' : '' }}">
								<label for="nom" class="col-md-4 control-label">Nom</label>

								<div class="col-md-6">
									<input id="nom" type="text" class="form-control" name="nom" value="{!! $quizz->nom !!}" required autofocus />

									@if ($errors->has('nom'))
										<span class="help-block">
											<strong>{{ $errors->first('nom') }}</strong>
										</span>
									@endif
								</div>
							</div>
							
							<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
								<label for="picture" class="col-md-4 control-label">Type de quizz							
								</label>

								<div class="col-md-6">								
									<input required id="type" placeholder="Culture générale" type="text" class="form-control" name="type" value="{!! $quizz->type !!}" />
									  
									@if ($errors->has('type'))
										<span class="help-block">
											<strong>{{ $errors->first('type') }}</strong>
										</span>
									@endif
								</div>
							 </div>
							 
							<div class="form-group{{ $errors->has('langue') ? ' has-error' : '' }}">
								<label for="langue" class="col-md-4 control-label">Langue</label>

								<div class="col-md-6">
									<input type="text" id="country"  value="{!! $quizz->langue !!}" name="langue" required />

									@if ($errors->has('langue'))
										<span class="help-block">
											<strong>{{ $errors->first('langue') }}</strong>
										</span>
									@endif
								</div>
							</div>
							
							<div class="form-group{{ $errors->has('intro') ? ' has-error' : '' }}">
								<label for="intro" class="col-md-4 control-label">Message d'introduction</label>

								<div class="col-md-6">
									<textarea id="intro" required class="form-control" name="intro">{!! $quizz->intro !!}</textarea>

									@if ($errors->has('intro'))
										<span class="help-block">
											<strong>{{ $errors->first('intro') }}</strong>
										</span>
									@endif
								</div>
							</div>
							
							<div class="form-group{{ $errors->has('conclusion') ? ' has-error' : '' }}">
								<label for="conclusion" class="col-md-4 control-label">Message final</label>

								<div class="col-md-6">
									<textarea id="conclusion" required class="form-control" name="conclusion">{!! $quizz->conclusion !!}</textarea>

									@if ($errors->has('conclusion'))
										<span class="help-block">
											<strong>{{ $errors->first('conclusion') }}</strong>
										</span>
									@endif
								</div>
							</div>
							
							<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
								<label for="status" class="col-md-4 control-label">Statut</label>

								<div class="col-md-6">
									<select id="status" required class="form-control" name="status">
										<option value="0" <?php if ($quizz->status == 0){echo "selected";}?> >Brouillon</option>
										<option value="1" <?php if ($quizz->status == 1){echo "selected";}?>>Publié</option>
									</select>

									@if ($errors->has('status'))
										<span class="help-block">
											<strong>{{ $errors->first('status') }}</strong>
										</span>
									@endif
								</div>
							</div>
							
							<div class="form-group{{ $errors->has('reset') ? ' has-error' : '' }}">
								<label for="reset" class="col-md-4 control-label">Compteur</label>

								<div class="col-md-6">
									<select id="reset" required class="form-control" name="reset">
										<option value="0">-</option>
										<option value="1">Remettre à zéro</option>
									</select>

									@if ($errors->has('reset'))
										<span class="help-block">
											<strong>{{ $errors->first('reset') }}</strong>
										</span>
									@endif
								</div>
							</div>
							
							<div class="form-group">
									<div class="col-md-8 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Enregistrer
										</button>

									</div>
								</div>                    
							{!! Form::close() !!}
						</div>
						
						<div id="questions" class="tab-pane <?php if (isset($_GET["questions"])){echo "show active";}?>">
							<br/>
							{!! Form::model($quizz, ['route' => ['update_questions',$quizz->id],'files'=>true, 'method' =>"POST", 'class' => 'form-horizontal panel']) !!}
								{{ csrf_field() }}
								<a href='/quizz/<?php echo $quizz->id;?>/addquestion'><i class="fa fa-plus"></i></a><br/>
								<?php
								$q=0;
								foreach ($quizz->questions() as $question){
									$q++;
									?>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group{{ $errors->has('qnom') ? ' has-error' : '' }}">
												<label for="qnom" class="col-md-8 control-label">Question <?php $q;?>
													<input required id="num_<?php echo $q;?>" type="text" class="form-control" style="display:inline;width:50px;" name="num_<?php echo $q;?>" value="{!! $question->num !!}" />
												</label>

												<div class="">													
													<input id="libelle_<?php echo $q;?>" type="text" class="form-control" name="libelle_<?php echo $q;?>" value="<?php echo str_replace('"',"'",$question->libelle);?>" />
													<br/>
													<div >
														Fond:
														<select name="background_<?php echo $q;?>" class="form-control">
															<?php
															$files = scandir(public_path()."/images/backgrounds");
															foreach ($files as $file){
																if ($file != ".." and $file != "."){
																	?>
																	<option <?php if($question->background == $file){echo "selected";} ?> value="<?php echo $file;?>"><?php echo str_replace(".jpg","",$file);?></option>
																	<?php
																}
															}
															?>
														</select>
														<br/>
													</div>
													Code HTML à intégrer:
													<br/>
													<textarea id="pj_<?php echo $q;?>" class="form-control" name="pj_<?php echo $q;?>">{!! $question->pj !!}</textarea>
													<br/>
													
													Cacher le média

													<select id="cacher_media_<?php echo $q;?>" required class="form-control" name="cacher_media_<?php echo $q;?>">
														<option value="0" <?php if ($question->cacher_media == 0){echo "selected";}?> >Caché</option>
														<option value="1" <?php if ($question->cacher_media == 1){echo "selected";}?>>Affiché</option>
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<label for="qnom" class="control-label">Bonne réponse / Libellé / Vote du public (%)</label>
											<ul style="padding:0">
											<?php
											$r = 0;
											foreach ($question->reponses() as $reponse){
												$r++;
												?>
												<li style="list-style:none">
													<input onchange="recalcul_check(<?php echo $q;?>,<?php echo $r;?>)" onclick="recalcul_check(<?php echo $q;?>,<?php echo $r;?>)" id="valide_<?php echo $q;?>_<?php echo $r;?>" type="checkbox" name="valide_<?php echo $q;?>_<?php echo $r;?>" value="1" <?php if($reponse->valide==1){echo "checked";}?>/>
													<input id="libelle_<?php echo $q;?>_<?php echo $r;?>" type="text" class="form-control" style="display:inline;width:70%" name="libelle_<?php echo $q;?>_<?php echo $r;?>" value="<?php echo str_replace('"',"'",$reponse->libelle);?>" />
													<input onchange="recalcul_vote(<?php echo $q;?>,<?php echo $r;?>)" onclick="recalcul_vote(<?php echo $q;?>,<?php echo $r;?>)" maxlength= "2" id="vote_<?php echo $q;?>_<?php echo $r;?>" type="text" style='display:inline;width:50px' class="form-control" name="vote_<?php echo $q;?>_<?php echo $r;?>" value="{!! $reponse->vote !!}" />												
												</li>
												<?php
											}
											?>											
											</ul>
										</div>
									</div>
									<hr/>
									<?php
								}
								?>							
								<div class="form-group">
									<div class="col-md-8 col-md-offset-4">
										<button type="submit" class="btn btn-primary">
											Enregistrer
										</button>

									</div>
								</div>                    
							{!! Form::close() !!}
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

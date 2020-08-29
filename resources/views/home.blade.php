@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Mes quizz</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
					
                    <a href='/quizz/create'><i class="fa fa-plus"></i></a>
					<ul>
						<?php
						foreach ($quizz as $q){
						?>
							<li>
								<a href='/quizz/<?php echo $q->id;?>/edit'><?php echo $q->nom. " (".$q->nbgagner."/".$q->nb;?>)</a>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								{!! Form::open(['method' => 'DELETE', "style"=>"display:inline",'route' => ['quizz.destroy', $q->id]]) !!}
									&nbsp;<a href="#" class="pointer" title="Remove" onclick="if (confirm('Confirmez la suppression ?')){$(this).parent().submit();}"><i class="fa fa-trash"></i></a>&nbsp;&nbsp;
								{!! Form::close() !!}
							</li>
						<?php
						}
						?>
					</ul>
					{{ $quizz->links() }}							
                </div>
            </div>
        </div>
		
		<div class="col-md-4">
			<div class="card">
				<div class="card-body">
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
@endsection

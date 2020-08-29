@extends('layouts.app')

@section('content')
<?php
$user = Auth::user();

?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">				
                <div class="card-body">
					<div class="card-header">Contact</div>
					<div class="card-body">
						Pour me contacter, écrivez à <a href="mailto:ynizon@gmail.com">ynizon@gmail.com</a><br/>
						Pour me remercier, vous pouvez me donner quelques euros via <a href='https://www.paypal.me/ynizon'>https://www.paypal.me/ynizon</a> (ca permettra d'ajouter des fonctionnalités)
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
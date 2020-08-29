<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
	<script src="{{ asset('js/countrySelect.min.js') }}" defer></script>
	<script src="{{ asset('js/utils.js') }}" defer></script>	
	<script src="https://code.highcharts.com/highcharts.js"></script>
	
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
	<link rel="stylesheet" href="/css/font-awesome.min.css" />
	<link href="{{ asset('css/countrySelect.min.css') }}" rel="stylesheet" />
	<style>
		.black{background:#000;color:#fff;}
	</style>	
</head>
<body class="black">
    <div id="app">        

		<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>               
            </div>
        </nav>
		
        <main class="py-4">
            @yield('content')
        </main>
		<br style="clear:both"/>
		<br style="clear:both"/>
		<br style="clear:both"/>
		
		<nav class="footer navbar navbar-expand-md navbar-light bg-white shadow-sm">
			<div class="container">	
				<br/>
				<a class="navbar-brand" href='/contact'>Me contacter</a>  <a class="navbar-brand"  href='https://www.gameandme.fr'>(c) Yohann Nizon - Expert PHP Nantes</a>
			</div>
		</nav>
    </div>
</body>
</html>

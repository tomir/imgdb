@extends('Main.Boilerplate')

@section('assets')
  @parent
  {{ HTML::style('assets/css/slider-single.css') }}
  {{ HTML::style('assets/js/slick/slick.css') }}
  {{ HTML::style('assets/js/slick/slick-theme.css') }}
@stop

@section('bodytag')
	<body id="home" class="nav-trans animate-nav">
@stop

@section('nav')
	@include('Partials.Navbar')
@stop

@section('content')
 	{{ $content }}
@stop

@section('scripts')

	{{ HTML::script('assets/js/vendor/slider.js') }}
	{{ HTML::script('//code.jquery.com/jquery-migrate-1.2.1.min.js') }}
	{{ HTML::script('assets/js/slick/slick.js') }}
	
	
	<script>
		$('.slick').slick();
		vars.trailersPlayer = '<?php echo $options->trailersPlayer(); ?>';
        ko.applyBindings(app.viewModels.home, $('.content')[0]);
    </script>

@stop
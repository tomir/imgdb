@extends('Main.Boilerplate')

@section('bodytag')
	<body id="titles-index">
@stop

@section('assets')
	@parent

  <meta name="fragment" content="!">
  <meta name="title" content="{{ trans('main.meta title') }}">
  <meta name="description" content="{{ trans('main.meta description') }}">
  <meta name="keywords" content="{{ trans('main.meta keywords') }}">
  
	{{ HTML::style('assets/css/pikaday.css') }}
@stop

@section('content')

  	<div class="container" id="content">

        {{ Hooks::renderHtml('Titles.Index.UnderFilters') }}

        <div class="clearfix">
            @if(Helpers::hasAccess('titles.edit'))
                <a class="btn new-button btn-primary pull-left" href="{{ Request::url().'/create' }}">{{ trans('dash.createNew') }}</a>
            @endif
            <div class="index-pagination"></div>
        </div>

  		<div class="row">

			<div class="col-sm-4 " style="float: right">
				<div style="padding: 15px; border: 1px solid #c3c3c3; margin-bottom: 10px;" >
					<h2 class="heading" style="margin-bottom: 0; background: none; padding-top: 0px;">Movies by genre</h2>
					<div style="margin: 0 20px 30px 15px; padding-bottom: 30px; border-bottom: 1px solid #c3c3c3" class="clearfix">
						<select name="genres" id="genres" class="form-control" data-bind="value: genre" style="display: none;">
								@foreach ($options->getGenres() as $genre)
									<option value="{{ strtolower($genre) }}">{{ $genre }}</option>
								@endforeach
						</select>
						<ul id="selected-genres" data-bind="foreach: params.genres" class="list-unstyled list-inline" style="padding-left: 5px;">
							<li style="font-size: 12px;" data-bind="click: $root.removeGenre"><i class="fa fa-times"></i> <span data-bind="text: app.utils.ucFirst($data)"></span></li>
						</ul>
						
						@foreach ($options->getGenres() as $genre)
							@if (!in_array($genre, array()))
								<div style="width: 50%; padding-left: 5px;float: left; font-size: 12px;"><a href="" onclick="$('#genres').val('{{ strtolower($genre) }}').change(); return false;">{{ $genre }}</a> <span style="font-size: 11px;">(19 987)</span></div>
							@endif
						@endforeach
					</div>
					
					<h2 class="heading" style="margin-bottom: 0; background: none; padding-top: 0px;">Movies by country</h2>
					<div style="margin: 0 20px 30px 15px; padding-bottom: 30px; border-bottom: 1px solid #c3c3c3" class="clearfix">
						<select name="country" id="country" class="form-control" data-bind="value: country" style="display: none;">
								@foreach ($options->getCountry() as $country)
									<option value="{{ strtolower($country) }}">{{ $country }}</option>
								@endforeach
						</select>

						@foreach ($options->getCountry() as $country)
							<div style="width: 50%; padding-left: 5px;float: left; font-size: 12px;"><a href="" onclick="$('#country').val('{{ strtolower($country) }}').change(); return false;">{{ $country }}</a> <span style="font-size: 11px;">(19 987)</span></div>
						@endforeach
					</div>
					
					<h2 class="heading" style="margin-bottom: 0; background: none; padding-top: 0px;">Movies by year</h2>
					<div style="margin: 0 20px 30px 15px; padding-bottom: 30px;" class="clearfix">
						<select name="year" id="year" class="form-control" data-bind="value: year" style="display: none;">
								@foreach ($options->getYear() as $year)
									<option value="{{ $year }}">{{ $year }}</option>
								@endforeach
						</select>

						@foreach ($options->getYear() as $year)
						<div style="width: 50%; padding-left: 5px;float: left; font-size: 12px;"><a href="" onclick="$('#year').val('{{ strtolower($year) }}').change();return false;">{{ $year }}</a> <span style="font-size: 11px;">(19 987)</span></div>
						@endforeach
					</div>
				</div>
			</div>
			
			<section data-bind="foreach: sourceItems">	
				<figure class="col-sm-8" data-bind="attr: { data: $index }">
					<div style="padding: 15px; border: 1px solid #c3c3c3; margin-bottom: 10px;">
						<div style="float: left; width: 20%">
							<a data-bind="attr: { href: vars.urls.baseUrl+'/'+vars.trans[type]+'/'+id+'-'+title.replace(/\s+/g, '-').toLowerCase() }"><img style="height: 20%;" class="img-responsive" data-bind="attr: { src: poster, alt: title }"></a>
						</div>
						<div style="float: left; margin-left: 40px; margin-top: 20px; width: 70%;">
							<figcaption data-bind="text: title+' ('+year+')'" style="font-size: 18px; font-weight: bold"></figcaption>
							<p style="font-size: 12px;"><span data-bind="text: runtime"></span> min&nbsp;&nbsp;|&nbsp;&nbsp;<span data-bind="text: genre.split('|');"></span>&nbsp;&nbsp;|&nbsp;&nbsp;<span data-bind="text: release_date"></span> (<span data-bind="text: country"></span>)</p>
							<p style="background: url('/assets/images/movie_list_review.png'); width: 496px; height: 43px; padding-top: 9px;">
								<span style="font-size: 11px; margin-left: 95px;">8.7/10 760 900 votes</span>
								<span style="font-size: 11px; margin-left: 105px;">70 440 want to see</span>
							</p>
							<p><span data-bind="text: plot.substring(0,250)+'...'"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a data-bind="attr: { href: vars.urls.baseUrl+'/'+vars.trans[type]+'/'+id+'-'+title.replace(/\s+/g, '-').toLowerCase() }">see more >></a></p>
							<p style="text-align: right"></p>
							<p style="text-align: right"><a href=""><img src="/assets/images/add_to_watchlist.png" style="margin-right: 10px;"></a><a href=""><img src="/assets/images/trailer.png"></a></p>
						</div>
						{{ Hooks::renderHtml('Titles.Index.ForEachMovie') }}
						<div style="clear: both"></div>
					</div>
				</figure>

			</section>
			
			
		</div>
        <div class="clearfix">
            <div class="index-pagination bottom-pagination"></div>
        </div>

	</div>

@stop

@section('scripts')
	<script>app.viewModels.titles.index.start('<?php echo $type; ?>');</script>
@stop

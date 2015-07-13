<section class="content" data-bind="playVideos">
	@if ( ! $slides->isEmpty())
	@include('Partials.SliderSingle', array('slides' => $slides))
	@endif

    <div class="container {{ $slides->isEmpty() ? 'no-slider' : '' }}">

		@if ($ad = $options->getHomeJumboAd())
		<div id="ad">{{ $ad }}</div>
        @endif

		@include('Partials.Response')

		<!-- latest news-->
		<div class="col-sm-9" id="news-container">
			<div style="border: 1px solid #c3c3c3; padding: 15px;">
				<h2 class="heading" style="margin-bottom: 0; background: none; padding-top: 0px;"><i class="fa fa-bullhorn"></i> {{ trans('main.latest news') }}</h2>

				<ul class="list-unstyled">
					@foreach($news as $k => $item)

					@if ($k == 4)
					@if ($ad = $options->getHomeNewsAd())
					<div id="ad">{{ $ad }}</div>
					@endif
					@endif

					<li>
						<figure class="pretty-figure" style="height: 82px; margin-bottom: 0px">
							<div style="float:left; padding: 0;" class="col-sm-3">
								<a href="{{ Helpers::url($item->title, $item->id, 'news') }}"><img style="height: 82px" src="{{ $item->image }}" alt="{{ $item->title }}" class="img-responsive"></a>
							</div>
							<div style="float:left; padding: 0;" class="col-sm-9">
								<figcaption style="text-align: left; white-space: normal; height: 82px; max-height: 82px; line-height: 15px;">
									<a style="color: #00a2e8"; href="{{ Helpers::url($item->title, $item->id, 'news') }}">{{ $item->title }}</a>
									<br /><span style="font-weight: normal; font-size: 11px">{{ Helpers::shrtString($item->body, 280) }}... 	<a style="color: #00a2e8"; href="{{ Helpers::url($item->title, $item->id, 'news') }}">see more</a></span>
								</figcaption>
							</div>
						</figure>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
		<!-- /latest news-->

		<div class="col-sm-3" style="padding-left: 0px; margin-bottom: 15px;">
			<div style="padding: 15px; border: 1px solid #c3c3c3">
				<img src="/assets/images/imgdb_onmobile.png" class="img-responsive">
				<span style="font-size: 11px;">As reported by the portal Coming Soon, in Sony are continuing to work on the third part of the "Bad Boys." Because Michael Bay does not sit on the bench again </span>
			</div>
		</div>

		<div class="col-sm-12">

			@if($categories->count())
			@foreach($categories as $category)
			@if ($category->active && $category->name != 'Top Games')
			<div style="padding: 15px; border: 1px solid #c3c3c3; margin-bottom: 10px;">
				<h2 class="heading" style="margin-bottom: 0; background: none; padding-top: 0px;"><i class="{{ $category->icon }}"></i> {{ $category->name }}</h2>
				<section class="row {{ $category->show_rating ? 'with-rating' : '' }} title-sizes" style="padding: 0 50px">


					@if ($category->actor && ! $category->actor->isEmpty())	
					<div class="slick" data-slick='{"slidesToShow": 7, "slidesToScroll": 7}'>
						@foreach($category->actor->slice(0, $category->limit) as $actor)
						<figure class="col-lg-1 col-md-2 col-sm-3 pretty-figure" style="max-height: 200px; width: 14%; margin-bottom: 0px;">
							<a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}"><img src="{{ $actor->image }}" alt="{{ $actor->name }}" class="img-responsive"></a>
							<p style="text-align: center; font-size: 12px; margin-top: 5px;">{{ $actor->name }}<br /><a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}">(33)</a></p>
						</figure>
						@endforeach
					</div>
					@endif

					@if ($category->title && ! $category->title->isEmpty())
					<div class="slick" data-slick='{"slidesToShow": 6, "slidesToScroll": 6}'>
						@foreach($category->title->slice(0, $category->limit) as $title)
						<figure class="col-lg-1 col-md-2 col-sm-3 pretty-figure" style="min-height: 200px; margin-bottom: 0px;">
							<div class="{{ $category->show_trailer ? 'play' : '' }}" data-source="{{ $title->trailer }}" data-poster="{{ $title->background }}">

								@if ($category->show_trailer && $title->trailer)
								<img src="{{ $title->poster }}" alt="{{ $title->title }}" class="img-responsive">
								<div class="center"><img src="{{ asset('assets/images/play.png') }}"> </div>
								@else
								<a href="{{ Helpers::url($title->title, $title->id, $title->type) }}"><img src="{{ $title->poster }}" alt="{{ $title->title }}" class="img-responsive"></a>
								@endif

							</div>
							<p style="text-align: center; font-size: 12px; margin-top: 5px;"><a href="{{ Helpers::url($title->title, $title->id, $title->type) }}">{{ $title->title }}</a></p>

						</figure>
						@endforeach
					</div>
					@endif
				</section>
			</div>
			@endif		
			@endforeach
			@else
			<h4>Create categories you want to display from <strong>dashboard > categories</strong> page.</h4>
			@endif

		</div>

		<!-- latest news-->
		<div class="col-sm-9" id="news-container">
			<div style="border: 1px solid #c3c3c3; padding: 15px;">
				<h2 class="heading" style="margin-bottom: 0; background: none; padding-top: 0px;"><i class="fa fa-bullhorn"></i> {{ trans('main.latest news') }}</h2>

				<ul class="list-unstyled">
					@foreach($news as $k => $item)

					@if ($k == 4)
					@if ($ad = $options->getHomeNewsAd())
					<div id="ad">{{ $ad }}</div>
					@endif
					@endif

					<li>
						<figure class="pretty-figure" style="height: 82px; margin-bottom: 0px">
							<div style="float:left; padding: 0;" class="col-sm-3">
								<a href="{{ Helpers::url($item->title, $item->id, 'news') }}"><img style="height: 82px" src="{{ $item->image }}" alt="{{ $item->title }}" class="img-responsive"></a>
							</div>
							<div style="float:left; padding: 0;" class="col-sm-9">
								<figcaption style="text-align: left; white-space: normal; height: 82px; max-height: 82px; line-height: 15px;">
									<a style="color: #00a2e8"; href="{{ Helpers::url($item->title, $item->id, 'news') }}">{{ $item->title }}</a>
									<br /><span style="font-weight: normal; font-size: 11px">{{ Helpers::shrtString($item->body, 280) }}... 	<a style="color: #00a2e8"; href="{{ Helpers::url($item->title, $item->id, 'news') }}">see more</a></span>
								</figcaption>
							</div>
						</figure>
					</li>
					@endforeach
				</ul>
			</div>
		</div>
		<!-- /latest news-->

		<div class="col-sm-3" style="padding-left: 0px; margin-bottom: 15px;">
			<div style="padding: 15px; border: 1px solid #c3c3c3">
				<img src="/assets/images/imgdb_onmobile.png" class="img-responsive">
				<span style="font-size: 11px;">As reported by the portal Coming Soon, in Sony are continuing to work on the third part of the "Bad Boys." Because Michael Bay does not sit on the bench again </span>
			</div>
		</div>
		
		<div class="col-sm-12">

			@if($categories->count())
			@foreach($categories as $category)
			@if ($category->active && $category->name == 'Top Games')
			<div style="padding: 15px; border: 1px solid #c3c3c3; margin-bottom: 10px;">
				<h2 class="heading" style="margin-bottom: 0; background: none; padding-top: 0px;"><i class="{{ $category->icon }}"></i> {{ $category->name }}</h2>
				<section class="row {{ $category->show_rating ? 'with-rating' : '' }} title-sizes" style="padding: 0 50px">

					@if ($category->name == 'Top Games')
					<div class="slick" data-slick='{"slidesToShow": 6, "slidesToScroll": 6}'>
						@foreach($category->game->slice(0, $category->limit) as $game)
						<figure class="col-lg-1 col-md-2 col-sm-3 pretty-figure" style="max-height: 200px; width: 14%; margin-bottom: 0px;">
							<a href="{{ Helpers::url($game->name, $actor->id, 'people') }}"><img src="{{ $game->image }}" alt="{{ $game->name }}" class="img-responsive"></a>
							<p style="text-align: center; font-size: 12px; margin-top: 5px;">{{ $game->name }}<br /><a href="{{ Helpers::url($game->name, $game->id, 'people') }}">(33)</a></p>
						</figure>
						@endforeach
					</div>
					@endif
				</section>
			</div>
			@endif		
			@endforeach
			@else
			<h4>Create categories you want to display from <strong>dashboard > categories</strong> page.</h4>
			@endif

		</div>

	</div>
</section>

<!-- video modal -->
<div class="modal fade" id="vid-modal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
			<button type="button" class="modal-close" data-dismiss="modal" aria-hidden="true"> 
                <span class="fa-stack fa-lg">
                    <i class="fa fa-circle fa-stack-2x"></i>
                    <i class="fa fa-times fa-stack-1x fa-inverse"></i>
                </span>
            </button>
            <div class="modal-body"> </div>
        </div>
	</div>
</div>
<!-- /video modal -->
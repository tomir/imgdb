<section class="content" data-bind="playVideos">
		@if ( ! $slides->isEmpty())
			@include('Partials.SliderSingle', array('slides' => $slides))
		@endif

    <div class="container">

		@if ($ad = $options->getHomeJumboAd())
            <div id="ad">{{ $ad }}</div>
        @endif

    	@include('Partials.Response')

		<div class="col-sm-9">
			@foreach($categories as $category)

				<section class="row title-sizes">
					<h2 class="heading"><i class="fa fa-fire"></i> {{ $category->name }}</h2>

					@if ($category->actor && ! $category->actor->isEmpty())
						@foreach($category->actor->slice(0, $category->limit) as $actor)
							<figure class="col-md-3 col-sm-6  pretty-figure">
								<a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}"><img src="{{ $actor->image }}" alt="{{ $actor->name }}" class="img-responsive"></a>
								<figcaption><a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}">{{ str_limit($actor->name, 20) }}</a></figcaption>
							</figure>
						@endforeach
					@endif

					@if ($category->title && ! $category->title->isEmpty())
						@foreach($category->title->slice(0, $category->limit) as $title)
							<figure class="col-md-3 col-sm-6  pretty-figure">
								<div class="{{ $category->show_trailer ? 'play' : '' }}" data-source="{{ $title->trailer }}" data-poster="{{ $title->background }}">

									@if ($category->show_trailer && $title->trailer)
										<img src="{{ $title->poster }}" alt="{{ $title->title }}" class="img-responsive">
										<div class="center"><img src="{{ asset('assets/images/play.png') }}"> </div>
									@else
										<a href="{{ Helpers::url($title->title, $title->id, $title->type) }}"><img src="{{ $title->poster }}" alt="{{ $title->title }}" class="img-responsive"></a>
									@endif

									@if ( ! $title->link->isEmpty())
										<div class="status">{{ trans('stream::main.availToStream') }}</div>
									@endif

								</div>
								<figcaption>
									<a href="{{ Helpers::url($title->title, $title->id, $title->type) }}">{{ str_limit($title->title, 20) }}</a>

									@if ($category->show_rating && $title->mc_user_score)
										<div class="home-rating" data-bind="raty: <?php echo $title->mc_user_score; ?>, readOnly: true, stars: 10"></div>
									@endif
								</figcaption>
							</figure>
						@endforeach
					@endif
				</section>
				
			@endforeach
		</div>
		
		<!-- latest news-->
		<div class="col-sm-3" id="news-container">
			<h2 class="heading"><i class="fa fa-bullhorn"></i> {{ trans('main.latest news') }}</h2>

			<ul class="list-unstyled">
				@foreach($news as $k => $item)

					@if ($k == 4)
						@if ($ad = $options->getHomeNewsAd())
				            <div id="ad">{{ $ad }}</div>
				        @endif
					@endif

					<li>
						<figure class="pretty-figure">
							<a href="{{ Helpers::url($item->title, $item->id, 'news') }}"><img src="{{ $item->image }}" alt="{{ $item->title }}" class="img-responsive"></a>
							<figcaption><a href="{{ Helpers::url($item->title, $item->id, 'news') }}">{{ $item->title }}</a></figcaption>
						</figure>
					</li>
				@endforeach
			</ul>
		</div>
		<!-- /latest news-->

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
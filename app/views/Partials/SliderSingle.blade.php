<div class="jumbotron">
    <div id="slider" class="sl-slider-wrapper" data-bind="slider">

        <div class="sl-slider">
        
            @foreach ($slides as $slide)
                
                <div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
                    <div class="sl-slide-inner">
                        <div class="bg-img" style="background: url('{{ $slide->image }}')"><div class="overlay"></div></div>
                        
                        <section class="pull-left details-column">
                            <h2><a href="{{ url($slide->link) }}">{{ $slide->title }}</a></h2>
                            <p>{{ $slide->body }}</p>
                            <ul class="slider-details list-unstyled">
                                @if ($slide->director)
                                    <li><strong>{{ trans('main.directedBy') }}</strong>{{ $slide->director }}</li>
                                @endif
                                @if ($slide->stars)
                                    <li><strong>{{ trans('main.stars') }}</strong>{{ $slide->stars }}</li>
                                @endif
                                @if ($slide->genre)
                                    <li><strong>{{ trans('main.genre') }}</strong>{{ str_replace(' | ', ', ', $slide->genre) }}</li>
                                @endif
                                @if ($slide->release_date)
                                    <li><strong>{{ trans('main.release date') }}</strong>{{ $slide->release_date }}</li>
                                @endif
                            </ul>
                        </section>

                        @if ($slide->trailer)
                            <div id="trailer-box" class="pull-right trailer-column play" data-source="{{ $slide->trailer }}" data-poster="{{ $slide->trailer_image }}">
                                <img src="{{ $slide->trailer_image }}" class="img-responsive">
                                <div class="overlay"></div>
                                <div class="center"><img src="{{ asset('assets/images/play.png') }}"> </div>
                            </div>
                        @endif
                    </div>
                </div>

            @endforeach
            
            <nav id="nav-arrows" class="nav-arrows">
                <span class="nav-arrow-prev">{{ trans('main.prev') }}</span>
                <span class="nav-arrow-next">{{ trans('main.next') }}</span>
            </nav>  

            <nav id="nav-dots" class="nav-dots">
                @foreach ($slides as $k => $v)
                    @if ($k === 0)
                        <span class="nav-dot-current"></span>
                    @else
                        <span></span>
                    @endif
                @endforeach
            </nav>

        </div>
    </div>
</div>
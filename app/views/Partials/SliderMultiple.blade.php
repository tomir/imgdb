<div class="jumbotron hidden-xs">
    <div id="slider" class="sl-slider-wrapper" data-bind="slider">

        <div class="sl-slider">
        
            @foreach ($slides->chunk(6) as $chunk)
                
                 <div class="sl-slide" data-orientation="horizontal" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
                        <div class="sl-slide-inner">
                            <div class="bg-img" style="background: url('{{ $chunk->first()->image }}')"><div class="overlay"></div></div>
                            
                                <div class="container">

                                    <div class="row" id="big-search">
                                        <input style="background-image: url('{{ url('assets/images/search.png') }}')" type="text" name="search" class="form-control" placeholder="{{ trans('main.search placeholder') }}" autocomplete="off" data-bind="value: query, valueUpdate: 'keyup', hideOnBlur" name="q" type="search">

                                        <div class="autocomplete-container">
                                            <div class="arrow-up"></div>
                                            <section class="auto-heading">{{ trans('main.resultsFor') }} <span data-bind="text: query"></span></section>

                                            <section class="suggestions" data-bind="foreach: autocompleteResults">
                                                <div class="media">
                                                    <a class="pull-left col-sm-2" data-bind="attr: { href: vars.urls.baseUrl+'/'+vars.trans[type]+'/'+id+'-'+title.replace(/\s+/g, '-').toLowerCase() }">
                                                        <img class="media-object img-responsive" data-bind="attr: { src: poster, alt: title }">
                                                    </a>
                                                    <div class="media-body">
                                                        <a data-bind="attr: { href: vars.urls.baseUrl+'/'+vars.trans[type]+'/'+id+'-'+title.replace(/\s+/g, '-').toLowerCase() }"><h6 class="media-heading" data-bind="text: title"></h6></a>
                                                    </div>
                                                </div>
                                            </section>
                                        </div>
                                    </div>

                                    <div class="row hidden-xs">
                                         @foreach($chunk as $slide)
                                
                                            <figure class=" col-sm-3 col-md-2">
                                                <div class="play" data-source="{{ $slide->trailer }}" data-poster="{{ $slide->image }}">
                                                    <img class="img-responsive" src="{{ $slide->poster }}">
                                                    <div class="center"><img src="{{ asset('assets/images/play.png') }}"> </div>
                                                    <figcaption>{{ $slide->title }}</figcaption>
                                                </div>
                                                
                                            </figure>

                                        @endforeach
                                    </div>
                                </div>
                            
                        </div>
                    </div>

            @endforeach

                <nav id="nav-arrows" class="nav-arrows hidden-xs">
                    <span class="nav-arrow-prev">{{ trans('main.prev') }}</span>
                    <span class="nav-arrow-next">{{ trans('main.next') }}</span>
                </nav>  

            <nav id="nav-dots" class="nav-dots hidden-xs">
                @foreach ($slides->chunk(4) as $k => $v)
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
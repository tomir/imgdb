@extends('Main.Boilerplate')

@section('title')
    <title> {{ $title->title.' '.trans_choice('main.season', 1).' '.$season->number.', '.trans('main.episode').' '.$episode->episode_number. ' - ' .$options->getSiteName() }}</title>
@stop

@section('bodytag')
  <body id="title-page">
@stop

@section('content')

    <div class="container" id="content">
        <div class="row">
            <div class="col-sm-9">
                <div class="row">
                    @if ($hasReplace = Hooks::hasReplace('Episodes.Show.Jumbotron'))
                        {{ Hooks::renderReplace('Episodes.Show.Jumbotron', $links, 'links') }}
                    @endif

                    @if( ! $hasReplace || $links->isEmpty())
                        @if ($episode->promo)
                            <div id="trailer-mask" data-bind="click: showTrailer" data-src="{{ $episode->promo }}">
                                <img class="img-responsive img-thumbnail" src="{{ Helpers::original($episode->poster) }}">
                                <div class="center"><img class="img-responsive" src="{{ asset('assets/images/play.png') }}"> </div>
                            </div>
                            <video id="trailer" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="100%" height="500px"> </video>
                        @else
                             <div id="trailer-mask">
                                <img class="img-responsive img-thumbnail" src="{{ Helpers::original($episode->poster) }}">
                            </div>
                        @endif
                     @endif    
                </div>

                @if ($ad = $options->getTitleJumboAd())
                    <div id="ad">{{ $ad }}</div>
                @endif

                <section class="row" id="details-container">
                    <div class="col-md-2 visible-lg">
                        <img class="img-responsive" src="{{ $title->poster }}">
                    </div>
                    <div class="col-lg-10 clearfix" id="details">
                        <div class="col-md-8">
                            <h3>
                                <p class="episode-byline">
                                    <a href="{{ Helpers::url($title->title, $title->id, $title->type) }}">{{ $title->title }}</a>: 
                                    {{trans_choice('main.season', 1).' '.$season->number.', '.trans('main.episode').' '.$episode->episode_number }}
                                </p>
                                <a href="{{ Request::url() }}">
                                    {{ $episode->title }}
                                </a>
                            </h3>

                            <ul class="list-unstyled list-inline">
                                @foreach(explode('|', $title->genre) as $genre)

                                    <li><a href="{{ route(($title->type == 'series' ? $title->type : $title->type.'s').'.index').'?genre='.trim($genre) }}">{{ $genre }}</a></li>
                                @endforeach
                            </ul>

                            <div data-bind="moreLess">
                                <p>{{ $episode->plot }}</p>
                            </div>
                            
                            <div id="people">
                                @if ( ! $title->director->isEmpty())
                                    <strong>{{ trans('main.directors') }}:</strong>
                                    <ul class="list-unstyled list-inline">
                                        @foreach($title->director as $director)
                                            <li>{{ $director->name }}</li>
                                        @endforeach
                                    </ul><br>
                                @endif

                                @if ( ! $title->writer->isEmpty())
                                    <strong>{{ trans('main.writing') }}:</strong>
                                    <ul class="list-unstyled list-inline">
                                        @foreach($title->writer->slice(0, 3) as $writer)
                                            <li>{{ $writer->name }}</li>
                                        @endforeach
                                    </ul><br>
                                @endif

                                @if ( ! $title->actor->isEmpty())
                                    <strong>{{ trans('main.stars') }}:</strong>
                                    <ul class="list-unstyled list-inline">
                                        @foreach($title->actor->slice(0, 3) as $actor)
                                            <li><a href="{{ Helpers::url($actor->name, $actor->id, 'people') }}">{{ $actor->name }}</a></li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4" id="sub-details">
                        
                            @if ($episode->release_date)
                                <strong>{{ trans('main.release date') .': ' }}</strong><span>{{ $title->release_date }}</span><br>
                            @endif

                            @if ( ! $title->season->isEmpty())
                                <strong>{{ trans('main.seasons') }}: </strong>
                                @foreach($title->season as $s)
                                    <a href="{{ Helpers::season($title->title, $s) }}">{{ $s->number }}</a>
                                @endforeach
                                <br>
                            @endif
                            <div id="ratings">
                                @if ($title->mc_user_score)
                                    <strong class="raty">Metacritic: </strong>
                                    <span class="hidden-md" data-bind="raty: {{ $title->mc_user_score }}, stars: 5, readOnly: true"></span>
                                    <span class="raty-text">{{ $title->mc_user_score }}/10</span>
                                    <br>
                                @endif
                                
                                @if ($title->tmdb_rating)
                                    <strong class="raty">TMDb: </strong>
                                    <span class="hidden-md" style="padding-left: 32px" data-bind="raty: {{ $title->tmdb_rating }}, stars: 5, readOnly: true"></span>
                                    <span class="raty-text">{{ $title->tmdb_rating }}/10</span>
                                    <br>
                                @endif
                                
                                @if ($title->imdb_rating)
                                    <strong class="raty">IMDb: </strong>
                                    <span class="hidden-md" style="padding-left: 32px" data-bind="raty: {{ $title->imdb_rating }}, stars: 5, readOnly: true"></span>
                                    <span class="raty-text">{{ $title->imdb_rating }}/10</span>
                                @endif
                            </div>


                            <ul class="list-unstyled">
                                @if ($title->country)
                                    <li><strong>{{ trans('main.country') .': ' }}</strong><span>{{ $title->country }}</span></li>
                                @endif

                                @if ($title->language)
                                    <li><strong>{{ trans('dash.language') .': ' }}</strong><span>{{ $title->language }}</span></li>
                                @endif

                                @if ($title->runtime)
                                    <li><strong>{{ trans('main.runtime') .': ' }}</strong><span>{{ $title->runtime }}</span></li>
                                @endif

                                @if ($title->budget)
                                   <li><strong>{{ trans('main.budget') .': ' }}</strong><span>{{ $title->budget }}</span></li>
                                @endif

                                @if ($title->revenue)
                                    <li><strong>{{ trans('main.revenue') .': ' }}</strong><span>{{ $title->revenue }}</span></li>
                                @endif
                            </ul>
                        </div>

                    </div>
                </section>
            </div>
            <div class="col-sm-3" id="images-col">
                @if($title->image->count())
                    @foreach($title->image->slice(0, 4) as $img)
                        <img src="{{ $img->path }}" alt="{{ $img->title }}" class="img-responsive img-thumbnail">
                    @endforeach
                @endif
            </div>
        </div>
        <div class="row" id="episode-grid">
            <h2>{{ trans('main.otherEpsForSeason') }}</h2>
            @foreach($season->episode as $ep)
                @if ($ep->episode_number == $episode->episode_number)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <figure>
                            <img src="{{ $ep->poster }}" alt="{{ $ep->title }}" class="img-responsive">
                            <figcaption>
                                <span>{{ trans('main.episode').' '.$ep->episode_number.' - '. $ep->title }}</span>
                            </figcaption>
                        </figure>
                    </div>
                @else
                    <a href="{{ Helpers::episodeUrl($title->title, $title->id, $title->type, $season->number, $ep->episode_number) }}" class="col-sm-6 col-md-4 col-lg-3">
                        <figure>
                            <img src="{{ $ep->poster }}" alt="{{ $ep->title }}" class="img-responsive">
                            <figcaption>
                                <span>{{ trans('main.episode').' '.$ep->episode_number.' - '. str_limit($ep->title, 25) }}</span>
                            </figcaption>
                        </figure>
                    </a>
                @endif
            @endforeach
        </div>
        <div class="row">
            <div id="disqus_thread"></div>
        </div>
    </div>

    {{ Hooks::renderHtml('Titles.Show.BeforeScripts') }}

@stop

@section('scripts')

    <script>
        vars.title = <?php echo $title->toJson(); ?>;
        vars.disqus = '<?php echo $options->getDisqusShortname(); ?>';    
        vars.titleId = '<?php echo $title->id; ?>';
        vars.trailersPlayer = '<?php echo $options->trailersPlayer(); ?>';
        vars.userId = '<?php echo Sentry::getUser() ? Sentry::getUser()->id : false ?>';
        ko.applyBindings(app.viewModels.titles.show, $('#content')[0]);
        app.viewModels.titles.create.activeSeason('<?php echo $season->number ?>');
        app.viewModels.titles.create.activeEpisode('<?php echo $episode->episode_number ?>');
        app.viewModels.titles.show.start(<?php echo isset($links) && ! $links->isEmpty() ? $links->first()->toJson() : null; ?>);   
    </script>

    {{ Hooks::renderHtml('Titles.Show.AfterScripts') }}
@stop
 <section id="actions-row" class="row">
    <div id="social" class="col-xs-4">
        {{ HTML::socialLink('facebook') }}
        {{ HTML::socialLink('twitter', $title->title) }}
        {{ HTML::socialLink('google') }}
    </div>

    <div class="col-xs-4" id="status">
        @if ( ! $title->link->isEmpty())
            <span class="text-success"><a href="{{ ! $title->season->isEmpty() ? Helpers::season($title->title, $title->season->first()) : '#' }}">{{ trans('stream::main.availToStream') }}</a></span>
        @else
            <span class="text-danger">{{ trans('stream::main.notAvailToStream') }}</span>
        @endif
    </div>
    <div id="lists" class="col-xs-4">
        @if ($options->enableBuyNow())
            @if ($title->affiliate_link)
                <a href="{{ $title->affiliate_link }}" class="btn btn-primary"><i class="fa fa-dollar"></i> {{ trans('main.buy now') }}</a>
            @else
                <a href="{{ HTML::referralLink($title->title) }}" class="btn btn-primary"><i class="fa fa-dollar"></i> {{ trans('main.buy now') }}</a>
            @endif
        @endif

        @if (Sentry::getUser())
            <button class="btn btn-primary" id="watchlist" data-bind="click: handleLists.bind($data, 'watchlist')">
                <!-- ko if: watchlist -->
                <i class="fa fa-check-square-o"></i>
                <!-- /ko -->

                 <!-- ko ifnot: watchlist -->
                <i class="fa fa-square-o"></i>
                <!-- /ko -->

                {{ trans('users.watchlist') }}
            </button>
            <button class="btn btn-primary" id="favorite" data-bind="click: handleLists.bind($data, 'favorite')">
                <!-- ko if: favorite -->
                <i class="fa fa-check-square-o"></i>
                <!-- /ko -->

                 <!-- ko ifnot: favorite -->
                <i class="fa fa-square-o"></i>
                <!-- /ko -->

                {{ trans('main.favorite') }}
            </button>
        @else
            <a class="btn btn-primary" id="watchlist" href="{{ url('login') }}">{{ trans('users.watchlist') }}</a>
            <a class="btn btn-primary" id="favorite" href="{{ url('login') }}">{{ trans('main.favorite') }}</a>
        @endif
    </div>
</section>

@if ($title->type == 'movie')
    @include('Titles.Partials.Stream')
@else
    @if ($title->trailer)
        <div id="trailer-mask" data-bind="click: showTrailer" data-src="{{ $title->trailer }}">
            <img class="img-responsive img-thumbnail" src="{{ $title->background }}">
            <div class="center"><img class="img-responsive" src="{{ asset('assets/images/play.png') }}"> </div>
        </div>
        <video id="trailer" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="100%" height="500px"> </video>
    @else
         <div id="trailer-mask">
            <img class="img-responsive img-thumbnail" src="{{ $title->background }}">
        </div>
    @endif
@endif 
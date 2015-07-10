(function($) {
	'use strict'

    app.viewModels.titles.show.activeEpisode = ko.observable(1);

    app.viewModels.titles.show.showEpisodeModal = function(id) {
        var self = this,
            id   = parseInt(id);

        if (vars.links[id][0]) {
            self.activeEpisode(id);
            self.renderTab(vars.links[id][0].id, vars.links[id][0].url, vars.links[id][0].type, 700)
        }

        $('#stream-modal').modal('toggle');
    };

    /**
     * Render stream tab contents on click with appropriate contents.
     * 
     * @param  int id  
     * @param  string url 
     * @param  string type
     * 
     * @return void    
     */
    app.viewModels.titles.show.renderTab = function(id, url, type, iframeHeight) {
        var $contents = $('#videos .tab-content'),
            height = iframeHeight ? iframeHeight : 500;

        //handle tab buttons active state
        $('#videos .nav-tabs > li').removeClass('active');
        $('#'+id).addClass('active');

        if (this.player) {
            this.player.dispose();
        };

        //if it's an embed simply inject the url into an iframe
        if (type == 'embed') {
            $contents.html('<iframe width="100%" height="'+height+'px" frameborder="0" src="'+url+'" scrolling="no"></iframe>');

        //if it's a video we'll play it using video.js
        } else if (type == 'video') {

            //inject base video markup
            $contents.html('<video id="vidjs" class="video-js vjs-default-skin vjs-big-play-centered" controls preload="auto" width="100%" height="'+height+'px"> </video>');

            //use appropriate tech to play video depending if it's a youtube url or html video
            if (url.indexOf('youtube') != -1) {
                this.player = videojs('vidjs', { "techOrder": ["youtube"]}).src(url).play();
            } else {
                this.player = videojs('vidjs', { "techOrder": ["html5", "flash"]}).src(url).play();
            }   
        } else {
            $contents.html('<a href="'+url+'">'+url+'</a>');
        } 
    };

    /**
     * Send request to server to report a broken link.
     * 
     * @param  int/string id
     * @return void
     */
    app.viewModels.titles.show.report = function(id) {
        
        app.utils.ajax({
            url: vars.urls.baseUrl+'/links/report',
            data: ko.toJSON({ _token: vars.token, link_id: id }),

            success: function(data) {
                app.utils.noty(data, 'success');
            },
            error: function(data) {
                app.utils.noty(data.responseJSON, 'error');
            }
        });
    };

    app.viewModels.titles.show.showTrailer = function() {
        var $mask = $('#trailer-mask');

        if (vars.trailersPlayer == 'default') {
            $mask.html('<div class="embed-responsive embed-responsive-16by9"><iframe class="embed-responsive-item" src="'
                +$mask.data('src')+'?autoplay=1&iv_load_policy=3&modestbranding=1" wmode="opaque" allowfullscreen="true"></iframe></div>');
        } else {
             //set up either to play from youtube or mp4 file
            if ($mask.data('src').indexOf('youtube') != -1) {
                videojs('trailer', { "techOrder": ["youtube"]}).src($mask.data('src')).play();
            }
            else {
                videojs('trailer', { "techOrder": ["html5", "flash"]}).src($mask.data('src')).play();
            }

            $mask.css('display', 'none');
            $('#trailer').css('display', 'block');
        }
    },

    app.viewModels.titles.show.start = function(video) {
        var self = app.viewModels.titles.show;

        //render first link in first tab on page load
        if (video) {
            self.renderTab(video.id, video.url, video.type);
        }

        //see if user has already added this title to favorites or watchlist
        $.each(vars.lists, function(i,v) {
            if (v.title_id == vars.titleId && v.watchlist) {
                self.watchlist(true);
            }

            if (v.title_id == vars.titleId && v.favorite) {
                self.favorite(true);
            }
        });

        app.startGallery();

        var h = $('#details-container').height();
        $('#details-container .img-responsive').height(h);

        app.loadDisqus();
    };
    
})(jQuery);

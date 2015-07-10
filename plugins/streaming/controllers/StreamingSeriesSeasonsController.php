<?php

class StreamingSeriesSeasonsController extends SeriesSeasonsController
{
	public function __construct()
	{
        $season = App::make('Lib\Titles\SeasonRepository');
        $validator = App::make('Lib\Services\Validation\SeasonValidator');
        parent::__construct($season, $validator);
	}

    /**
     * Show episodes list for a season.
     * 
     * @param  string $title  series title
     * @param  int    $num    season number
     * @return View
     */
    public function show($title, $num)
    {
        if ($num == 0) App::abort(404);

        //fetch all seasons and episodes of series as we'll update all
        //of them at once if they need updating
        $title = $this->season->withSeasonsEpisodes($title);

        //if series doesn't have requested season we'll bail
        if (! isset($title['season'][$num - 1])) App::abort(404);

        //prepare the requested season for displaying
        $title = $this->season->prepareSingle($title, $num);

        try {
            $episodes = Helpers::extractSeason($title, $num)->episode;
        } catch (Exception $e) {
            App::abort(404);
        }
        
        $links = array();

        //create an array of links where key is the number of episode
        //and the value is an array of link models.       
        foreach ($title->link as $link)
        {
            if ((int) $link->season == (int) $num && $link->episode > 0)
            {
                $links[$link->episode][] = $link->toArray();
            }
        }

        return View::make('Titles.Seasons.StreamingShow')->withNum($num)->withTitle($title)->withEpisodes($episodes)->withLinks($links);
    }
}
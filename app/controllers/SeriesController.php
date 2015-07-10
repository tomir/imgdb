<?php

class SeriesController extends TitleController {

	/**
	 * Instantiate new series controller instance.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Displays the series main page.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$title = $this->title->byUri($id);

		try {
			$episodes = $this->title->getNextPrevEpisodes($title);
		} catch (Exception $e) {
			$episodes = new stdClass();
		}

		if ($this->title->needsScraping($title))
		{
			$title = $this->title->getCompleteTitle($title);
		}
	
		$lists = App::make('Lib\Repositories\User\UserRepositoryInterface')->getListsByTitleId($id);
			
		return View::make('Titles.Show')->withTitle($title)->withLists($lists)->withEpisodes($episodes);
	}

	/**
	 * Displays a grid of titles with pagination.
	 *
	 * @return View
	 */
	public function index()
	{
		$q = Request::query();

		//make index page crawlable by google
		if (isset($q['_escaped_fragment_']))
		{
			$series = Title::where('type', 'series')->paginate(16);

			return View::make('Titles.CrawlableIndex')->with('movies', $series)->with('type', 'series');
		}

		return View::make('Titles.Index')->withType('series');
	}

}
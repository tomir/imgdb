<?php

use Lib\Services\Scraping\Scraper;
use Lib\Repositories\Dashboard\DashboardRepositoryInterface as Dash;
use Lib\Services\Validation\DashboardValidator as Validator;

class DashboardController extends BaseController
{
	/**
	 * Scraper instance.
	 * 
	 * @var Lib\Services\Scraping\Scraper
	 */
	private $scraper;

	/**
	 * Validator instance.
	 * 
	 * @var Lib\Services\Validation\DashboardValidator
	 */
	private $validator;

	/**
	 * Dashboard repository instance.
	 * 
	 * @var Lib\Repositories\Dashboard\DashboardRepositoryInterface
	 */
	private $dashboard;

	/**
	 * Options instance.
	 * 
	 * @var Lib\Services\Options\Options
	 */
	private $options;

	public function __construct(Dash $dashboard, Validator $validator, Scraper $scraper)
	{
		//allow non-super users to view dashboard on demo environment
		if (App::environment() === 'demo')
		{
			$this->beforeFilter('logged', array('on' => 'post'));
			$this->beforeFilter('is.admin', array('on' => 'post'));
		} 
		else
		{
			$this->beforeFilter('logged');
			$this->beforeFilter('is.admin');
		}
	
		$this->beforeFilter('csrf', array('on' => 'post'));
		
		$this->scraper   = $scraper;		
		$this->dashboard = $dashboard;
		$this->validator = $validator;
		$this->options   = App::make('options');
	}

	public function index()
	{
		return View::make('Dashboard.Titles');
	}

	/**
	 * Media Page.
	 * 
	 * @return View
	 */
	public function media()
	{
		return View::make('Dashboard.Media');
	}

	/**
	 * Reviews Page.
	 * 
	 * @return View
	 */
	public function reviews()
	{
		return View::make('Dashboard.Reviews');
	}

	/**
	 * Categories Page.
	 * 
	 * @return View
	 */
	public function categories()
	{
		return View::make('Dashboard.Categories');
	}

	/**
	 * Settings page.
	 * 
	 * @return View
	 */
	public function settings()
	{
		return View::make('Dashboard.Settings')->withOptions(App::make('options'));
	}

	/**
	 * Users page.
	 * 
	 * @return View
	 */
	public function users()
	{
		return View::make('Dashboard.Users');
	}

	/**
	 * Slider page.
	 * 
	 * @return View
	 */
	public function slider()
	{
		$slides = App::make('Slide')->limit(10)->get();

		return View::make('Dashboard.Slider')->withSlides($slides);
	}

	/**
	 * Actors page.
	 * 
	 * @return View
	 */
	public function actors()
	{
		return View::make('Dashboard.Actors');
	}

	/**
	 * Ads page.
	 * 
	 * @return View
	 */
	public function ads()
	{
		return View::make('Dashboard.Ads');
	}

	/**
	 * News page.
	 * 
	 * @return View
	 */
	public function news()
	{
		return View::make('Dashboard.News');
	}

	/**
	 * Scraping Page.
	 * 
	 * @return View
	 */
	public function actions()
	{
		return View::make('Dashboard.Actions');
	}

	/**
	 * Generate a sitemap.
	 * 
	 * @return Redirect
	 */
	public function makeSiteMap()
	{
		App::make('Lib\Services\SitemapMaker')->make();

		return Redirect::back()->withSuccess('Sitemap generated successfully!');
	}

	/**
	 * Handle imdb advanced search scraping.
	 * 
	 * @return Redirect
	 */
	public function imdbAdvanced()
	{
		$input = Input::except('_token');

		if ( ! $this->validator->setRules('imdbScrape')->with($input)->passes())
		{
			return Redirect::back()->withErrors($this->validator->errors())->withInput($input);
		}

		if ( ! $amount = $this->scraper->imdbAdvanced($input) )
		{
			return Redirect::back()->withFailure( trans('dash.failed to scrape') );
		}

		return Redirect::back()->withSuccess( trans('dash.scraped successfully', array('number' => $amount - 1)) );	
	}

	/**
	 * Handle tmdb discover scraping.
	 * 
	 * @return Redirect
	 */
	public function tmdbDiscover()
	{
		$input = Input::except('_token');

		if ( ! $amount = $this->scraper->tmdbDiscover($input) )
		{
			return Redirect::back()->withFailure( trans('dash.failed to scrape') );
		}

		return Redirect::back()->withSuccess( trans('dash.scraped successfully', array('number' => $amount)) );	
	}

	/**
	 * Cleans all data in the app including
	 * database, cache and downloaded files.
	 * 
	 * @return Redirect
	 */
	public function truncate()
	{
		$this->dashboard->truncate();

		return Redirect::back()->withSuccess( trans('main.truncate success') );
	}

	/**
	 * Flush all cache.
	 * 
	 * @return Redirect
	 */
	public function clearCache()
	{
		Artisan::call('cache:clear');

		return Redirect::back()->withSuccess('Cleared Cache Successfully');
	}

	/**
	 * Truncates titles or actors with no images.
	 * 
	 * @return Redirect
	 */
	public function truncateNoPosters()
	{
		$table = Input::get('table');

		$this->dashboard->truncateWithParams($table);

		return Redirect::back()->withSuccess( trans('dash.delete success') );
	}

	/**
	 * Deletes titles by specified years.
	 * 
	 * @return Redirect
	 */
	public function truncateByYear()
	{
		$input = Input::all();

		if ( ! $input['from'] && ! $input['to'])
		{
			return Redirect::back()->withFailure( trans('dash.enter from or to') );
		}

		$this->dashboard->deleteByYear($input);

		return Redirect::back()->withSuccess( trans('dash.truncate no poster success') );
	}

	/**
	 * Stores updated options in database.
	 * 
	 * @return Redirect
	 */
	public function options()
	{
		$options = Input::except('_token', '_method');

		if ( ! $this->validator->setRules('options')->with($options)->passes())
		{
			return Redirect::back()->withErrors($this->validator->errors())->withInput($options);
		}

		$this->dashboard->updateOptions($options);

		return Redirect::back()->withSuccess( trans('dash.options update success') );
	}
}
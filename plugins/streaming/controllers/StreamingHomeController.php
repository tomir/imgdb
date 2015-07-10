<?php

use Carbon\Carbon;
use Lib\Services\Mail\Mailer;
use Lib\Services\Validation\ContactValidator;

class StreamingHomeController extends HomeController
{

	public function __construct()
	{
		$validator = App::make('Lib\Services\Validation\ContactValidator');
		$mailer = App::make('Lib\Services\Mail\Mailer');
		$renderer = App::make('Lib\Services\Rendering\HomepageRenderer');

		parent::__construct($validator, $mailer, $renderer);
	}

	/**
	 * Show homepage.
	 * 
	 * @return View
	 */
	public function index()
	{	
		return $this->renderer->render('Home.Home', 'Home.StreamingContent');	  
	}
}
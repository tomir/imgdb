<?php

use Lib\Lists\ListRepository;

class LinksController extends BaseController
{
	/**
	 * Link model instance.
	 * 
	 * @var Link
	 */
	private $model;

	public function __construct(Link $model)
	{
		$this->model = $model;
		$this->beforeFilter('csrf', array('on' => 'post'));
		$this->beforeFilter('logged');
		$this->beforeFilter('links:create', array('only' => 'attach'));
		$this->beforeFilter('links:delete', array('only' => array('detach', 'delete', 'deleteAll')));
	}

	/**
	 * Attach a link to a title.
	 * 
	 * @return Response
	 */
	public function attach()
	{
		$input = Input::except('_token');

		if ( ! isset($input['title_id']))
		{
			return Response::json(trans('dash.somethingWrong'), 500);
		}

		if (isset($input['id']))
		{
			//make sure we don't overwrite reports with 0
			if (isset($input['reports'])) unset($input['reports']);

			$this->model->where('id', $input['id'])->update($input);
		}
		else
		{
			$this->model->fill($input)->save();
		}
		
		return Response::json(trans('stream.attachedSuccess'), 201);
	}

	/**
	 * Detach link from specified title.
	 * 
	 * @return Response
	 */
	public function detach()
	{
		$input = Input::except('_token');

		if ( ! isset($input['title_id']))
		{
			return Response::json(trans('dash.somethingWrong'), 500);
		}

		$this->model->where('url', $input['url'])->where('title_id', $input['title_id'])->delete();

		return Response::json(trans('stream.detachSuccess'), 200);
	}

	public function report()
	{
		$ip = Request::getClientIp();
		$id = Input::get('link_id');

		//if this ip already reported this link we'll bail with error message
		if (DB::table('reports')->where('link_id', $id)->where('ip_address', $ip)->first())
		{
			return Response::json(trans('stream.reportFail'), 400);
		}

		//increment reports by 1
		$this->model->where('id', $id)->increment('reports');

		//make note that this ip reported this link already so reports are unique per ip address
		DB::table('reports')->insert(array('ip_address' => $ip, 'link_id' =>  $id));

		return Response::json(trans('stream.reportSuccess'), 200);
	}

	/**
	 * Paginate all the links in database.
	 * 
	 * @return JSON
	 */
	public function paginate()
	{
		$repo = App::make('Lib\Repository');
		$repo->model = $this->model;

		return $repo->paginate(Input::all());
	}

	/**
	 * Delete a link with given id from database.
	 * 
	 * @param  int/string $id
	 * @return Response
	 */
	public function delete($id)
	{
		$this->model->destroy($id);

		return Response::json(trans('stream.linkDelSuccess'), 200);
	}

	/**
	 * Delete links that have more reports then passed number.
	 * 
	 * @return Response
	 */
	public function deleteAll()
	{
		if (Input::get('number') && Input::get('number') !== 0)
		{
			$this->model->where('reports', '>=', Input::get('number'))->delete();

			//fire event manually so we can flush the cache on it
			Event::fire('eloquent.deleted: Link', array($this->model));

			return Response::json(trans('stream.linkDelSuccess'), 200);
		}	
	}

}
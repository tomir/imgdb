<?php

use Lib\Games\GameRepository;

class GameController extends BaseController {

    /**
     * Slide repository implementation.
     * 
     * @var Lib\Repositories\Slide\SlideRepositoryInterface
     */
    private $repo;

    /**
     * Create new Slide Controller instance.
     */
    public function __construct(GameRepository $repo)
    { 
    	$this->repo = $repo;
    }

    /**
     * Save new slide to database.
     * 
     * @return JSON
     */
    public function postAdd()
    {
        $input = Input::except('_token');

        $this->repo->save($input);

        return Response::json(trans('dash.gameSaveSuccess'), 201);
    }

    /**
     * Delete a slide from database.
     * 
     * @return JSON
     */
    public function postRemove()
    {
    	$input = Input::except('_token');
        
    	$this->repo->delete($input);

    	return Response::json(trans('dash.gameDeleteSuccess'), 200);
    }

}
<?php namespace Lib\Games;

use Game;
use Lib\Repository;

class GameRepository extends Repository
{
	/**
	 * Actor model instance.
	 * 
	 * @var Actor
	 */
	protected $model;

	/**
	 /**
	 * Slide Model instance.
	 * 
	 * @var Slide
	 */
	protected $model;

	/**
	 * Create new Slide repository instance.
	 * 
	 * @param Slide $model
	 */
	public function __construct(Game $model)
	{
		$this->model = $model;
	}

	/**
    * Returns most popular actors.
    * 
    * @param  int/string $limit
    * @return collection
    */
    public function popular($limit = 4)
    {
    	return $this->model->orderBy('views', 'desc')->limit($limit)->remember(1440, 'games.popular')->get();
    }
	
	/**
	 * Get the specified amount of slides.
	 * 
	 * @param  integer $limit
	 * @return Collection
	 */
	public function get($limit = 8)
    {
        return $this->model->limit($limit)->cacheTags('games')->remember(2000)->get();
    }

	/**
	 * Save new slide to database.
	 * 
	 * @param  array  $input
	 * @return integer
	 */
	public function save(array $input)
	{
		if (isset($input['id']))
		{
			return $this->update($input);
		}

		foreach ($input as $attr => $value)
		{
			$this->model->$attr = $value;
		}
	
		return $this->model->save();
	}

	/**
	 * Update a slide in database.
	 * 
	 * @param  array  $input
	 * @return boolean
	 */
	public function update(array $input)
	{
		$model = $this->model->find($input['id']);

		foreach ($input as $attr => $value)
		{
			$model->$attr = $value;
		}

		return $model->save();
	}

	/**
	 * Delete slide from database.
	 * 
	 * @param  array  $input
	 * @return boolean
	 */
	public function delete(array $input)
	{
		if (isset($input['id']))
		{
			return $this->model->destroy($input['id']);
		}	
	}

}
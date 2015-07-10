<?php namespace Plugins\Streaming\Lib\Categories;

use DB, App;
use Lib\Categories\CategoriesRepository;

class StreamingCategoriesRepository extends CategoriesRepository
{

	public function __construct()
	{
        $category = App::make('Category');

        parent::__construct($category);
	}

    /**
     * Return all categories.
     * 
     * @return Collection
     */
    public function all()
    {
        return $this->model->with(array('title.link', 'actor'))->orderBy('weight', 'desc')->get();
    }
}
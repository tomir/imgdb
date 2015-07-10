<?php namespace Lib\Categories;

use DB, App, Cache;
use Lib\Repository;

class CategoriesRepository extends Repository
{
	/**
	 * Category model instance.
	 * 
	 * @var Category
	 */
	protected $model;

    protected $queries = array(
        'popularTitles' => array('class' => 'Lib\Titles\TitleRepository', 'method' => 'mostPopular', 'type' => 'title'),
        'latestTitles'  => array('class' => 'Lib\Titles\TitleRepository', 'method' => 'newAndUpcoming', 'type' => 'title'),
        'popularActors' => array('class' => 'Lib\Actors\ActorRepository', 'method' => 'popular', 'type' => 'actor'),
        'topRatedTitles'=> array('class' => 'Lib\Titles\TitleRepository', 'method' => 'topRated', 'type' => 'title'),
    );

	public function __construct(\Category $category)
	{
        $this->model = $category;
	}

    /**
     * Return all categories.
     * 
     * @return Collection
     */
    public function all()
    {
        return $this->model->with(array('Title', 'Actor'))->orderBy('weight', 'desc')->get();
    }

    /**
     * Restrict paginate query by given params.
     *
     * @param  array $params
     * @param  Builder $query
     * @return Builder
     */
    protected function appendParams(array $params, $query)
    {
        //append all the params to query from base paginate method
        return parent::appendParams($params, $query)->with(array('Title', 'Actor'));
    }

    /**
     * Create new category or update existing one.
     * 
     * @param  array  $input
     * @return void
     */
    public function createOrUpdate(array $input)
    {
        $snakeInput = array();

        //convert all input keys to snake case
        foreach ($input as $k => $v)
        {
            $snakeInput[snake_case($k)] = $v;
        }
        
        //find a model if we get passed an id
        if (isset($snakeInput['id']))
        {
            $this->model = $this->model->find($snakeInput['id']);
        }

        $this->model->fill($snakeInput)->save();

        if ($this->model->auto_update)
        {
            $this->attachByQuery($this->model->id, $this->model->query, $this->model->limit);
        }

        Cache::forget('home.content');

        return true;
    }

    /**
     * Attach title or actor to category.
     * 
     * @param  array  $input
     * @return boolean
     */
    public function attach(array $input)
    {
        if (isset($input['titleId']) && isset($input['categoryId']))
        {   
            Cache::forget('home.content');
            
            try {
                $this->model->find($input['categoryId'])->{$input['type']}()->attach($input['titleId']);
                return true;
            } catch (Exception $e) {}
        }
    }

    /**
     * Detach title or actor from category.
     * 
     * @param  array  $input
     * @return boolean
     */
    public function detach(array $input)
    {
        if (isset($input['titleId']) && isset($input['categoryId']))
        {
            try {
                Cache::forget('home.content');     
                return $this->model->find($input['categoryId'])->{$input['type']}()->detach($input['titleId']);
            } catch (Exception $e) {}
        }
    }

    /**
     * Fetch titles via given query and attach
     * them to the given category.
     * 
     * @param  mixed   $id
     * @param  string  $query
     * @param  integer $limit
     * 
     * @return Category
     */
    public function attachByQuery($id, $query, $limit = 8)
    {
        if (isset($this->queries[$query]))
        {
            $this->detachAll($id);

            //get class name and method name
            extract($this->queries[$query]);

            //fetch titles via query method on the class
            $titles = App::make($class)->$method($limit);

            $sync = array();

            foreach ($titles->lists('id') as $key => $tid)
            {
                //$sync[$tid] = array('categorizable_type' => $type);
               $sync[] = $tid;
            }

            //attach fetched categorizable resources to the category
            $this->model->find($id)->$type()->sync($sync);

            Cache::forget('home.content');

            return $this->model->with(ucfirst($type))->find($id);
        }
    }

    /**
     * Detach all titles and actors from given category.
     * 
     * @param  mixed $id
     * @return void
     */
    public function detachAll($id)
    {
        $this->model->find($id)->title()->detach();
        $this->model->actor()->detach();
        Cache::forget('home.content');
    }

    /**
     * delete a category with given id.
     * 
     * @param  integer $id
     * @return boolean
     */
    public function delete($id)
    {
        if ($id)
        {   
            $this->model->destroy($id);
            DB::table('categorizables')->where('category_id', $id)->delete();
            Cache::forget('home.content');
            return true;
        }
    }
}
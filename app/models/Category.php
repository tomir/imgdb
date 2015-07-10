<?php

class Category extends Entity
{
	public $table = 'categories';

	protected $guarded = array('id');

	public function title()
    {
        return $this->morphedByMany('Title', 'categorizable');
    }

    public function actor()
    {
        return $this->morphedByMany('Actor', 'categorizable');
    }

    public function getResourceTypeAttribute()
    {
    	if ($this->query == 'popularActors')
    	{
    		return 'actor';
    	}
    	else
    	{
    		return 'title';
    	}
    }
}
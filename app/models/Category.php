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
	
	public function game()
    {
        return $this->morphedByMany('Game', 'categorizable');
    }

    public function getResourceTypeAttribute()
    {	
    	if ($this->query == 'popularActors')
    	{
    		return 'actor';
    	}
		elseif($this->query == 'topGames') {
			return 'game';
		}
    	else
    	{
    		return 'title';
    	}
    }
}
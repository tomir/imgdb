<?php namespace Plugins\Streaming\Models;

use Carbon\Carbon;

class Title extends \Title
{
    public function link()
    {
       return $this->hasMany('Link')->orderBy('reports', 'asc');
    }

    /**
     * Fetches title with relations by id.
     * 
     * @param  Illuminate\Database\Eloquent\Builder $query
     * @param  Int $id
     * @return collection
     */
    public function scopeById($query, $id)
    {
        return $query->with('Actor', 'Image', 'Director', 'Writer', 'Review', 'Season.Episode', 'Link')->findOrFail($id);
    }
}


<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//search
Route::get(Str::slug(trans('main.search')), 'SearchController@byQuery');
Route::get('typeahead/{query}', array('uses' => 'SearchController@typeAhead', 'as'   => 'typeahead'));
Route::post('populate-slider/{query}', 'SearchController@populateSlider');
Route::post('typeahead-actor/{query}', array('uses' => 'SearchController@castTypeAhead', 'as'   => 'typeahead-cast'));

//homepage and footer
Route::get('/', array('uses' => 'HomeController@index', 'as' => 'home'));
Route::get(Str::slug(trans('main.privacyUrl')), array('uses' => 'HomeController@privacy', 'as' => 'privacy'));
Route::get(Str::slug(trans('main.tosUrl')), array('uses' => 'HomeController@tos', 'as' => 'tos'));
Route::get(Str::slug(trans('main.contactUrl')), array('uses' => 'HomeController@contact', 'as' => 'contact'));
Route::post(Str::slug(trans('main.contactUrl')), array('uses' => 'HomeController@submitContact', 'as' => 'submit.contact'));

//news
Route::get('news/paginate', 'NewsController@paginate');
Route::resource(Str::slug(trans('main.news')), 'NewsController');
Route::post('news/external', array('uses' => 'NewsController@updateFromExternal', 'as' => 'news.ext'));

//movies/series 
Route::get('titles/paginate', 'TitleController@paginate');
Route::resource(
    Str::slug(trans('main.series')), 
    'SeriesController',
    array('names' => array('show' => 'series.show', 'index' => 'series.index', 'store' => 'series.store', 'edit' => 'series.edit', 'destroy' => 'series.destroy', 'create' => 'series.create'), 'except' => array('update'))
);
Route::resource(
    Str::slug(trans('main.movies')), 
    'MoviesController',
    array('names' => array('show' => 'movies.show', 'index' => 'movies.index', 'store' => 'movies.store', 'edit' => 'movies.edit', 'destroy' => 'movies.destroy', 'create' => 'movies.create'), 'except' => array('update'))
);
Route::post('detach-people', 'TitleController@detachPeople');

//seasons/episodes
Route::resource(Str::slug(trans('main.series')) . '.seasons', 'SeriesSeasonsController', array('except' => array('index', 'edit')));
Route::resource(Str::slug(trans('main.series')) . '/{seriesid}/seasons/{seasonid}/episodes', 'SeasonsEpisodesController', array('except' => array('index', 'create')));

//reviews
Route::resource(Str::slug(trans('main.series')) . '.reviews', 'ReviewController', array('only' => array('store', 'destroy')));
Route::resource(Str::slug(trans('main.movies')) . '.reviews', 'ReviewController', array('only' => array('store', 'destroy')));
Route::post(Str::slug(trans('main.series')) . '/{title}/reviews', 'ReviewController@store');
Route::post(Str::slug(trans('main.movies')) . '/{title}/reviews', 'ReviewController@store');
Route::get('reviews/paginate', 'ReviewController@paginate');

//people
Route::get('people/paginate', 'ActorController@paginate');
Route::resource(Str::slug(trans('main.people')), 'ActorController');
Route::post('people/unlink', array('uses' => 'ActorController@unlinkTitle', 'as' => 'people.unlink'));
Route::post('people/knownFor', array('uses' => 'ActorController@knownFor', 'as' => 'people.knownFor'));

//games
Route::resource(Str::slug(trans('main.games')), 'GameController');

//users
Route::get('users/paginate', 'UserController@paginate');

Route::resource(
    Str::slug(trans('main.users')),
    'UserController', array(
        'except' => array('index'),
        'names' => array('show' => 'users.show', 'store' => 'users.store', 'edit' => 'users.edit', 'destroy' => 'users.destroy', 'create' => 'users.create')
    )
);

Route::get(Str::slug(trans('main.users')) . '/{id}/settings', array('uses' => 'UserController@edit', 'as' => 'settings'));
Route::get(Str::slug(trans('main.users')) . '/{username}/change-password', array('uses' => 'UserController@changePassword', 'as' => 'changePass'));
Route::post(Str::slug(trans('main.users')) . '/{username}/change-password', array('uses' => 'UserController@storeNewPass', 'as' => 'users.storeNewPass'));
Route::post(Str::slug(trans('main.users')) . '/{username}/avatar', array('uses' => 'UserController@avatar', 'as' => 'users.avatar'));
Route::post(Str::slug(trans('main.users')) . '/{username}/bg', array('uses' => 'UserController@background', 'as' => 'users.bg'));
Route::post('users/create-new', array('uses' => 'UserController@createNew', 'as' => 'users.createNew'));

//login/logout 
Route::get(Str::slug(trans('main.login')), 'SessionController@create');
Route::get(Str::slug(trans('main.logout')), 'SessionController@logOut');
Route::get(Str::slug(trans('main.register')), 'UserController@create');
Route::resource('sessions', 'SessionController', array('only' => array('create', 'store')));
Route::get('forgot-password', 'UserController@requestPassReset');
Route::post('forgot-password', 'UserController@sendPasswordReset');
Route::get('reset-password/{code}', 'UserController@resetPassword');
Route::get('activate/{id}/{code}', 'UserController@activate');


//dashboard
//Route::controller('dashboard', 'DashboardController');
Route::group(array('prefix' => 'dashboard'), function()
{
    Route::get('/', 'DashboardController@index');
    Route::get('media', 'DashboardController@media');
    Route::get('settings', 'DashboardController@settings');
    Route::get('users', 'DashboardController@users');
    Route::get('slider', 'DashboardController@slider');
    Route::get('actors', 'DashboardController@actors');
    Route::get('ads', 'DashboardController@ads');
    Route::get('reviews', 'DashboardController@reviews');
    Route::get('news', 'DashboardController@news');
    Route::get('actions', 'DashboardController@actions');
    Route::get('categories', 'DashboardController@categories');
    Route::post('make-site-map', 'DashboardController@makeSiteMap');
    Route::post('imdb-advanced', 'DashboardController@imdbAdvanced');
    Route::post('tmdb-discover', 'DashboardController@tmdbDiscover');
    Route::post('truncate', 'DashboardController@truncate');
    Route::post('clear-cache', 'DashboardController@clearCache');
    Route::post('truncate-no-posters', 'DashboardController@truncateNoPosters');
    Route::post('truncate-by-year', 'DashboardController@truncateByYear');
    Route::post('options', 'DashboardController@options');
});

Route::get('dashboard/links', 'DashboardController@getLinks');

//lists(watchlist/favorites)
Route::controller('lists', 'ListsController');

Route::resource('categories', 'CategoriesController', array('only' => array('store', 'destroy')));
Route::post('categories/attach', 'CategoriesController@attach');
Route::post('categories/detach', 'CategoriesController@detach');
Route::get('categories/paginate', 'CategoriesController@paginate');

Route::controller('install', 'InstallController');

//updates
Route::get('update', 'UpdateController@update');
Route::post('update-schema',array('uses' => 'UpdateController@updateSchema', 'as' => 'update.schema'));

//internal
Route::group(array('prefix' => 'private'), function()
{
    Route::post('update-reviews', 'TitleController@updateReviews');
    Route::post('scrape-fully', array('uses' => 'TitleController@scrapeFully', 'as' => 'titles.scrapeFully'));
    Route::post('update-playing', array('uses' => 'TitleController@updatePlaying', 'as' => 'titles.updatePlaying'));
});

//groups
Route::get('GroupController@clear', array('before' => 'is.admin','uses' => 'GroupController@clear'));
Route::resource('groups', 'GroupController', array('only' => array('store', 'destroy')));

Route::get('social/{provider?}', array("as" => "hybridauth", 'uses' => 'SessionController@social'));
Route::post('social/twitter/email', 'SessionController@twitterEmail');

//RSS
Route::group(array('prefix' => Str::slug(trans('feed.feed'))), function()
{
    Route::get(Str::slug(trans('main.newAndUpcoming')), array('uses' => 'RssController@movies', 'as' => 'feed.theaters'));
    Route::get(Str::slug(trans('feed.newsUrl')), array('uses' => 'RssController@news', 'as' => 'feed.news'));
});

//media
Route::get('media/paginate', array('uses' => 'MediaController@paginate', 'as' => 'media.paginate'));
Route::resource('media', 'MediaController', array('only' => array('store', 'destroy')));

Route::controller('slides', 'SlideController');
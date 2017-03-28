<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

/* PUBLIC GETS */
Route::get('/', array('as'=>'home', 'uses'=>'stance@all_stances'));
Route::get('register', array('as'=>'register', 'uses'=>'user@register'));
Route::get('login', array('as'=>'login', 'uses'=>'user@login'));
Route::get('profile/view/(:num)', array('as'=>'profile_view', 'uses'=>'user@profile_view'));
Route::get('stance/view/(:num)', array('as'=>'stance_view', 'uses'=>'stance@view'));
Route::get('stances/your', array('as'=>'your_stances', 'uses'=>'stance@your_stances'));
Route::get('stances/all', array('as'=>'all_stances', 'uses'=>'stance@all_stances'));
Route::get('test', array('as'=>'test', 'uses'=>'test@testfun'));

/* PUBLIC POSTS */
Route::post('register', array('before'=>'csrf', 'uses'=>'user@register'));
Route::post('login', array('before'=>'csrf', 'uses'=>'user@login'));
Route::post('filter_stances', array('before'=>'csrf', 'uses'=>'stance@filter'));

/* AJAX ROUTES */
Route::post('regions', array('uses'=>'region@query'));
Route::post('stancetags', array('uses'=>'tag@query_stancetags'));

/* PRIVATE GETS */
Route::group(array('before'=>'auth'), function() //this line calls the auth filter before the route is called.
{
	Route::get('logout', array('as'=>'logout', 'uses'=>'user@logout'));
	Route::get('profile/update', array('as'=>'profile_update', 'uses'=>'user@profile_update'));
	Route::get('stance/add', array('as'=>'add_stance', 'uses'=>'stance@add'));	
	Route::get('stance/(:num)/comment', array('as'=>'add_comment', 'uses'=>'comment@add'));
	Route::get('comment/(:num)/reply', array('as'=>'add_comment_reply', 'uses'=>'comment@reply'));
});

/* PRIVATE POSTS */
Route::group(array('before'=>'auth|csrf'), function() //this line calls the auth filter before the route is called.
{
	Route::post('profile_update', array('uses'=>'user@profile_update'));
	Route::post('add_stance', array('uses'=>'stance@add'));
	Route::post('endorse_stance', array('uses'=>'stance@endorse'));
	Route::post('protest_stance', array('uses'=>'stance@protest'));
	Route::post('add_comment', array('uses'=>'comment@add'));
	Route::post('add_comment_reply', array('uses'=>'comment@reply'));
	Route::post('upvote_comment', array('uses'=>'comment@upvote'));
});

/* AJAX test code */
Route::get('content', function() {
	return View::make('content');
});
Route::post('content', function() {
	//deal with the post data...
	// echo Input::get('name');
	// echo Input::get('age');
	
	echo serialize(Region::get_list());
});


/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application. The exception object
| that is captured during execution is then passed to the 500 listener.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function($exception)
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Route::get('/', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) {
		return Redirect::to('login')
		->with('message',"You must be logged in to do that!");
	}
});
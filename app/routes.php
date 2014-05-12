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

/* 
 * Admin filter:
 */ 
Route::when('admin/*', 'admin');

/*
 * Frontend dashboard.
 */ 
Route::get('/', ['as' => 'home', 'uses' => 'App\Modules\News\Controllers\NewsController@showOverview']);

/*
 * Backend dashboard.
 * We prefer to use a route here instead of inside the modules' own routing file.
 * So there can't exist multiple modules that try to declare themselves as dashboard.
 * (Well, ofcourse they may try to... since routing is global. But they should not.)
 */ 
Route::get('admin', [
    'as' => 'admin.dashboard', 
    'before' => 'admin', 
    'uses' => 'App\Modules\Dashboard\Controllers\AdminDashboardController@getindex'
]);

/*
 * Comment component
 */
Route::post('comments/store', ['as' => 'comments.store', 'before' => 'csrf', 'uses' => function()
{
    $foreignType = Input::get('foreigntype');
    $foreignId = Input::get('foreignid');
    return Comments::store($foreignType, $foreignId);
}]);

Route::get('comments/{id}', function($id)
{
    return Comments::get($id);
});

Route::get('comments/{id}/edit', ['as' => 'comments.edit', 'uses' => function($id)
{
    return Comments::edit($id);
}]);

Route::put('comments/{id}/update', ['as' => 'comments.update', 'before' => 'csrf', 'uses' => function($id)
{
    return Comments::update($id);
}]);

Route::delete('comments/{id}/delete', ['as' => 'comments.delete', 'before' => 'csrf', 'uses' => function($id)
{
    return Comments::delete($id);
}]);

/*
 * Captcha
 */
Route::get('captcha', ['as' => 'captcha', 'uses' => function()
{
    Captcha::make();
    $response = Response::make('', 200);

    $response->header('Content-Type', 'image/jpg');

    return $response;
}]);

/*
 * Installation
 */
Route::get('install', 'InstallController@index');
Route::post('install', 'InstallController@index');

/*
 * Testing
 */
Route::get('test', function()
{

    //$news = App\Modules\News\Models\News::find(3);
    $News = 'App\Modules\News\Models\News';
    $news = $News::query();
    $news->whereId(3);
    dd($news);
    $news = $news->get();
    
    //dd($news->created_at->dateTime());

    //$fg = new FormGenerator();
    //return '<pre>'.$fg->generate('pages').'</pre>';
    
   // $text = 'Hallo [b]Welt![/b]! Schau mal bei [url=http://google.de]Google[/url] vorbei!';
    $text = 'Hallo [b]Welt![/b]! Schau mal bei [url]http://google.de[/url] vorbei und schau dir das Bild an: ';
    //$text = '[url=http://www.google.de/][img]http://www.golem.de/1305/99093-45283-i_rc.jpg[/img][/url]';
    //$text = '[url][img]http://www.golem.de/1305/99093-45283-i_rc.jpg[/img][/url]';
    //$text = '[list][*]Punkt 1[*]Punkt 2[*]Punkt 3[/list]';
    //$text = '[quote=Schalke-Fan][quote=BVB Fan]Ich bin ein BVB Fan![/quote]Ich bin eher Schalke Fan![/quote]Und ich bin Bayern Fan!';
    //$text = 'Ich [u]finde [font=Impact]es[/font] [center][b]wirklich[/b][/center] nicht gut[/u] wie [color=blue]wir[/color] [size=30][i]hier[/i][/size] behandelt [s]wurden[/s] werden.';
    //$text = 'Email: [email]max@mustermann.de[/email] und an [email=maria@musterfrau.com]meine Frau[/email].';
    //$text = '[quote="Mr. Array[]"]There is no spoon![/quote]';
    $text = '[youtube]0L_55kFSN9o[/youtube]';
    //$text = '[list][li]Punkt 1[/li][li]Punkt 2[/li][li]Punkt 3[/li][/list]';

    $bbcode = new BBCode();
    $res = $bbcode->render($text);

    return '<html><head><style>blockquote { background-color: #EEE; border: 1px dotted silver; padding: 5px }</style></head><body>'.
        $res.'<hr><pre>'.htmlentities($res).'</pre></body></html>';
});
<?php
/**
 * User: zain
 * Date: 2017/2/7
 * Time: 13:40
 */

use Zain\Router;

Router::get('','ArticleController@all');
Router::get('/search','ArticleController@search');
Router::get('/article/(:num)','ArticleController@find');
Router::get('/sort/(:num)','ArticleController@sort');
Router::post('/comment/(:num)','ArticleController@comment');
Router::get('admin','AdminController@index');
Router::get('/admin/articles','AdminController@articles');
Router::get('/admin/articles/(:num)','AdminController@articles');
Router::get('/admin/write','AdminController@write');
Router::get('/admin/article/(:num)','AdminController@article');
Router::post('/admin/articleDelete','AdminController@articleDelete');
Router::get('/admin/sort','AdminController@sort');
Router::get('/admin/sort/(:num)','AdminController@sort');
Router::get('/admin/link','AdminController@link');
Router::get('/admin/link/(:num)','AdminController@link');
Router::get('/admin/comment','AdminController@comment');
Router::get('/admin/setting','AdminController@setting');
Router::get('/admin/personal','AdminController@personal');
Router::get('/login','LoginController@index');
Router::get('/logout','LogoutController@index');
Router::post('/admin/setting','AdminController@changeSetting');
Router::post('/admin/personal','AdminController@changePersonal');


Router::post('/login','LoginController@login');
Router::post('/admin/write','AdminController@write');
Router::post('/admin/article/(:num)','AdminController@article');

Router::post('/admin/search','AdminController@search');

Router::post('/admin/sort','AdminController@sort');
Router::post('/admin/sort/(:num)','AdminController@sort');
Router::post('/admin/sortDelete','AdminController@sortDelete');
Router::post('/admin/changeSort/(:num)','AdminController@changeSort');
Router::post('/admin/sortReOrder','AdminController@sortReOrder');

Router::post('/admin/commentDelete','AdminController@commentDelete');

Router::post('/admin/link','AdminController@link');
Router::post('/admin/link/(:num)','AdminController@link');
Router::post('/admin/linkHide','AdminController@linkHide');
Router::post('/admin/linkDelete','AdminController@linkDelete');
Router::post('/admin/linkReOrder','AdminController@linkReOrder');

Router::error(function(){
    zView('errors.404');
});
Router::dispatch();
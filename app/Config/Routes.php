<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Page::home');

$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('article', 'article::admin_index');
    $routes->add('article/add', 'article::add');
    $routes->add('article/edit/(:any)', 'article::edit/$1');
    $routes->get('article/delete/(:any)', 'article::delete/$1');
    });
$routes->get('/article', 'Article::index');
$routes->get('/article/(:segment)', 'Article::view/$1'); // Dynamic slug

$routes->get('/about', 'Page::about');
$routes->get('/contact', 'Page::contact');
$routes->get('/faqs', 'Page::faqs');
$routes->get('/tos', 'Page::tos');

$routes->get('user/login', 'User::login');
$routes->post('user/login', 'User::login');
$routes->get('user/logout', 'User::logout');

$routes->resource('api/articles', [
    'controller' => 'ArticleApi',
    'placeholder' => 'id',
    
]);

// RESTful API manual routes for ArticleAPI
$routes->get('api/articles', 'ArticleApi::index');
$routes->post('api/articles', 'ArticleApi::create');
$routes->get('api/articles/(:num)', 'ArticleApi::show/$1');
$routes->put('api/articles/(:num)', 'ArticleApi::update/$1');
$routes->patch('api/articles/(:num)', 'ArticleApi::update/$1');
$routes->delete('api/articles/(:num)', 'ArticleApi::delete/$1');


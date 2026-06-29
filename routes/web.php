<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1'], function () use ($router) {
    $router->group(['prefix' => 'user'], function () use ($router) {
        $router->post('register', 'V1\UserController@register');
        $router->post('login', 'V1\UserController@login');
	$router->post('forgotpassword', 'V1\AuthController@forgotPassword');
 	$router->post('accountdeletion', 'V1\AuthController@accountDeletion');
        $router->post('dashboard', 'V1\DashboardController@index');
        $router->post('cmsPage', 'V1\CmsPageController@index');
        $router->post('contact', 'V1\ContactController@submit');
	$router->post('clubs', 'V1\ClubsController@index');
	$router->post('clubsmember', 'V1\ClubsController@membership');
	$router->post('clubsubmit', 'V1\JoinClubsController@submit');
        $router->post('participantsubmit', 'V1\EventParticipantsController@submit');
	$router->post('qrscan', 'V1\EventParticipantsController@qrscan');
	$router->post('attendance', 'V1\EventParticipantsController@attendance');
    	$router->post('membershiptype', 'V1\EventParticipantsController@membershiptype');
	$router->post('upload', 'V1\EventParticipantsController@upload');
	$router->post('banner', 'V1\BannerController@index');
        $router->post('sponsor', 'V1\SponsorController@index');
        $router->post('sponsorid', 'V1\SponsorController@sponsorid');
        $router->post('sponsorevent', 'V1\SponsorController@sponsorevent');

        $router->group(['middleware' => ['jwt.verify']], function () use ($router) {
            $router->post('show', 'V1\UserController@show');
            $router->post('update', 'V1\UserController@update');
            $router->post('uploadProfileImage', 'V1\UserController@uploadProfileImage');
            $router->post('memberList', 'V1\UserController@memberList');
	    $router->post('changepassword', 'V1\UserController@changepassword');	
        });
    });

    $router->post('device-access/verify', 'V1\DeviceAccessController@verify');
});

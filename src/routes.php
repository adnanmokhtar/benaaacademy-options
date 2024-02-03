<?php

use Benaaacademy\Options\Facades\Option;

/*
 * WEB
 */
Route::group([
    "prefix" => ADMIN,
    "middleware" => ["web", "auth:backend", "can:options.manage"],
    "namespace" => "Benaaacademy\\Options\\Controllers"
], function ($route) {
    $route->group(["prefix" => "options"], function ($route) {


        foreach (Option::pages() as $page) {
            $route->any('/{page?}', ["as" => "admin.options", "uses" => "OptionsController@index"]);
        }
    });
});

/*
 * API
 */
Route::group([
    "prefix" => API,
    "middleware" => ["auth:api"],
    "namespace" => "Benaaacademy\\Options\\Controllers"
], function ($route) {
    $route->get("/options/show", "OptionsApiController@show");
    $route->post("/options/create", "OptionsApiController@create");
    $route->post("/options/update", "OptionsApiController@update");
    $route->post("/options/destroy", "OptionsApiController@destroy");
});



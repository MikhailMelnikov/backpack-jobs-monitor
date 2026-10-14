<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | The pages live under {backpack.base.route_prefix}/{route_prefix}:
    | admin/jobs/schedule and admin/jobs/queue by default. Set setup_routes to
    | false to register your own routes to the add-on controllers.
    |
    */

    'setup_routes' => true,

    'route_prefix' => 'jobs',

    /*
    |--------------------------------------------------------------------------
    | Extra middleware
    |--------------------------------------------------------------------------
    |
    | Added on top of Backpack's `web` + admin middleware. Running tasks and
    | flushing failed jobs are powerful actions: restrict them to admins with a
    | role/permission middleware if not every Backpack user should have them.
    |
    */

    'middleware' => [],

];

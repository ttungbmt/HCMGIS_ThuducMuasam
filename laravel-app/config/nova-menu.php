<?php


return [
    /*
    |------------------|
    | Required options |
    |------------------|
    */


    /*
    |--------------------------------------------------------------------------
    | Table names
    |--------------------------------------------------------------------------
    */

    'menus_table_name' => 'nova_menu_menus',
    'menu_items_table_name' => 'nova_menu_menu_items',


    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | Set all the available locales as either [key => name] pairs, a closure
    | or a callable (ie 'locales' => 'nova_get_locales').
    |
    */

    'locales' => [
        'en_US' => 'English'
    ],


    /*
    |--------------------------------------------------------------------------
    | Menus
    |--------------------------------------------------------------------------
    |
    | Set all the possible menus in a keyed array of arrays with the options
    | 'name' and optionally 'menu_item_types' and unique.
    /  Unique is true by default
    |
    | For example: ['header' => ['name' => 'Header', 'unique' => true, 'menu_item_types' => []]]
    |
    */

    'menus' => [
         'admin' => [
             'name' => 'Admin',
             'unique' => true,
             'menu_item_types' => [
                 \Larabase\Nova\MenuItemTypes\ResourceGroup::class,
                 \Larabase\Nova\MenuItemTypes\Resource::class,
                 \Larabase\Nova\MenuItemTypes\InternalUrl::class,
                 \Larabase\Nova\MenuItemTypes\Tool::class,
             ]
         ],
        'map' => [
            'name' => 'Map',
            'unique' => false,
            'menu_item_types' => [
                \Larabase\Nova\MenuItemTypes\LayerGroup::class,
                \Larabase\Nova\MenuItemTypes\Layer::class,
            ]
        ]
    ],


    /*
    |--------------------------------------------------------------------------
    | Menu item types
    |--------------------------------------------------------------------------
    |
    | Set all the custom menu item types in an array.
    |
    */

    'menu_item_types' => [
//        \Larabase\Nova\MenuItemTypes\StaticURL::class
    ],


    /*
    |--------------------------------|
    | Optional configuration options |
    |--------------------------------|
    */


    /*
    |--------------------------------------------------------------------------
    | Resource
    |--------------------------------------------------------------------------
    |
    | Optionally override the original Menu resource.
    |
    */

    'resource' => OptimistDigital\MenuBuilder\Nova\Resources\MenuResource::class,


    /*
    |--------------------------------------------------------------------------
    | Menu Model
    |--------------------------------------------------------------------------
    |
    | Optionally override the original Menu model.
    |
    */

    'menu_model' => OptimistDigital\MenuBuilder\Models\Menu::class,


    /*
    |--------------------------------------------------------------------------
    | MenuItem Model
    |--------------------------------------------------------------------------
    |
    | Optionally override the original Menu Item model.
    |
    */

    'menu_item_model' => OptimistDigital\MenuBuilder\Models\MenuItem::class,


    /*
    |--------------------------------------------------------------------------
    | Auto-load migrations
    |--------------------------------------------------------------------------
    |
    | Allow auto-loading of migrations (without the need to publish them)
    |
    */

    'auto_load_migrations' => true,
];

<?php

use App\Models\Order;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('index');
});

Route::get('/locations-served', function () {
    return view('locations');
});

Route::get('/our-services', function () {
    return view('services');
});

Route::get('/contact', function () {
    return view('contact');
});

Route::get('/terms', function () {
    return view('terms');
});

Route::post('/contact',  'HomeController@contact');


Auth::routes();
Route::post('/register', 'Auth\RegisterController@store');

Route::get('/account/inactive', function () {
    return view('auth.approve');
})->middleware(['auth', 'verified']);

require_once 'email_verify_routes.php';

Route::group(['middleware' => ['auth', 'verified'/*, 'approved'*/]], function () {
    Route::get('/dashboard', 'HomeController@index')->name('dashboard');
    Route::resource('regions', 'RegionController')->middleware(['can:manageLocations, App\Models\User']);

    //order routes
    Route::post('/install/post', 'OrderController@store')->name('install_post');
    Route::group(['as' => 'orders.'], function () {
        Route::get('/get/order/{order}', "OrderController@show")->name("get.order");
        Route::get('/order/{order}/cancel', 'OrderController@cancel');
        Route::get('/order/delete/file/{file}', "OrderController@deleteFile")->name("file.remove");
        Route::get('/order/{order}/mark-completed', 'OrderController@markCompleted');
        Route::post('/order/delete/all', 'OrderController@deleteAll');
        Route::get('/order/email/{order}', 'OrderController@sendEmail');
    });

    //Repair orders
    Route::prefix('repair')->group(function () {
        Route::get('/', 'OrderController@loadRepairPage');
        Route::get('/orders/datatable', 'OrderController@repairOrdersDatatable');
        Route::get('/get/order/{order}', 'OrderController@getOrderForRepairModal');
        Route::get('/get/zone/{order}', 'OrderController@getRepairZone');
    });

    //payments
    Route::group(['as' => 'payments.'], function () {
        Route::get('/payment', "PaymentController@index");
        Route::post('/payment/pay', "PaymentController@pay")->name("pay");
        Route::get('payment/get-saved-cards/{user}', 'PaymentController@getSavedCards');
    });

    //====
    Route::get('/post/{post}/access/set/all/office/access/{access}', "PostController@toggleAcessAll");
    Route::get('/accessory/{accessory}/access/set/all/office/access/{access}', "AccessoryController@toggleAcessAll");

    //main group
    Route::group(['middleware' => 'can:manageOffices, App\Models\User'], function () {
        //users
        Route::resource('users', 'UserController');
        //related to users
        Route::resource('offices', 'OfficeController');
        Route::resource('agents', 'AgentController');
        //Office group
        Route::group(['as' => 'offices.'], function () {
            Route::get('/get/office/{office}', 'OfficeController@getOffice');
            Route::get('office/{office}/reset/password', 'OfficeController@resetPassword')->name('reset.password');
            Route::post("/offices/delete/all", "OfficeController@destroyAll")->name('destroy.all');
            Route::post("/office/{office}/update/payment/method", "OfficeController@updatePaymentMethod")->name('update.payment.method');
            Route::post("/office/{office}/create/note", "OfficeController@storeNote")->name('note.store');
            Route::get("/office/{office}/agents", "OfficeController@agents")->name('get.agents');
            Route::get("/office/{office}/agents/json", "OfficeController@agentsJson")->name('get.agents.json');
            Route::get("/office/{office}/agents/order/by/name/json", "OfficeController@agentsJsonOrderByName")->name('get.agents.order.by.name.json');
            Route::get('/get/office/{office}/posts', 'OfficeController@getOfficePosts');
            Route::get('/get/office/{office}/accessories', "OfficeController@getOfficeAccessories");
            Route::get('/get/office/{office}/panels', "OfficeController@getOfficePanels");
        });

        //Agent group
        Route::group(['as' => 'agents.'], function () {
            Route::get('agent/{agent}/reset/password', 'AgentController@resetPassword')->name('reset.password');
            Route::get('/get/agent/{agent}', 'AgentController@getAgent');
            Route::post("/agents/delete/all", "AgentController@destroyAll")->name('destroy.all');
            Route::post("/agent/{agent}/update/payment/method", "AgentController@updatePaymentMethod")->name('update.payment.method');
            Route::post("/agent/{agent}/create/note", "AgentController@storeNote")->name('note.store');
            Route::post("/set/new/office/{office}/for/agent/{agent}", "AgentController@newOffice")->name('update.office');
            Route::get("/update/post/{post}/agent/{agent}/{column}/{value}", "AgentController@updatePostAgentColumn")->name('agent.update.column');
            Route::get("/update/post/agent/{post}/{agent}/{access}/{price}/{locked}", "AgentController@toggleLocked")->name('toggle.locked');
            Route::get("/update/posts/agent/{post}/{agent}/{access}/{price?}", "AgentController@toggleAccess")->name('toggle.access');
            Route::get('/get/agent/{agent}/posts', 'AgentController@getAgentPosts');
            Route::get('/get/agent/{agent}/accessories', "AgentController@getAgentAcessories");
            Route::get('/get/agent/{agent}/panels', "AgentController@getAgentPanels");
        });
        //inventories
        Route::resource('inventories', 'InventoryController');
        //related to inventories
        Route::resource('posts', 'PostController');
        Route::resource('panels', 'PanelController');
        Route::resource('panelSettings', 'PanelSettingController');
        Route::resource('accessories', 'AccessoryController');
        //Post group
        Route::group(['as' => 'posts.'], function () {
            Route::get('/get/post/{post}', 'PostController@getPost');
            Route::post("/posts/delete/all", "PostController@destroyAll")->name('destroy.all');

            Route::get("/update/posts/{post}/{column}/{value}", "PostController@updateColumn")->name('update.column');

            Route::get("/update/post/{post}/office/{office}/{column}/{value}", "PostController@updatePostOfficeColumn")->name('post.office.update.column');
            Route::get("/update/post/office/{post}/{office}/{access}/{price}/{locked}", "PostController@toggleLocked")->name('toggle.locked');
            Route::get("/update/posts/office/{post}/{office}/{access}/{price?}", "PostController@toggleAccess")->name('toggle.access');
        });

        // Panels group
        Route::group(['as' => 'panels.'], function () {
            Route::get('/get/panel/{panel}', 'PanelController@getPanel');
            Route::post("/panels/delete/all", "PanelController@destroyAll")->name('destroy.all');
            Route::get("/panel/{panel}/agents", "PanelController@agents")->name('panel.agents');
            Route::get("/panel/{panel}/all/agents", "PanelController@allAgents")->name('panel.agents');
        });

        // Accessories group
        Route::group(['as' => 'accessories.'], function () {
            Route::get('/get/accessory/{accessory}/json', 'AccessoryController@getAccessoryJson');
            Route::get('/get/accessory/{accessory}', 'AccessoryController@getAccessory');
            Route::post("/accessories/delete/all", "AccessoryController@destroyAll")->name('destroy.all');
            Route::get("/update/accessory/{accessory}/{column}/{value}", "AccessoryController@updateColumn")->name('update.column');
            /////////////
            Route::get("/update/accessory/{accessory}/office/{office}/{column}/{value}", "AccessoryController@updateAccessoryOfficeColumn")->name('offices.update.column');
            Route::get("/update/accessory/office/{accessory}/{office}/{access}/{price}/{locked}", "AccessoryController@toggleLocked")->name('offices.toggle.locked');
            Route::get("/update/accessories/office/{accessory}/{office}/{access}/{price?}", "AccessoryController@toggleAccess")->name('offices.toggle.access');
            ////////////
            Route::get("/update/accessory/{accessory}/agent/{agent}/{column}/{value}", "AccessoryController@updateAccessoryAgentColumn")->name('agents.update.column');
            Route::get("/update/accessory/agent/{accessory}/{agent}/{access}/{price}/{locked}", "AccessoryController@AgenttoggleLocked")->name('agents.toggle.locked');
            Route::get("/update/accessories/agent/{accessory}/{agent}/{access}/{price?}", "AccessoryController@AgenttoggleAccess")->name('agents.toggle.access');
            Route::post('/store/douctment/accessory/{accessory}', "AccessoryController@storeDocuments");
        });
        //services
        Route::resource('services', 'ServiceController');
        Route::get("/update/column/service/settings/{column}/{value}", "ServiceSettingController@updateColumn")->name('serive.settings.update');
        //zones
        Route::resource('zones', "ZoneController");
        Route::group(['as' => 'zones.'], function () {
            Route::get('/get/zones/orderby/{column}/{type}', 'ZoneController@getZonesOrderBy');
            Route::post('/update/zone/{zone}', 'ZoneController@update');
            Route::post('/update/settings/zone/', 'ZoneController@updateSettings')->name('update.settings');
            Route::post('/update/zone/fee/{zone}', 'ZoneController@updateZoneFee')->name('update.zone.fee');
            Route::get("/delete/zone/{zone}", "ZoneController@delete")->name("delete");
            Route::get('/get/zone/settings', 'ZoneController@getSettings');
        });
        //holidays
        Route::group(['as' => 'holidays.'], function () {
            Route::get('/get/holidays', 'HolidayController@getAll');
            Route::post('/set/holidays', 'HolidayController@store');
        });

        //datatable private group
        Route::group(['as' => 'datatable.', 'prefix' => '/datatable'], function () {
            Route::get('/offices', 'OfficeController@datatable')->name('offices');
            Route::get('/post/offices', 'OfficeController@post_offices')->name('post.offices');
            Route::get('/agents', 'AgentController@datatable')->name('agents');
            Route::get('/post/agents', 'AgentController@post_agents')->name('agents.offices');
            Route::get('/posts', 'PostController@datatable')->name('posts');
            Route::get('/panels', 'PanelController@datatable')->name('panels');
            Route::get('/accessories', "AccessoryController@datatable")->name('accessories');
            Route::get('/accessory/offices', 'OfficeController@accessory_offices')->name('accessories.offices');
            Route::get('/accessory/agents', 'OfficeController@accessory_agents')->name('accessories.agents');
            Route::get('/orders', "OrderController@datatable")->name("orders");
        });
    });
    //exports routes
    Route::group(['prefix' => '/export', 'as' => "export."], function () {
        Route::get('/agents', 'ExportImportController@agents')->name('agents');
    });
    //imports routes
    Route::group(['prefix' => '/import', 'as' => "import."], function () {
        Route::post('/agents', 'ExportImportController@importAgents')->name('agents');
    });

    //files routes
    Route::get('/private/image/{name}', 'PrivateFileController@getImage');
    Route::get('/private/image/office/{name}', 'PrivateFileController@getOfficeFile');
    Route::get('/private/image/post/{name}', 'PrivateFileController@getPostFile');
    Route::get('/private/image/panel/{name}', 'PrivateFileController@getPanelFile');
    Route::get('/private/image/accessory/{name}', 'PrivateFileController@getAccessoryFile');
    Route::get('/private/document/file/{name}', 'PrivateFileController@documentFile');
});

//datatable group
Route::group(['as' => 'datatable.public.', 'prefix' => '/datatable/public'], function () {
    Route::get('/offices', 'OfficeController@datatable_public')->name('offices');
});

// Route::view('/view/order','mail.order.created',['order'=> Order::get()->first()]);

<?php

Route::group(['middleware' => 'web', 'prefix' => \Helper::getSubdirectory(), 'namespace' => 'Modules\MultiAssign\Http\Controllers'], function () {
    Route::any('/multi-assign/toggle', ['uses' => 'MultiAssignController@toggle', 'middleware' => ['auth', 'roles'], 'roles' => ['admin', 'user'], 'laroute' => true])->name('multiassign.toggle');
    Route::any('/multi-assign/clear', ['uses' => 'MultiAssignController@clearAll', 'middleware' => ['auth', 'roles'], 'roles' => ['admin', 'user'], 'laroute' => true])->name('multiassign.clear');
});

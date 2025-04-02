<?php

use TeamsTom\TableSticky\Http\Controllers\TableStickyController;
use Illuminate\Support\Facades\Route;

Route::get('table-sticky', TableStickyController::class.'@index');

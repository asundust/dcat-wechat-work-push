<?php

use Asundust\DcatWechatWorkPush\Http\Controllers\DcatWechatWorkPushUserController;
use Illuminate\Support\Facades\Route;

Route::resource('wechatWorkPushUsers', DcatWechatWorkPushUserController::class)->except(['show']);

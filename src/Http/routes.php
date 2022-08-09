<?php

use Asundust\DcatWechatWorkPush\Http\Controllers\WechatWorkPushUserController;
use Illuminate\Support\Facades\Route;

Route::resource('wechatWorkPushUsers', WechatWorkPushUserController::class)->except(['show']);

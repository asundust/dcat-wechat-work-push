<?php

use Asundust\DcatWechatWorkPush\Http\Controllers\WechatWorkPushHandleController;

Route::match(['get', 'post'], 'push/{secret}', WechatWorkPushHandleController::class.'@push');

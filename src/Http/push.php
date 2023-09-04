<?php

use Asundust\DcatWechatWorkPush\Http\Controllers\DcatWechatWorkPushHandleController;

Route::match(['get', 'post'], 'push/{secret}', DcatWechatWorkPushHandleController::class . '@push');

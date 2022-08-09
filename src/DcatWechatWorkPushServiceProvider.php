<?php

namespace Asundust\DcatWechatWorkPush;

use Dcat\Admin\Admin;
use Dcat\Admin\Extend\ServiceProvider;
use Illuminate\Routing\Router;

class DcatWechatWorkPushServiceProvider extends ServiceProvider
{
    // 注册菜单
    protected $menu = [
        [
            'title' => '企业微信消息推送',
            'uri' => '',
            'icon' => 'fa-wechat',
        ],
        [
            'parent' => '企业微信消息推送',
            'title' => '用户配置',
            'uri' => 'wechatWorkPushUsers',
        ],
    ];

    public function register()
    {
    }

    public function init()
    {
        $this->registerPushRoutes();
        parent::init();
    }

    public function settingForm()
    {
        return new Setting($this);
    }

    public function registerPushRoutes()
    {
        $path = $this->path('src/Http/push.php');

        Admin::app()->routes(function (Router $router) use ($path) {
            $router->group([
                'middleware' => config('admin.route.middleware'),
            ], is_file($path) ? $path : null);
        });
    }
}

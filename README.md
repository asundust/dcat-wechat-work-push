Dcat-Admin 消息推送插件 by 企业微信应用消息
======
> ~~无需公众号，不需要安装企业微信客户端，低成本推送消息解决方案~~（仅限老应用）

> 貌似现在新建应用必须绑定IP白名单才可以使用，且无法脱离企业微信APP的样子

> 另有 [Laravel-Admin版](https://github.com/asundust/wechat-work-push)

![StyleCI build status](https://github.styleci.io/repos/382739241/shield)
<a href="https://packagist.org/packages/asundust/dcat-wechat-work-push"><img src="https://img.shields.io/packagist/dt/asundust/dcat-wechat-work-push" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/asundust/dcat-wechat-work-push"><img src="https://img.shields.io/packagist/v/asundust/dcat-wechat-work-push" alt="Latest Stable Version"></a>


## 前言

灵感启发Server酱，这边只是一个简单的实现。

## 客户端支持

- Laravel版 [https://github.com/asundust/push-laravel](https://github.com/asundust/push-laravel)

## 功能介绍

目前版本支持灵活设置

- 支持入参标题、内容、链接、链接标题
- 一个【企业微信应用】的消息可推送【单个账号/全部人员】）
- 【单个账号/全部人员】可设置独立的【企业微信应用】配置

另外

- 目前版本不支持内容文本markdown等格式，仅支持简单文本，后期开发
- 目前版本无日志功能，后期开发

## 截图

- 能直接在通知里看到消息内容

![通知效果](https://user-images.githubusercontent.com/6573979/107605606-a4adfb80-6c6e-11eb-9f71-66309bc41c1e.png)

## 安装

```
composer require asundust/dcat-wechat-work-push
```

## 相关说明

- 会增加以下菜单菜单
```
企业微信消息推送
└用户配置
```
- `用户配置`的表名默认为`dcat_wechat_work_push_users`，目前不可更改
- 默认中间件组为`web`，目前不可更改
- 目前不支持多语言，后期可以考虑增加

## 配置

- 由于内容一致，参见 [Laravel-Admin版#配置](https://github.com/asundust/wechat-work-push#%E9%85%8D%E7%BD%AE)

## 使用

- 默认路由支持`get`和`post`，**记得在`VerifyCsrfToken`里的`except`添加`push/*`**，以便支持`post`接口请求。

- 接口地址为`http://{www.abc.com}/push/{推送密钥}`，标题为`title`不可空，内容为`content`可不传，链接为`url`可不传，链接标题为`url_title`可不传。 示例：`get`
  地址为`http://{www.abc.com}/push/我是密钥?title=测试标题&content=测试内容&url=https://www.baidu.com&url_title=我是百度的测试链接`

- 传入不合法的`url`可能会导致发送请求超时，不知为何，建议自行测试。

## 内部调用支持

- 引用此Trait类`\Asundust\DcatWechatWorkPush\Http\Traits\DcatWechatWorkPushSendMessageTrait`。
- 使用默认配置发送`defaultSend()`，使用自定配置发送`send()`，具体入参看方法。

## 支持

如果觉得这个项目帮你节约了时间，不妨支持一下呗！

![alipay](https://user-images.githubusercontent.com/6573979/91679916-2c4df500-eb7c-11ea-98a7-ab740ddda77d.png)
![wechat](https://user-images.githubusercontent.com/6573979/91679913-2b1cc800-eb7c-11ea-8915-eb0eced94aee.png)

## License

[The MIT License (MIT)](https://opensource.org/licenses/MIT)

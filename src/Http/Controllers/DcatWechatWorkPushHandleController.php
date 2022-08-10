<?php

namespace Asundust\DcatWechatWorkPush\Http\Controllers;

use Asundust\DcatWechatWorkPush\DcatWechatWorkPushServiceProvider;
use Asundust\DcatWechatWorkPush\Http\Traits\DcatWechatWorkPushSendMessageTrait;
use Asundust\DcatWechatWorkPush\Models\DcatWechatWorkPushUser;
use Dcat\Admin\Http\Controllers\AdminController;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\RuntimeException;
use Illuminate\Http\Request;

class DcatWechatWorkPushHandleController extends AdminController
{
    use DcatWechatWorkPushSendMessageTrait;

    /**
     * @param $secret
     *
     * @throws InvalidArgumentException
     * @throws RuntimeException
     */
    public function push($secret, Request $request): array
    {
        $title = $request->input('title');
        $content = $request->input('content');
        $url = $request->input('url');
        $urlTitle = $request->input('url_title');
        if (!$title) {
            return ['code' => 1, 'message' => '消息标题为空'];
        }

        $user = DcatWechatWorkPushUser::where('sc_secret', $secret)
            ->where('status', 1)
            ->first();

        if ($user) {
            if ($user->is_own_wechat_work) {
                $config = [
                    'corp_id' => $user->corp_id,
                    'agent_id' => $user->agent_id,
                    'secret' => $user->secret,
                ];
            } else {
                $config = [
                    'corp_id' => DcatWechatWorkPushServiceProvider::setting('corp_id'),
                    'agent_id' => DcatWechatWorkPushServiceProvider::setting('agent_id'),
                    'secret' => DcatWechatWorkPushServiceProvider::setting('secret'),
                ];
            }

            return $this->send($config, $user->name, $title, $content, $url, $urlTitle);
        }

        return ['code' => 1, 'message' => 'secret验证失败'];
    }
}

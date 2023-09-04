<?php

namespace Asundust\DcatWechatWorkPush\Http\Actions;

use Asundust\DcatWechatWorkPush\DcatWechatWorkPushServiceProvider;
use Asundust\DcatWechatWorkPush\Http\Traits\DcatWechatWorkPushSendMessageTrait;
use Asundust\DcatWechatWorkPush\Models\DcatWechatWorkPushUser;
use Dcat\Admin\Grid\RowAction;
use Illuminate\Http\Request;

class SendTestMessage extends RowAction
{
    use DcatWechatWorkPushSendMessageTrait;

    /**
     * 标题.
     *
     * @return string
     */
    public function title()
    {
        return '发送测试消息';
    }

    /**
     * @return \Dcat\Admin\Actions\Response
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function handle(Request $request)
    {
        $id = $this->getKey();
        $user = DcatWechatWorkPushUser::find($id);
        if ($user->is_own_wechat_work) {
            $config = [
                'corp_id' => $user->corp_id,
                'agent_id' => $user->agent_id,
                'secret' => $user->secret,
            ];
            $type = '自定义';
        } else {
            $config = [
                'corp_id' => DcatWechatWorkPushServiceProvider::setting('corp_id'),
                'agent_id' => DcatWechatWorkPushServiceProvider::setting('agent_id'),
                'secret' => DcatWechatWorkPushServiceProvider::setting('secret'),
            ];
            if (3 != count(array_filter($config))) {
                return $this->response()->error('【默认配置】或【自定义配置】企业微信通道尚未配置');
            }
            $type = '默认';
        }

        $title = '当前使用的【' . $type . '配置】企业微信通道发送的测试消息';
        $result = $this->send($config, $user->name, $title);
        if (0 == $result['code']) {
            return $this->response()->success('使用【' . $type . '配置】企业微信通道发送消息成功');
        }

        return $this->response()->success('使用【' . $type . '配置】企业微信通道发送消息失败：' . $result['message']);
    }
}

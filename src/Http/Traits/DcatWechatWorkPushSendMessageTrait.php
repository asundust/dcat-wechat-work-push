<?php

namespace Asundust\DcatWechatWorkPush\Http\Traits;

use Asundust\DcatWechatWorkPush\DcatWechatWorkPushServiceProvider;

/**
 * Trait DcatWechatWorkPushSendMessageTrait.
 */
trait DcatWechatWorkPushSendMessageTrait
{
    /**
     * 使用自定配置发送消息.
     *
     * @param array       $config   配置 ['corp_id' => 'xxx', 'agent_id' => 'xxx', 'secret' => 'xxx'];
     * @param string      $name     用户
     * @param string      $title    标题
     * @param string|null $content  内容
     * @param string|null $url      链接
     * @param string|null $urlTitle 链接标题
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function send(array $config, string $name, string $title, ?string $content = null, ?string $url = null, ?string $urlTitle = null): array
    {
        $message = $title;
        if ($content) {
            $message .= "\n\n" . $content;
        }
        if ($url) {
            $message .= "\n\n" . '<a href="' . $url . '">' . ($urlTitle ?: $url) . '</a>';
        }

        if (
            version_compare($this->getPackageVersion('w7corp/easywechat'), '6.0.0', '>=')
            || version_compare($this->getPackageVersion('overtrue/wechat'), '6.0.0', '>=')
        ) {
            $app = new \EasyWeChat\Work\Application([
                'corp_id' => $config['corp_id'],
                'secret' => $config['secret'],
            ]);
            $response = $app->getClient()->postJson('/cgi-bin/message/send', [
                'touser' => $name ?? '@all',
                'msgtype' => 'text',
                'agentid' => $config['agent_id'],
                'text' => [
                    'content' => $message,
                ],
            ]);
            $result = $response->toArray();
        } else {
            $messenger = \EasyWeChat\Factory::work($config)->messenger;
            $result = $messenger->ofAgent($config['agent_id'])->message($message)->toUser($name ?? '@all')->send();
        }

        if (0 == $result['errcode'] && 'ok' == $result['errmsg']) {
            return ['code' => 0, 'message' => 'success', 'original' => app()->isLocal() ? $result : []];
        }

        return ['code' => 1, 'message' => $result['errmsg'], 'original' => app()->isLocal() ? $result : []];
    }

    /**
     * 使用默认配置发送消息.
     *
     * @param string      $name     用户
     * @param string      $title    标题
     * @param string|null $content  内容
     * @param string|null $url      链接
     * @param string|null $urlTitle 链接标题
     *
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     */
    public function defaultSend(string $name, string $title, ?string $content = null, ?string $url = null, ?string $urlTitle = null): array
    {
        $config = [
            'corp_id' => DcatWechatWorkPushServiceProvider::setting('corp_id'),
            'agent_id' => DcatWechatWorkPushServiceProvider::setting('agent_id'),
            'secret' => DcatWechatWorkPushServiceProvider::setting('secret'),
        ];

        return $this->send($config, $name, $title, $content, $url, $urlTitle);
    }

    /**
     * 获取已安装扩展的版本号.
     *
     * @param $packageName
     *
     * @return false|string
     */
    private function getPackageVersion($packageName)
    {
        try {
            return \Composer\InstalledVersions::getVersion($packageName);
        } catch (\OutOfBoundsException $exception) {
            return false;
        }
    }
}

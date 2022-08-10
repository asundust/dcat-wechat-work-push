<?php

namespace Asundust\DcatWechatWorkPush\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Asundust\DcatWechatWorkPush\Models\DcatWechatWorkPushUser.
 *
 * @property int         $id
 * @property string      $name               用户的账户
 * @property string      $sc_secret          用户的推送密钥
 * @property int         $status             状态(0禁用1启用)
 * @property string|null $corp_id            用户自定企业ID
 * @property string|null $agent_id           用户自定应用ID/agent_id
 * @property string|null $secret             用户自定应用Secret
 * @property bool        $is_own_wechat_work
 * @property string      $api_address
 * @property string      $api_address_show
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @mixin Eloquent
 */
class DcatWechatWorkPushUser extends Model
{
    protected $fillable = ['name', 'sc_secret', 'status', 'corp_id', 'agent_id', 'secret'];

    const STATES = [
        0 => '禁用',
        1 => '启用',
    ];

    const IS_OWN_WECHAT_WORK = [
        0 => '否',
        1 => '是',
    ];

    const STATES_SWITCH = [
        'on' => ['value' => 1, 'text' => '启用', 'color' => 'success'],
        'off' => ['value' => 0, 'text' => '禁用', 'color' => 'danger'],
    ];

    /**
     * Settings constructor.
     *
     * @param array $attributes
     */
    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(config('admin.database.connection') ?: config('database.default'));

        $this->setTable('dcat_wechat_work_push_users');
    }

    // is_own_wechat_work
    public function getIsOwnWechatWorkAttribute(): bool
    {
        return $this->corp_id && $this->agent_id && $this->secret;
    }

    // api_address_show
    public function getApiAddressShowAttribute(): string
    {
        return config('app.url').'/push/***';
    }

    // api_address
    public function getApiAddressAttribute(): string
    {
        return config('app.url').'/push/'.$this->sc_secret.'?title=我是标题&content=我是内容(可不填)&url=我是链接(可不填)&url_title=链接标题(可不填)';
    }
}
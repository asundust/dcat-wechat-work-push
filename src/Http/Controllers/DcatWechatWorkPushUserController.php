<?php

namespace Asundust\DcatWechatWorkPush\Http\Controllers;

use Asundust\DcatWechatWorkPush\Http\Actions\SendTestMessage;
use Asundust\DcatWechatWorkPush\Models\DcatWechatWorkPushUser;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Grid\Displayers\Actions;
use Dcat\Admin\Grid\Filter;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class DcatWechatWorkPushUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '企业微信应用消息用户管理';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DcatWechatWorkPushUser());

        $grid->column('id', '序号');
        $grid->column('name', '推送账号')->copyable();
        $grid->column('sc_secret', '推送密钥')->display(function ($scSecret) {
            return '<a href="javascript:void(0);" class="grid-column-copyable text-muted" data-content="' . $scSecret . '" title="" data-placement="bottom" data-original-title="已复制!"><i class="fa fa-copy"></i></a>&nbsp;***';
        });
        $grid->column('api_address', '推送Api地址')->display(function () {
            /* @var DcatWechatWorkPushUser $this */
            $apiAddressShow = $this->api_address_show;
            $apiAddress = $this->api_address;

            return '<a href="javascript:void(0);" class="grid-column-copyable text-muted" data-content="' . $apiAddress . '" title="" data-placement="bottom" data-original-title="已复制!"><i class="fa fa-copy"></i></a>&nbsp;' . $apiAddressShow;
        });
        $grid->column('status', '账号状态')->switch();
        $grid->column('is_own_wechat_work', '自定企业微信')->bool();
        $grid->column('created_at', '创建时间')->display(function ($createdAt) {
            return date('Y-m-d H:i:s', strtotime($createdAt));
        });
        $grid->column('updated_at', '更新时间')->display(function ($updatedAt) {
            return date('Y-m-d H:i:s', strtotime($updatedAt));
        });

        $grid->filter(function (Filter $filter) {
            $filter->equal('status', '账号状态')
                ->radio(array_merge(['' => '全部'], DcatWechatWorkPushUser::STATES));
            $filter->equal('id', '序号');
            $filter->like('name', '推送账号');
            $filter->where('user_self_config', function (Builder $builder) {
                switch ($this->input) {
                    case 0:
                        $builder->whereNull('corp_id')
                            ->orwhereNull('agent_id')
                            ->orwhereNull('secret');
                        break;
                    case 1:
                        $builder->whereNotNull('corp_id')
                            ->whereNotNull('agent_id')
                            ->whereNotNull('secret');
                        break;
                }
            }, '自定企业微信')
                ->radio(array_merge(['' => '全部'], DcatWechatWorkPushUser::IS_OWN_WECHAT_WORK));
            $filter->between('created_at', '创建时间')->datetime();
            $filter->between('updated_at', '创建时间')->datetime();
        });

        $grid->showToolbar();

        $grid->actions(function (Actions $actions) {
            $actions->disableView();
            $actions->append(new SendTestMessage());
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new DcatWechatWorkPushUser());

        $form->text('name', '推送账号')
            ->rules('required')
            ->help('后台的话【通讯录】-【企业名字】-【点击账号进入详情】-【账号】；如果要发送给全部人员请填写【@all】');
        $form->text('sc_secret', '账号推送密钥')
            ->default(strtolower(Str::random(32)))
            ->rules('required')
            ->help('推送消息的唯一密钥');
        $form->switch('status', '账号状态')
            ->default(1);
        $form->text('corp_id', '自定企业ID')
            ->help('推送不是走自己的企业微信可为空');
        $form->text('agent_id', '自定应用ID/agent_id')
            ->help('推送不是走自己的企业微信可为空');
        $form->password('secret', '自定应用Secret')
            ->help('推送不是走自己的企业微信可为空');

        // todo 推送密钥唯一处理

        $form->disableViewCheck();

        return $form;
    }
}

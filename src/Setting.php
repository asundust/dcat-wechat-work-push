<?php

namespace Asundust\DcatWechatWorkPush;

use Dcat\Admin\Extend\Setting as Form;

class Setting extends Form
{
    public function form()
    {
        $this->text('corp_id', '默认企业ID')->rules('required');
        $this->text('agent_id', '默认应用ID/agent_id')->rules('required');
        $this->password('secret', '默认应用Secret')->rules('required');
    }
}

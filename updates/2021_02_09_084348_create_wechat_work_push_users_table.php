<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWechatWorkPushUsersTable extends Migration
{
    /**
     * @return \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string|null
     */
    public function getConnection()
    {
        return config('admin.database.connection') ?: config('database.default');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table = 'wechat_work_push_users';
        Schema::create($table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->index()->comment('用户的账户');
            $table->string('sc_secret')->unique()->comment('用户的推送密钥');
            $table->tinyInteger('status')->index()->comment('状态(0禁用1启用)');
            $table->text('corp_id')->nullable()->comment('用户自定企业ID');
            $table->text('agent_id')->nullable()->comment('用户自定应用ID/agent_id');
            $table->text('secret')->nullable()->comment('用户自定应用Secret');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table = 'wechat_work_push_users';
        Schema::dropIfExists($table);
    }
}

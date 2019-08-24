<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->morphs('product');//产品关联

            $table->unsignedInteger('quantity')->default(1)->comment('购买数量');

            $table->unsignedBigInteger('user_address_id')->nullable();
            $table->string('channel', 10);
            $table->string('type', 10);
            $table->unsignedInteger('amount')->default(0);
            $table->ipAddress('client_ip')->nullable();//发起支付请求客户端的 IP 地址
            $table->string('status', 20);
            $table->timestamps();
            $table->timestamp('succeeded_at', 0)->nullable();//付款成功时间
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

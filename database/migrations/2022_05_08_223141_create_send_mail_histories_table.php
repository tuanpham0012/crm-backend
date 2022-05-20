<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSendMailHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('send_mail_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('id người gửi mail');
            $table->string('title');
            $table->text('content');
            $table->integer('customer_id');
            $table->string('to_email');
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
        Schema::dropIfExists('send_mail_histories');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCallHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_call_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('Người liên hệ');
            $table->integer('customer_id');
            $table->string('phone_contacts');
            $table->integer('time');
            $table->string('content')->nullable();
            $table->string('link_record')->nullable();
            $table->text('note')->nullable();
            $table->integer('call_status_id');
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
        Schema::dropIfExists('customer_call_histories');
    }
}

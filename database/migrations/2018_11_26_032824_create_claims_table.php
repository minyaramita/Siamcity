<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ins_id')->unsigned();
            $table->date('claim_date');
            $table->string('accident_cause');
            $table->date('accident_date')->nullable();
            $table->float('withdraw_amount')->nullable();
            $table->float('approve_amount')->nullable();
            $table->date('pay_date')->nullable();
            $table->string('payType')->nullable();
            $table->mediumText('detail')->nullable();
            $table->integer('status_id')->unsigned();
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
        Schema::dropIfExists('claims');
    }
}

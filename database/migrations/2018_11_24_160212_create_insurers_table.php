<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsurersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('title_id')->unsigned();
            $table->string('ins_fname');
            $table->string('ins_lname');
            $table->string('ins_class');
            $table->integer('namelist_id')->unsigned();
            $table->string('ins_type');
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
        Schema::dropIfExists('insurers');
    }
}

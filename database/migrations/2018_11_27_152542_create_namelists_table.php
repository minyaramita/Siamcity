<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNamelistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('namelists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('school_id')->unsigned();
            $table->integer('quantity_student')->unsigned()->nullable();
            $table->integer('quantity_personnel')->unsigned()->nullable();
            $table->date('receive_date');
            $table->date('protection_date');
            $table->integer('plan_id')->unsigned();
            $table->year('year');
            $table->mediumText('detail')->nullable();
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
        Schema::dropIfExists('namelists');
    }
}

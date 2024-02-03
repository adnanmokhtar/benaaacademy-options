<?php

use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateOptionsTable
 */
class CreateOptionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create("options", function ($table) {
            $table->increments('id');
            $table->string('name')->index();
            $table->text("value")->nullable();
            $table->text("lang")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('options');
    }

}

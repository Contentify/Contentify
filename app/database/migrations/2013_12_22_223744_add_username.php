<?php

use Illuminate\Database\Migrations\Migration;

class AddUsername extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->string('username', 20)->unique();
            $table->string('slug')->unique();
            $table->integer('updater_id')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}
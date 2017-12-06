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
            // Note: An empty string is not a valid value for the slug attribute, however,
            // we have to allow this, because Sentinel will fill it it in its register() method.
            $table->string('slug')->unique()->default('');
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
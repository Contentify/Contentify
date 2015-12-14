<?php

use Illuminate\Database\Migrations\Migration;

class AddRolesUpdater extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function($table)
        {
            $table->integer('creator_id')->unsigned()->default(0);
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
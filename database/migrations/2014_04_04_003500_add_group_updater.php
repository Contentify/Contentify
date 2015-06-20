<?php

use Illuminate\Database\Migrations\Migration;

class AddGroupUpdater extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function($table)
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
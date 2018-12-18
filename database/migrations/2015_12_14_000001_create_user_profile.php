<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserProfile extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table)
        {
            $table->boolean('banned')->default(false);
            $table->string('steam_auth_id')->nullable();
            $table->timestamp('last_active')->nullable()->default(null);
            $table->integer('gender')->nullable()->default(0);
            $table->integer('country_id')->default('1');
            $table->integer('language_id')->default('1');
            $table->string('birthdate', 20)->nullable();
            $table->text('occupation')->nullable();
            $table->text('website')->nullable();
            $table->text('about')->nullable();

            $table->string('discord')->nullable();
            $table->string('skype')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('steam_id')->nullable();

            $table->string('cpu')->nullable();
            $table->string('graphics')->nullable();
            $table->string('ram')->nullable();
            $table->string('motherboard')->nullable();
            $table->string('drives')->nullable();
            $table->string('display')->nullable();
            $table->string('mouse')->nullable();
            $table->string('keyboard')->nullable();
            $table->string('headset')->nullable();
            $table->string('mouse_pad')->nullable();

            $table->string('game')->nullable();
            $table->string('food')->nullable();
            $table->string('drink')->nullable();
            $table->string('music')->nullable();
            $table->string('film')->nullable();

            $table->string('image', 255)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->integer('cup_points')->default(0);
            $table->string('access_counter', 255)->default(0);
            $table->string('posts_count', 255)->default(0); // Forum posts counter
            $table->text('signature')->nullable(); // Forum signature
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

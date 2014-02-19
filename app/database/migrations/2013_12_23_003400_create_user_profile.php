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
			$table->integer('gender')->nullable()->default(0);
			$table->string('birthdate', 20)->nullable();
			$table->text('about')->nullable();

			$table->string('skype')->nullable();
			$table->string('steamid')->nullable();

			$table->string('cpu')->nullable();
			$table->string('graphics')->nullable();
			$table->string('ram')->nullable();
			$table->string('motherboard')->nullable();
			$table->string('drives')->nullable();
			$table->string('display')->nullable();
			$table->string('mouse')->nullable();
			$table->string('keyboard')->nullable();
			$table->string('headset')->nullable();
			$table->string('mousepad')->nullable();

			$table->string('game')->nullable();
			$table->string('food')->nullable();
			$table->string('drink')->nullable();
			$table->string('music')->nullable();
			$table->string('film')->nullable();

			$table->string('image', 255)->nullable();
			$table->string('avatar', 255)->nullable();
			$table->string('view_counter', 255)->default(0);
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
<?php

class InstallController extends BaseController {

	public function index() 
	{
		$this->createDatabase();
	}

	public function createDatabase()
	{
		// The default length of strings is 255 chars.
		//
		
		$this->create('games', function($table)
		{
			$table->string('tag', 6)->nullable();
			$table->string('image')->nullable();
		});

		$this->create('contact_messages', function($table)
		{
			$table->string('username', 30);
			$table->string('email');
			$table->text('text');
			$table->string('ip');
			$table->boolean('new')->default(true);
		});
	}

	/**
	 * Helper functions. Creates a database table.
	 * @param  string  $tableName
	 * @param  Closure $tableRows
	 * @param  boolean $isContentObject
	 */
	private function create($tableName, Closure $tableRows, $isContentObject = true)
	{
		// Delete existing table:
		Schema::dropIfExists($tableName);

		// Add id:
		Schema::create($tableName, function($table)
		{
			$table->increments('id');
		});

		// Add the table rows:
		Schema::table($tableName, $tableRows);

		// Add the timestamps:
		if ($isContentObject) {
			Schema::table($tableName, function($table)
			{
				$table->string('title', 70);
				$table->integer('creator_id')->default(0);
				$table->timestamps();
			});

			/*
			// Add soft delete:
			Schema::table($tableName, function($table)
			{
				$table->softDeletes();
			});
			*/
		}
	}
}
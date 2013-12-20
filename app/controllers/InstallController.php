<?php

class InstallController extends BaseController {

	public function index() 
	{
		$this->create('games', function($table)
		{
			$table->string('tag', 5)->nullable();
			$table->string('image', 255)->nullable();
		});
	}

	/**
	* Helper functions. Creates a database table.
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
				$table->string('title', 50);
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
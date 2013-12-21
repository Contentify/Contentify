<?php namespace App\Modules\Games\Models;

use Ardent;

class Game extends Ardent {

	protected $fillable = array('title', 'tag', 'image');

}
<?php namespace App\Modules\Pages\Models;

use App\Modules\Pages\Models\Page;
use DB;

class Fragment extends Page {

    protected $isSubclass = true;

    protected $subclassId = 3;

}
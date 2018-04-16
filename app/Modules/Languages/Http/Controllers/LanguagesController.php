<?php 

namespace App\Modules\Streams\Http\Controllers;

use App\Modules\Streams\Stream;
use FrontController;

class LanguagesController extends FrontController
{

    /*
    public function __construct()
    {
        $this->modelClass = Stream::class;

        parent::__construct();
    }
    */

    /**
     * Show a stream
     * 
     * @param  string $code The code of the language
     * @return void
     */
    public function set($code)
    {
        /** @var Stream $stream */
        $stream = Stream::findOrFail($id);

        $stream->access_counter++;
        $stream->save();

        $this->title($stream->title);

        $this->pageView('streams::show', compact('stream'));
    }

}
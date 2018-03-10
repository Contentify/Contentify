<?php

namespace Contentify\Traits;

use ModelHandler;

trait ModelHandlerTrait
{

    /**
     * CRUD: create model
     *
     * @return void
     */
    public function create()
    {
        ModelHandler::controller($this);

        ModelHandler::create();
    }

    /**
     * CRUD: store model
     *
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function store()
    {
        ModelHandler::controller($this);

        return ModelHandler::store();
    }

    /**
     * CRUD: edit model
     *
     * @param  int $id The id of the model
     * @return void
     */
    public function edit($id)
    {
        ModelHandler::controller($this);

        ModelHandler::edit($id);
    }

    /**
     * CRUD: update model
     *
     * @param  int $id The id of the model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id)
    {
        ModelHandler::controller($this);

        return ModelHandler::update($id);
    }

    /**
     * CRUD: delete model
     *
     * @param  int $id The id of the model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        ModelHandler::controller($this);

        return ModelHandler::destroy($id);
    }

    /**
     * CRUD-related: restore model after soft deletion
     *
     * @param  int $id The id of the model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        ModelHandler::controller($this);

        return ModelHandler::restore($id);
    }

    /**
     * Helper action method for searching. All we do here is to redirect with the input.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function search()
    {
        ModelHandler::controller($this);
        
        return ModelHandler::search();
    }
    
}
<?php namespace Contentify\Traits;

use ModelHandler;

trait ModelHandlerTrait {

    /**
     * CRUD: create model
     */
    public function create()
    {
        ModelHandler::controller($this);

        return ModelHandler::create();
    }

    /**
     * CRUD: store model
     */
    public function store()
    {
        ModelHandler::controller($this);

        return ModelHandler::store();
    }

    /**
     * CRUD: edit model
     * 
     * @param  int The id of the model
     */
    public function edit($id)
    {
        ModelHandler::controller($this);

        return ModelHandler::edit($id);
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
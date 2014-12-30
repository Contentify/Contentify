## Base Controller

All controllers should inherit from the BaseController class. Its constructor adds several attributes such as the module and controller name and the model that is handled by the inheriting controller. It also enables CSRF protection. The base controller class implements several methods that are extremly helpful when working with controllers.

* *pageView*: Adds a view to the main layout
* *pageOutput*: Adds a string to the main layout
* *pageMessage*: Adds a message view to the main layout
* *messageFlash*: Inserts a flash message to the main layout
* *buildIndexPage*: Builds an index page from a model
* Permission helper functions

### buildIndexPage()

This method returns a string with HTML code that renders an index page. Controllers usually handle a single model. These resource controllers help to build RESTful controllers around resources. The buildIndexPage implements the "index" action. You can pass a data array to configure how this page is rendered.

Basic example:

    $data = [
        'tableHead' => [
            t('ID')     => 'id', 
            t('Title')  => 'title'
        ],
        'tableRow' => function($game)
        {
            return [
                $game->id,
                $game->title,
            ];            
        }]
    $this->buildIndexPage($data);

The main part of the every index page is a table showing entities. In the example the table has two header columns ("ID" and "Title"). Both are related to an attribute of the entity which make them sortable. The table body consist of rows. To build each row a Closure is executed. It recieves the current entity as a parameter and returns an array with values for the columns.

List of arguments the data array may use:

* *buttons*: Array of names of default buttons ("new" and "category") or custom HTML code
* *searchFor*: The attribute to search (e. g. "title"). Null will disable the search feature.
* *infoText*: Info text that is displayed above the table. You may use HTML tags.
* *dataSource*: Null or Array of entities. If null it will take the entities from the database. If an array is passed sorting, searching and pagination are not available.
* *tableHead*: Array of items for the table head columns
* *tableRow*: Closure returning an array of items for all columns of a single row
* *action*: Array of named action buttons for the "Action" column ("edit", "delete" or "restore") or Closures
* *brightenFirst*: Display the first colum values in a bright color? (useful to display IDs in a different style)
* *sortby*: Name of the model attribute used for default sorting (e. g. "id")
* *order*: Default order ("asc" or "desc")
* *filter*: Boolean. If there is content filter UI, apply it to the models?
* *permaFilter: Null or Closure. The model query is passed to the Closure so additional Eloquent manipulations (e. g. filtering with where clauses) is possible. The Closure must return the query.

## Frontend Controller

Extends the BaseController class. Controllers tied to the frontend should inherit from the FrontController class. The frontend controller sets the frontend layout as template. Its constructor passes variables (module and controller name) to this template. It also implements a search method.

## Backend Controller

Extends the BaseController class. Controllers tied to the backend should inherit from the BackController class. While the frontend controller class is lightweight, the backend controller comes with a lot of features. It sets the backend layout as template and passes several variables to it (module and controller name, controller icon, user picture, message notifier).

The most outstanding feature is its ability to handle resource actions. It's capable of using a trait called `ModelHandlerTrait` that implements methods that create (and store), edit (and update) and delete (and restore) a model. This is well known as CRUD. Controllers that use the `ModelHandlerTrait` always inherit these methods. Keep this in mind when creating routes for a resource controller. Perhaps you have to disable routes manually that you do not want to use. For example maybe your controller must not be able to delete the models it handles. In this case you have to close the "delete" route manually.

> Use the `php artisan routes` command to list all available routes.

If a method does not work in the way it's intended or the task to perform is too complex you are free to override it. But if it isn't necessary to override it's recommended to stick to the existing implementation.

## Modifying ModelHandlerTrait Methods

The simplest way to implement another behaviour for a CRUD action is to rename the method and create a new one:

    // app/controllers/ExampleController.php that extends BackController

    use ModelHandlerTrait {
        create as traitCreate;
    }

    public function create()
    {
        // Your implementation
    }

Ofcourse you are still able to call the method of the ModelHandlerTrait class:

    public function create()
    {
        // Do stuff before...

        $this->traitCreate();

        // ...and after calling the method of the ModelHandlerTrait.
    }

It's possible to add extra data to the view that's created by a ModelHandlerTrait method:

    public function create()
    {
        $this->traitCreate();

        $games = Game::all();

        $this->layout->page->with('games');
    }
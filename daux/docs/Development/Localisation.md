## Date And Time

Please use Carbon instead of the native DateTime class. Carbon extends DateTime and adds lots of fantastic features including localisation support. For all examples the use of Carbon is assumed.

Carbon defines the `__toString`method. This allows a pretty short way to print a date:

    echo $news->created_at; // Example output: 1969-07-21

## Eloquent Models And Carbon

Eloquent converts only certain model attributes to Carbon instances:

* **created_at**
* **updated_at**
* **deleted_at**

Override the `$date` array of a model to customize which attributes are mutated:

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'baked_at'];



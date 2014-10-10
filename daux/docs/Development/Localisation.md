## Date And Time

Please use Carbon instead of the native DateTime class. Carbon extends DateTime and adds lots of fantastic features including localisation support. For all examples the use of Carbon is assumed.

Carbon defines the `__toString`method. This allows a pretty short way to print a date:

    echo $news->created_at; // Example output: 1969-07-21

Or use the `date` method to receive the same output. Call `dateTime` to receive date and time:

    echo $news->created_at->dateTime(); // Example output: 1969-07-21 13:37:00

## Eloquent Models And Carbon

Eloquent converts only certain model attributes to Carbon instances:

* **created_at**
* **updated_at**

Override the `$date` array of a model to customize which attributes are mutated:

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'baked_at'];

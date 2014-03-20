The comments system is a built-in feature that is realised as a class: Contentify\Comments (aliased as Comments) Every comment has two attributes that connect it to a content object. `foreignType` is the name of the object type, for example the name of a model such as "news". `foreignId` is optional (null or int) and is the ID of the related object.

> `foreignType` is not limited to the use of models. It can be any string as long as it's a unique identifier.

Due to its flexibility all comments are stored in a single database table. The comment system is independet. There is no direct connection established between the comment class and a controller or model. This makes it easy to integrate it.

Call the show method to render the comment widget in a template:

    {{ Comments::show('news', $news->id) }}
    <!-- show($foreignType, $foreignID) }} -->

Laravel doesn't support relations that have composite foreign keys. Therefore models cannot establish a relationship to the Comment model. A common task is to count the comments that are related to an entity (for example a news post). The Comment class offers a method to perform this task:

    <!-- Excerpt of the news model -->
    public function countComments()
    {
        return Comment::count('news', $this->id);
    }


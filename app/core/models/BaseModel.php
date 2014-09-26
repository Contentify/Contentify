<?php namespace Contentify\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str, DB, DateAccessorTrait, ValidatingTrait, Eloquent, InvalidArgumentException, Exception;

class BaseModel extends Eloquent {

    use ValidatingTrait;

    use DateAccessorTrait;

    /**
     * True if model is slugable
     * @var bool
     */
    protected $slugable = false;

    /**
     * Name of the upload directory (null = name of class)
     * @var string
     */
    protected $uploadDir = null;

    /**
     * Override this method to apply filters to a query of a model.
     * The Filter class provides methods to get values of filters.
     * 
     * @param  Builder $query Apply filters to this query
     * @return Builder
     */
    public function scopeFilter($query) { }

    /**
     * Path to uploaded files.
     * NOTE: When uploading files set $local = true!
     *
     * @param  bool $local If true, return a local path (e. g. "C:\Contentify\public/uploads/games/")
     * @return string
     */
    public function uploadPath($local = false)
    {
        $class = class_basename(get_class($this));

        $dir = $this->uploadDir;
        if (! $dir) {
            $dir = strtolower(str_plural($class));
        }

        if ($local) {
            $base = public_path();
        } else {
            $base = url('/');
        }

        return $base.'/uploads/'.$dir.'/';
    }

    /**
     * Decides if a model is modifiable.
     * This includes updating and deleting.
     * Affects only parts of the CMS (not of Laravel).
     * 
     * @return bool
     */
    public function modifiable()
    {
        return true;
    }

    /**
     * True if model is slugable
     * 
     * @return bool
     */
    public function slugable()
    {
        return $this->slugable;
    }

    /**
     * Creates a simple slug or a unique slug
     * 
     * @param  bool   $unique   Unique or not?
     * @return void
     */
    function createSlug($unique = true)
    {
        if (! $this->slugable) {
            throw new Exception('This model does not support slugs.');
        }

        $slug = Str::slug($this->title);

        if ($unique) {
            /*
             * Retrieve the model with the highest slug counter.
             * (orderBy uses "natural sorting")
             */
            $model = static::orderBy(DB::Raw('LENGTH(slug) DESC, slug'), 'DESC')
                ->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")
                ->withTrashed()->first();

            /*
             * If the slug is in use already:
             * Extract the counter value, increase it and create the new slug.
             */
            if ($model) {
                $max = (int) substr($model->slug, strlen($slug) + 1);
                $slug .= '-'.($max + 1);
            }
        }

        $this->slug = $slug;
    }

    /**
     * With Laravel 4.2 Eloquent's isSoftDeleting() method has been removed.
     * This method is an alternative. The new SoftDeletingTrait adds an attribute 
     * named forceDeleting to the model, so if it exists we know the model
     * uses soft deleting.
          *
     * @return bool
     */
    public function isSoftDeleting()
    {
        return property_exists($this, 'forceDeleting');
    }

    /**
     * Can be used to ease declaration of relationships in models that extend the BaseModel class.
     * This is derived from @link https://github.com/laravelbook/ardent
     * Follows closely the behavior of the relation methods used by Eloquent, but packing them into an indexed array
     * with relation constants make the code less cluttered.
     *
     * It should be declared with camel-cased keys as the relation name, and value being a mixed array with the
     * relation constant being the first (0) value, the second (1) being the classname and the next ones (optionals)
     * having named keys indicating the other arguments of the original methods: 'foreignKey' (belongsTo, hasOne,
     * belongsToMany and hasMany); 'table' and 'otherKey' (belongsToMany only); 'name', 'type' and 'id' (specific for
     * morphTo, morphOne and morphMany).
     * Exceptionally, the relation type MORPH_TO does not include a classname, following the method declaration of
     * {@link \Illuminate\Database\Eloquent\Model::morphTo}.
     *
     * Example:
     * <code>
     * class Order extends BaseModel {
     *     protected static $relations = array(
     *         'items'    => array(self::HAS_MANY, 'Item'),
     *         'owner'    => array(self::HAS_ONE, 'User', 'foreignKey' => 'user_id'),
     *         'pictures' => array(self::MORPH_MANY, 'Picture', 'name' => 'imageable')
     *     );
     * }
     * </code>
     *
     * @see \Illuminate\Database\Eloquent\Model::hasOne
     * @see \Illuminate\Database\Eloquent\Model::hasMany
     * @see \Illuminate\Database\Eloquent\Model::belongsTo
     * @see \Illuminate\Database\Eloquent\Model::belongsToMany
     * @see \Illuminate\Database\Eloquent\Model::morphTo
     * @see \Illuminate\Database\Eloquent\Model::morphOne
     * @see \Illuminate\Database\Eloquent\Model::morphMany
     *
     * @var array
     */
    protected static $relationsData = array();

    const HAS_ONE = 'hasOne';

    const HAS_MANY = 'hasMany';

    const BELONGS_TO = 'belongsTo';

    const BELONGS_TO_MANY = 'belongsToMany';

    const MORPH_TO = 'morphTo';

    const MORPH_ONE = 'morphOne';

    const MORPH_MANY = 'morphMany';

    /**
     * Array of relations used to verify arguments used in the {@link $relationsData}
     *
     * @var array
     */
    protected static $relationTypes = array(
        self::HAS_ONE, self::HAS_MANY,
        self::BELONGS_TO, self::BELONGS_TO_MANY,
        self::MORPH_TO, self::MORPH_ONE, self::MORPH_MANY
    );

    public static function relations()
    {
        return static::$relationsData;
    }

    /**
     * Looks for the relation in the {@link $relationsData} array and does the correct magic as Eloquent would require
     * inside relation methods. For more information, read the documentation of the mentioned property.
     * This is derived from @link https://github.com/laravelbook/ardent
     *
     * @param string $relationName the relation key, camel-case version
     * @return \Illuminate\Database\Eloquent\Relations\Relation
     * @throws \InvalidArgumentException when the first param of the relation is not a relation type constant,
     *      or there's one or more arguments missing
     * @see BaseModel::relationsData
     */
    protected function handleRelationalArray($relationName) {
        $relation     = static::$relationsData[$relationName];
        $relationType = $relation[0];
        $errorHeader  = "Relation '$relationName' on model '".get_called_class();

        if (!in_array($relationType, static::$relationTypes)) {
            throw new InvalidArgumentException($errorHeader.
            ' should have as first param one of the relation constants of the BaseModel class.');
        }
        if (!isset($relation[1]) && $relationType != self::MORPH_TO) {
            throw new InvalidArgumentException($errorHeader.
            ' should have at least two params: relation type and classname.');
        }
        if (isset($relation[1]) && $relationType == self::MORPH_TO) {
            throw new InvalidArgumentException($errorHeader.
            ' is a morphTo relation and should not contain additional arguments.');
        }

        $verifyArgs = function (array $opt, array $req = array()) use ($relationName, &$relation, $errorHeader) {
            $missing = array('req' => array(), 'opt' => array());

            foreach (array('req', 'opt') as $keyType) {
                foreach ($$keyType as $key) {
                    if (!array_key_exists($key, $relation)) {
                        $missing[$keyType][] = $key;
                    }
                }
            }

            if ($missing['req']) {
                throw new InvalidArgumentException($errorHeader.'
                    should contain the following key(s): '.join(', ', $missing['req']));
            }
            if ($missing['opt']) {
                foreach ($missing['opt'] as $include) {
                    $relation[$include] = null;
                }
            }
        };

        switch ($relationType) {
            case self::HAS_ONE:
            case self::HAS_MANY:
            case self::BELONGS_TO:
                $verifyArgs(array('foreignKey'));
                return $this->$relationType($relation[1], $relation['foreignKey']);

            case self::BELONGS_TO_MANY:
                $verifyArgs(array('table', 'foreignKey', 'otherKey'));
                $relationship = $this->$relationType($relation[1], $relation['table'], $relation['foreignKey'], $relation['otherKey']);
                if(isset($relation['pivotKeys']) && is_array($relation['pivotKeys']))
                    $relationship->withPivot($relation['pivotKeys']);
                if(isset($relation['timestamps']) && $relation['timestamps']==true)
                    $relationship->withTimestamps();
                return $relationship;

            case self::MORPH_TO:
                $verifyArgs(array('name', 'type', 'id'));
                return $this->$relationType($relation['name'], $relation['type'], $relation['id']);

            case self::MORPH_ONE:
            case self::MORPH_MANY:
                $verifyArgs(array('type', 'id'), array('name'));
                return $this->$relationType($relation[1], $relation['name'], $relation['type'], $relation['id']);
        }
    }

    /**
     * Handle dynamic method calls into the method.
     * Overrided from {@link Eloquent} to implement recognition of the {@link $relationsData} array.
     * This is derived from @link https://github.com/laravelbook/ardent
     *
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters) {
        if (array_key_exists($method, static::$relationsData)) {
            return $this->handleRelationalArray($method);
        }

        return parent::__call($method, $parameters);
    }


    /**
     * Define an inverse one-to-one or many relationship.
     * Overriden from {@link Eloquent\Model} to allow the usage of the intermediary methods to handle the {@link
     * $relationsData} array.
     * This is derived from @link https://github.com/laravelbook/ardent
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $otherKey
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function belongsTo($related, $foreignKey = NULL, $otherKey = NULL, $relation = NULL) {
        $backtrace = debug_backtrace(false);
        $caller = ($backtrace[1]['function'] == 'handleRelationalArray')? $backtrace[3] : $backtrace[1];

        // If no foreign key was supplied, we can use a backtrace to guess the proper
        // foreign key name by using the name of the relationship function, which
        // when combined with an "_id" should conventionally match the columns.
        $relation = $caller['function'];

        if (is_null($foreignKey)) {
            $foreignKey = snake_case($relation).'_id';
        }

        // Once we have the foreign key names, we'll just create a new Eloquent query
        // for the related models and returns the relationship instance which will
        // actually be responsible for retrieving and hydrating every relations.
        $instance = new $related;
        
        $otherKey = $otherKey ?: $instance->getKeyName();
        
        $query = $instance->newQuery();

        return new BelongsTo($query, $this, $foreignKey, $otherKey, $relation);
    }

    /**
     * Define an polymorphic, inverse one-to-one or many relationship.
     * Overriden from {@link Eloquent\Model} to allow the usage of the intermediary methods to handle the {@link
     * $relationsData} array.
     * This is derived from @link https://github.com/laravelbook/ardent
     *
     * @param  string  $name
     * @param  string  $type
     * @param  string  $id
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function morphTo($name = null, $type = null, $id = null) {
        // If no name is provided, we will use the backtrace to get the function name
        // since that is most likely the name of the polymorphic interface. We can
        // use that to get both the class and foreign key that will be utilized.
        if (is_null($name))
        {
            $backtrace = debug_backtrace(false);
            $caller = ($backtrace[1]['function'] == 'handleRelationalArray')? $backtrace[3] : $backtrace[1];

            $name = snake_case($caller['function']);
        }

        // Next we will guess the type and ID if necessary. The type and IDs may also
        // be passed into the function so that the developers may manually specify
        // them on the relations. Otherwise, we will just make a great estimate.
        list($type, $id) = $this->getMorphs($name, $type, $id);

        $class = $this->$type;

        return $this->belongsTo($class, $id);
    }

    /**
     * Get an attribute from the model.
     * Overrided from {@link Eloquent} to implement recognition of the {@link $relationsData} array.
     * This is derived from @link https://github.com/laravelbook/ardent
     *
     * @param  string $key
     * @return mixed
     */
    public function getAttribute($key) {
        $attr = parent::getAttribute($key);

        if ($attr === null) {
            $camelKey = camel_case($key);
            if (array_key_exists($camelKey, static::$relationsData)) {
                $this->relations[$key] = $this->$camelKey()->getResults();
                return $this->relations[$key];
            }
        }

        return $attr;
    }

}
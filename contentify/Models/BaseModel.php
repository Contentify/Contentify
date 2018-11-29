<?php

namespace Contentify\Models;

use Contentify\ModelHandler;
use Contentify\Traits\SlugTrait;
use DateAccessorTrait;
use Eloquent;
use Exception;
use File;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Input;
use InterImage;
use InvalidArgumentException;
use ValidatingTrait;

/**
 * @property int $id
 */
class BaseModel extends Eloquent
{

    use ValidatingTrait;

    use DateAccessorTrait;

    use SlugTrait;

    /**
     * True if model is slugable
     *
     * @var bool
     */
    protected $slugable = false;

    /**
     * Name of the upload directory (null = name of class)
     *
     * @var string|null
     */
    protected $uploadDir = null;

    /**
     * Override this method to apply filters to a query of a model.
     * The Filter class provides methods to get values of filters.
     * 
     * @param  Builder $query Apply filters to this query
     * @return Builder
     */
    public function scopeFilter($query) {

    }

    /**
     * Path to uploaded files.
     * NOTE: When uploading files set $fileSystem = true!
     *
     * @param  bool $fileSystem If true, return a path of the file system (e. g. "C:\Contentify\public/uploads/games/")
     * @return string
     */
    public function uploadPath($fileSystem = false)
    {
        $class = class_basename(get_class($this));

        $dir = $this->uploadDir;
        if (! $dir) {
            $dir = strtolower(str_plural($class));
        }

        if ($fileSystem) {
            $base = public_path();
        } else {
            $base = asset('');
            
            if (ends_with($base, '/')) {
                $base = substr($base, 0, -1);
            }
        }

        return $base.'/uploads/'.$dir.'/';
    }

    /**
     * Tries to upload a file. It tries to delete the existing (old) file,
     * if instead of a file the string "." was sent.
     * Returns null or an error message (if an image file is invalid).
     * 
     * @param  string $fieldName  The name of the form field
     * @param  bool   $isImage    If true ensure the file is an image
     * @param  array  $thumbnails For images only: Array with closures or sizes of thumbnails
     * @return string|null
     */
    public function uploadFile($fieldName, $isImage = false, $thumbnails = array())
    {
        $file = Input::file($fieldName);

        if ($file == null and Input::get($fieldName) != '.') {
            return null;
        }
        
        if ($file) {
            $extension  = $file->getClientOriginalExtension();

            if ($isImage) {
                try {
                    $imgData = getimagesize($file->getRealPath()); // Try to gather infos about the image
                } catch (Exception $e) {
                    // Do nothing.
                }

                if (! in_array(strtolower($extension), ModelHandler::ALLOWED_IMG_EXTENSIONS)) {
                    return trans('app.invalid_image');
                }

                // Check if image has a size. If not, it's not an image. Does not work for SVGs.
                if (strtolower($extension) !== 'svg' and (! isset($imgData[2]) or ! $imgData[2])) {
                    return trans('app.invalid_image');
                }
            }
        }

        $filePath = $this->uploadPath(true);

        if (File::exists($filePath.$this->getOriginal($fieldName))) {
            File::delete($filePath.$this->getOriginal($fieldName)); // Delete the old file
        }

        if ($file) {
            $filename           = $this->id.'_'.$fieldName.'.'.$extension;
            $uploadedFile       = $file->move($filePath, $filename);
            $this->$fieldName   = $filename;
            $this->save();
        } else {
            $this->$fieldName   = '';
            $this->save();
        }

        if ($isImage) {
            foreach ($thumbnails as $thumbnail) {
                if (is_callable($thumbnail)) {
                    $thumbnail($file ? $filePath.$filename : null); // Let the closure handle the thumbnailing
                } else {
                    if (File::exists($filePath.$thumbnail.'/'.$this->getOriginal($fieldName))) {
                        File::delete($filePath.$thumbnail.'/'.$this->getOriginal($fieldName)); // Delete old thumbnail
                    }

                    if ($file) {
                        InterImage::make($filePath.'/'.$filename)->resize($thumbnail, $thumbnail, function ($constraint) 
                        {
                            /** @var \Intervention\Image\Constraint $constraint */
                            $constraint->aspectRatio(); // Keep the aspect ratio
                        })->save($filePath.$thumbnail.'/'.$filename);
                    }
                }
            }
        }

        return null;
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
        // One cannot edit or delete a deleted model
        return (! $this->isSoftDeleting() or ! $this->trashed());
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
     * class Order extends BaseModel
{
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

    /** This class "has one model" if its ID is an FK in that model */
    const HAS_ONE = 'hasOne';

    /** This class "has many models" if its ID is an FK in those models */
    const HAS_MANY = 'hasMany';

    const HAS_MANY_THROUGH = 'hasManyThrough';

    /** This class "belongs to a model" if it has a FK from that model */
    const BELONGS_TO = 'belongsTo';

    const BELONGS_TO_MANY = 'belongsToMany';

    const MORPH_TO = 'morphTo';

    const MORPH_ONE = 'morphOne';

    const MORPH_MANY = 'morphMany';

    const MORPH_TO_MANY = 'morphToMany';

    const MORPHED_BY_MANY = 'morphedByMany';

    /**
     * Array of relations used to verify arguments used in the {@link $relationsData}
     *
     * @var string[]
     */
    protected static $relationTypes = array(
        self::HAS_ONE, self::HAS_MANY, self::HAS_MANY_THROUGH,
        self::BELONGS_TO, self::BELONGS_TO_MANY,
        self::MORPH_TO, self::MORPH_ONE, self::MORPH_MANY,
        self::MORPH_TO_MANY, self::MORPHED_BY_MANY
    );

    /**
     * Getter for $relationsData
     * 
     * @return array
     */
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

        if (! in_array($relationType, static::$relationTypes)) {
            throw new InvalidArgumentException($errorHeader.
            ' should have as first param one of the relation constants of the BaseModel class.');
        }
        if (! isset($relation[1]) && $relationType != self::MORPH_TO) {
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
                    should contain the following key(s): '.implode(', ', $missing['req']));
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
                $verifyArgs(['foreignKey', 'localKey']);
                return $this->$relationType($relation[1], $relation['foreignKey'], $relation['localKey']);
            case self::HAS_MANY_THROUGH:
                $verifyArgs(['firstKey', 'secondKey', 'localKey'], ['through']);
                return $this->$relationType($relation[1], $relation['through'], $relation['firstKey'], $relation['secondKey'], $relation['localKey']);
            case self::BELONGS_TO:
                $verifyArgs(['foreignKey', 'otherKey', 'relation']);
                return $this->$relationType($relation[1], $relation['foreignKey'], $relation['otherKey'], $relation['relation']);
            case self::BELONGS_TO_MANY:
                $verifyArgs(['table', 'foreignKey', 'otherKey', 'relation']);
                $relationship = $this->$relationType($relation[1], $relation['table'], $relation['foreignKey'], $relation['otherKey'], $relation['relation']);
                if (isset($relation['pivotKeys']) && is_array($relation['pivotKeys'])) {
                    $relationship->withPivot($relation['pivotKeys']);
                }
                if (isset($relation['timestamps']) && $relation['timestamps']) {
                    $relationship->withTimestamps();
                }
                return $relationship;
            case self::MORPH_TO:
                $verifyArgs(['name', 'type', 'id']);
                return $this->$relationType($relation['name'], $relation['type'], $relation['id']);
            case self::MORPH_ONE:
            case self::MORPH_MANY:
                $verifyArgs(['type', 'id', 'localKey'], ['name']);
                return $this->$relationType($relation[1], $relation['name'], $relation['type'], $relation['id'], $relation['localKey']);
            case self::MORPH_TO_MANY:
                $verifyArgs(['table', 'foreignKey', 'otherKey', 'inverse'], ['name']);
                return $this->$relationType($relation[1], $relation['name'], $relation['table'], $relation['foreignKey'], $relation['otherKey'], $relation['inverse']);
            case self::MORPHED_BY_MANY:
                $verifyArgs(['table', 'foreignKey', 'otherKey'], ['name']);
                return $this->$relationType($relation[1], $relation['name'], $relation['table'], $relation['foreignKey'], $relation['otherKey']);
        }
    }

    /**
     * Handle dynamic method calls into the method.
     * Overridden from {@link Eloquent} to implement recognition of the {@link $relationsData} array.
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
     * Overridden from {@link Eloquent\Model} to allow the usage of the intermediary methods to handle the {@link
     * $relationsData} array.
     * This is derived from @link https://github.com/laravelbook/ardent
     *
     * @param  string  $related
     * @param  string  $foreignKey
     * @param  string  $otherKey
     * @param  string  $relation
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function belongsTo($related, $foreignKey = NULL, $otherKey = NULL, $relation = NULL) {
        // If no foreign key was supplied, we can use a backtrace to guess the proper
        // foreign key name by using the name of the relationship function, which
        // when combined with an "_id" should conventionally match the columns.
        if (is_null($relation)) {
            $backtrace = debug_backtrace(false, 4);
            if ($backtrace[1]['function'] == 'handleRelationalArray') {
                $relation = $backtrace[1]['args'][0];
            } else {
                $relation = $backtrace[3]['function'];
            }
        }

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
     * Overridden from {@link Eloquent\Model} to allow the usage of the intermediary methods to handle the {@link
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
     * Overridden from {@link Eloquent} to implement recognition of the {@link $relationsData} array.
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

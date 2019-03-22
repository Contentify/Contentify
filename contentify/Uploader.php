<?php

namespace Contentify;

use Exception;
use File;
use Input;
use InterImage;

/**
 * This class is the centralized place to handle file uploads
 */
class Uploader
{
    /**
     * Array that contains all allowed file extensions for image file uploads
     */
    const ALLOWED_IMG_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];
    
    /**
     * Upload files (and images) for a given model.
     * The model must support this.
     * Returns an array with errors.
     *
     * @param object $model The instance of the model the client wants to upload files for
     * @param bool $modelIsNew True if an existing model is being edited
     * @return array
     */
    public function uploadModelFiles($model, $modelIsNew = true) : array
    {
        $modelClass = getclass($model);
    
        if (isset($modelClass::$fileHandling) and sizeof($modelClass::$fileHandling) > 0) {
            foreach ($modelClass::$fileHandling as $fieldName => $fieldInfo) {
                if (! is_array($fieldInfo)) {
                    $fieldName = $fieldInfo;
                    $fieldInfo = ['type' => 'file'];
                }

                if (Input::hasFile($fieldName)) {
                    $file       = Input::file($fieldName);
                    $extension  = $file->getClientOriginalExtension();
                    $error      = false;

                    if (strtolower($fieldInfo['type']) == 'image') {
                        try {
                            $imgData = getimagesize($file->getRealPath());
                        } catch (Exception $e) {
                            // Do nothing
                        }

                        if (! in_array(strtolower($extension), self::ALLOWED_IMG_EXTENSIONS)) {
                            $error = trans('app.invalid_image');
                        }

                        // Check if image has a size. If not, it's not an image. Does not work for SVGs.
                        if (strtolower($extension) !== 'svg' and (! isset($imgData[2]) or ! $imgData[2])) {
                            $error = trans('app.invalid_image');
                        }
                    }

                    if (in_array(strtolower($extension), $controller->getEvilFileExtensions())) {
                        $error = trans('app.bad_extension', [$extension]);
                    }

                    if ($error !== false) {
                        $model->delete(); // Delete the invalid model
                        return [$error];
                    }
                    
                    if (! $modelIsNew) {
                        $oldFile = $model->uploadPath(true).$model->$fieldName;
                        if (File::isFile($oldFile)) {
                            File::delete($oldFile); // Delete the old file so we never have "123.jpg" AND "123.png"
                        }
                    }
                    
                    $filePath           = $model->uploadPath(true);
                    $filename           = $model->id.'_'.$fieldName.'.'.$extension;
                    $uploadedFile       = $file->move($filePath, $filename);
                    $model->$fieldName  = $filename;
                    $model->forceSave(); // Save model again, without validation

                    /*
                     * Create thumbnails for images
                     */
                    if (isset($fieldInfo['thumbnails'])) {
                        $thumbnails = $fieldInfo['thumbnails'];
                        
                        // Ensure $thumbnails is an array:
                        if (! is_array($thumbnails)) {
                            $thumbnails = compact('thumbnails'); // Ensure $thumbnails is an array
                        }

                        foreach ($thumbnails as $thumbnail) {
                            InterImage::make($filePath.'/'.$filename)
                                ->resize($thumbnail, $thumbnail, function ($constraint) {
                                    /** @var \Intervention\Image\Constraint $constraint */
                                    $constraint->aspectRatio();
                                })->save($filePath.$thumbnail.'/'.$filename); 
                        }
                    }
                } else {
                    if ($modelIsNew) {
                        // Ignore missing files
                    } else {
                         // We use the filename '.' to signalize we want to delete the file.
                        // (A file cannot be named "." in Linux.)
                        if (Input::get($fieldName) == '.') {
                            $oldFile = $model->uploadPath(true).$model->$fieldName;
                            if (File::isFile($oldFile)) {
                                File::delete($oldFile);
                            }
                            $model->$fieldName  = '';
                            $model->forceSave(); // Save model again, without validation
                        }
                    }
                }
                
                return [];
            }
        }
    }
    
    /**
     * Deletes all files releted to a given model
     *
     * @param object $model
     * @return void
     */
    public function deleteModelFiles($model)
    {
        $modelClass = getclass($model);
        
        if ((! method_exists($modelClass, 'trashed') or ! $model->trashed()) 
            and isset($modelClass::$fileHandling) and sizeof($modelClass::$fileHandling) > 0) {
            
            $filePath = $model->uploadPath(true);

            foreach ($modelClass::$fileHandling as $fieldName => $fieldInfo) {
                if (! is_array($fieldInfo)) {
                    $fieldName = $fieldInfo;
                    $fieldInfo = ['type' => 'file'];
                }

                File::delete($filePath.$model->$fieldName);

                /*
                 * Delete image thumbnails
                 */
                if (strtolower($fieldInfo['type']) == 'image' and isset($fieldInfo['thumbnails'])) {
                    $thumbnails = $fieldInfo['thumbnails'];
                    if (! is_array($thumbnails)) {
                        $thumbnails = compact('thumbnails'); // Ensure $thumbnails is an array
                    }

                    foreach ($thumbnails as $thumbnail) {
                        $filename = $filePath.$thumbnail.'/'.$model->$fieldName;
                        if (File::isFile($filename)) {
                            File::delete($filename);
                        }
                    }
                }
            }
        }
    }
}

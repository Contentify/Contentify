<?php

namespace Contentify;

use DateTime;
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
     * Array with "evil" file extensions - files with this extensions are not allowed to be uploaded
     */
    const FORBIDDEN_FILE_EXTENSIONS = ['php'];
    
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

                    if (in_array(strtolower($extension), self::FORBIDDEN_FILE_EXTENSIONS)) {
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
                    $filename           = $this->generateFilename($filePath, $extension);
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
    
    /**
     * Generates a filename for the new uploaded file.
     * The filename will be randomized (via hashing) and unique.
     * To verify its uniqueness the path and extension have to be passed.
     *
     * @param string $filePath      Directory where the file will be moved to
     * @param string $fileExtension Desired extension of the file
     * @return string
     */
    public function generateFilename(string $filePath, string $fileExtension = '') : string
    {
        // See: stackoverflow.com/questions/4371941/best-method-to-generate-unique-filenames-when-uploading-files-php
        do {
            $date = DateTime::createFromFormat('U.u', microtime(true));
            $filename = md5($date->format('Y-m-d H:i:s.u'))); 
        } while (file_exists($filename))
            
        return $filename;
    }
}

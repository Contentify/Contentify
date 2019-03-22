<?php

namespace Contentify;

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
     * File (and image) handling
     */
    public function uploadModelFiles($model)
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
                    // Ignore missing files
                }
            }
        }
    }
}

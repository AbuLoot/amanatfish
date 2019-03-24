<?php

namespace App;

use Image;

trait ImageTrait {

    public function resizeImage($image, $width, $height, $path, $quality, $watermark = null, $color = '#ffffff')
    {
        $frame = Image::canvas($width, $height, $color);
        $newImage = Image::make($image);

        if ($newImage->width() <= $newImage->height()) {
            $newImage->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        else {
            $newImage->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        if ($newImage->width() > $width OR $newImage->height() > $height) {
            $newImage->crop($width, $height);
        }

        // if ($width == 300) {
        //     // draw transparent rectangle with 2px border
        //     $frame->rectangle(1, 0, 299, 199, function ($draw) {
        //         $draw->border(1, '#e3ce8a');
        //     });
        // }

        $frame->insert($newImage, 'center');

        if ($watermark != null) {
            $frame->insert($watermark, 'bottom-left', 65, 65);
        }

        $frame->save(public_path().$path, $quality);
    }

    public function resizeMiniImage($image, $width, $height, $path, $quality, $color = '#ffffff')
    {
        $frame = Image::canvas($width, $height, $color);
        $newImage = Image::make($image);

        $newImage->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
        });

        if ($newImage->width() > $width OR $newImage->height() > $height) {
            $newImage->crop($width, $height);
        }

        $frame->insert($newImage, 'center');

        $frame->save(public_path().$path, $quality);
    }

    public function cropImage($image, $width, $height, $path, $quality)
    {
        $newImage = Image::make($image);

        if ($newImage->width() > $newImage->height()) {
            $newImage->resize(null, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        else {
            $newImage->resize($width, null, function ($constraint) {
                $constraint->aspectRatio();
            });
        }

        if ($newImage->width() > $width OR $newImage->height() > $height) {
            $newImage->crop($width, $height);
        }

        $newImage->save(public_path().$path, $quality);
    }
}
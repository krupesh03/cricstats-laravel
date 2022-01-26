<?php

namespace App\Helpers;

class FunctionHelper
{
    public function setImage( $imagePath = '' ) {

        if( file_exists($imagePath) ) {
            return $imagePath;
        }
        $nImagePath = explode('https://', $imagePath);
        if( isset($nImagePath[1]) ) {
            $mImagePath =  explode('/', $nImagePath[1]);
            if( count($mImagePath) > 1 ) {
                return $imagePath;
            }
        }
        return url('assets/images/dummy/dummy.jpg');

    }
}
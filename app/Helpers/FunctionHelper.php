<?php

namespace App\Helpers;

class FunctionHelper
{
    public function setImage( $imagePath = '' ) {

        if( file_exists($imagePath) ) {
            return $imagePath;
        }
        return url('assets/images/dummy/dummy.jpg');

    }
}
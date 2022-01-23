<?php

namespace App\Helpers;

class FunctionHelper
{
    public function setImage( $imagePath = '' ) {

        if( $imagePath ) {
            return $imagePath;
        }
        return url('assets/images/dummy/dummy.jpg');

    }
}
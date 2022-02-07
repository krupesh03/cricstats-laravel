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

    public function calculateBallsFromOvers( $overs = 0 ) {

        if( $overs ) {
            $whole = floor( $overs );      
            $fraction = $overs - $whole; 
            $numberOfBalls = ($whole * 6 ) + ($fraction * 10);
            return (int)$numberOfBalls;
        }
        return 0;
    }
}
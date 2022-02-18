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

    public function batterOnStrike( $ball = [], $batsman = 0 ) {

        if( !$batsman ) return false;

        $runs = 0;
        if( $ball['score']['ball'] ) { //if valid ball
            if( $ball['score']['leg_bye'] ) {
                $runs = (int)$ball['score']['leg_bye'];
            } elseif( $ball['score']['bye'] ) {
                $runs = (int)$ball['score']['bye'];
            } else {
                $runs = (int)$ball['score']['runs'];
            }
        } else {
            if( $ball['score']['noball'] ) {
                $runs = (int)$ball['score']['runs'] > 1 ? ((int)$ball['score']['runs'] - (int)$ball['score']['noball_runs']) : 0 ;
            }
        }
        if( strpos($ball['ball'], '.6') !== false ) { //if last ball
            if( (int)$runs%2 !== 0 ) { //if odd number scored on last ball
                return $ball['batsman']['id'] == $batsman; 
            } else {
                return $ball['batsman']['id'] !== $batsman;
            }
        }

        if( (int)$runs%2 !== 0 ) { //during current over
            return $ball['batsman']['id'] !== $batsman; 
        } else {
            return $ball['batsman']['id'] == $batsman;
        }
    }

    public function calculateOversFromBalls( $balls = 0 ) {

        if( $balls ) {

            $overs = floor( $balls / 6 );
            $ball = $balls - ( $overs * 6 );
            if( $ball ) {
                $overs .= '.' . $ball;
            }
            return $overs;
        }
        return 0;
    }
}
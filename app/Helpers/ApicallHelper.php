<?php

namespace App\Helpers;

use Config;

class ApicallHelper
{
    public function __construct() {

        $this->api_token = env('API_TOKEN');
        $this->api_url = Config::get('constants.API_URL');
    }

    public function getDataFromAPI( $apiEndpoint = '', $queryStrs = [] ) {

        if( !$apiEndpoint ) {
            return [
                'success'   => false,
                'data'      => "No endpoint received"
            ];
        }

        $apiUrl = $this->api_url . $apiEndpoint . '?api_token=' . $this->api_token;
        foreach( $queryStrs as $query => $value ) {
            $apiUrl .= '&' . $query . '=' . $value;
        }

        $params = [
            CURLOPT_URL             => $apiUrl,
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_ENCODING        => "",
            CURLOPT_TIMEOUT         => 30000,
            CURLOPT_HTTP_VERSION    => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST   => "GET",
            CURLOPT_HTTPHEADER      => [
                'Content-Type: application/json', 
            ]
        ];
        if( env('SSL_VERIFY') === false ) {
            $params[CURLOPT_SSL_VERIFYPEER] =  env('SSL_VERIFY');
            $params[CURLOPT_SSL_VERIFYHOST] =  env('SSL_VERIFY');
        }

        $curl = curl_init();
        curl_setopt_array( $curl, $params );
        $response = curl_exec( $curl );
        $err = curl_error( $curl );
        curl_close( $curl );
        if ( $err ) {
            return [
                'success'   => false,
                'data'      => "cURL Error #:" . $err
            ];
        }
        $data = json_decode( $response, true );
        if( isset($data['data']) ) {
            return [
                'success'   => true,
                'data'      => $data
            ];
        }
        return [
            'success'   => false,
            'data'      => "Invalid Api Endpoint"
        ];
    }
}
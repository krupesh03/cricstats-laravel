<?php

namespace App\Helpers;

use Config;

class ApicallHelper
{
    public function __construct() {

        $this->api_token = env('API_TOKEN');
        $this->api_url = Config::get('constants.API_URL');
        $this->rapid_api_news = Config::get('constants.RAPID_API_NEWS');
    }

    public function getDataFromAPI( $apiEndpoint = '', $queryStrs = [] ) {

        if( !$apiEndpoint ) {
            return [
                'data'      => "No endpoint received",
                'success'   => false
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
                'data'      => str_replace('cricket.sportmonks.com', 'Possibly network error.', $err),
                'success'   => false
            ];
        }
        $data = json_decode( $response, true );
       
        if( isset($data['data']) ) {
            $data['success'] = true;
            return $data;
        }
        return [
            'data'      => "Invalid Api Endpoint",
            'success'   => false
        ];
    }

    public function getNewsDataFromAPI() {

        if( empty($this->rapid_api_news['API_URL']) || empty($this->rapid_api_news['HTTPHEADER']) ) {
            return [
                'data'      => 'API Error',
                'success'   => false
            ];
        }

        $curl = curl_init();
        $params =  [
            CURLOPT_URL => $this->rapid_api_news['API_URL'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => $this->rapid_api_news['HTTPHEADER'],
        ];
        if( env('SSL_VERIFY') === false ) {
            $params[CURLOPT_SSL_VERIFYPEER] =  env('SSL_VERIFY');
            $params[CURLOPT_SSL_VERIFYHOST] =  env('SSL_VERIFY');
        }
        curl_setopt_array($curl, $params);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ( $err ) {
            return [
                'data'      => $err,
                'success'   => false
            ];
        }
        $data = json_decode( $response, true );
       
        if( $data ) {
            return [
                'data'      => $data,
                'success'   => true
            ];
        }
        return [
            'data'      => 'API Error',
            'success'   => false
        ];
    }
}
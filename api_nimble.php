<?php

/*
 * Nimble CRM API Class v0.2
 * http://viktorixinnovative.com/
 *
 * Copyright 2013 Viktorix Innovative (email: support@viktorixinnovative.com)
 * Copyright 2015 Okay Plus (email: joeydi@okaypl.us)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
*/


class NimbleAPI {

    public function __construct() {
        $this->config = array(
            'api_key' => get_option('nimble_client_id'),
            'secret_key' => get_option('nimble_client_secret'),
            'redirect_uri' => get_option('nimble_redirect_uri')
        );
    }

    public function nimble_get_auth_code() {
        $handle = 'https://api.nimble.com/oauth/authorize?client_id='.$this->config['api_key'].'&redirect_uri='.$this->config['redirect_uri'].'&response_type=code';
        header("Location: $handle");
    }

    public function nimble_get_access_token($code) {
        $authen_code = $code;
        $url         = 'https://api.nimble.com/oauth/token';
        $method      = 'POST';
        $data        = 'client_id='.$this->config['api_key'].'&client_secret='.$this->config['secret_key'].'&redirect_uri=' . $this->config['redirect_uri'] .'&code=' . $authen_code . '&grant_type=authorization_code';
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8'
        );
        $response_data = $this->nimble_request($url, $method, $data, $headers);

        return $response_data;
    }

    public function nimble_refreshtoken_get_access_token() {
        $request_token = get_option('nimble_refresh_token');
        $url           = 'https://api.nimble.com/oauth/token';
        $method        = 'POST';
        $data          = 'client_id='.$this->config['api_key'].'&client_secret='.$this->config['secret_key'].'&redirect_uri=' . $this->config['redirect_uri'] .'&refresh_token=' . $request_token . '&grant_type=refresh_token';
        $headers = array(
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded; charset=UTF-8'
        );
        $response_data = $this->nimble_request($url, $method, $data,$headers);

        return $response_data[1]->access_token;
    }

    public function nimble_search_contact($emailaddress) {
        $access_token =  get_option('nimble_access_token');
        $method = 'GET';
        $data = '';

        $url = 'https://api.nimble.com/api/v1/contacts/list?' . http_build_query(array(
        'access_token' => $access_token,
        'query' => '{"email":{"is":"' . $emailaddress . '"}}',
        'fields'=> 'first name'
        ));

       $headers = array(
            'Accept: application/json',
            'Content-Type: application/json'
        );
        $response_data = $this->nimble_request($url, $method, $data,$headers);

        if (($response_data[0] == 401) && ($response_data[1]->Error == 'Access Token is invalid or expired.')) {
            $access_token = $this->nimble_refreshtoken_get_access_token();
                    update_option('nimble_access_token',$access_token);

            $this->nimble_search_contact( $emailaddress);
        } else if (($response_data[0] == 200) && ($response_data[1]->meta->total == 0)) {
            return "OK";
        } else if (($response_data[0] == 200) && ($response_data[1]->meta->total != 0)) {
            return "Email Already Exist";
        } else if (($response_data[0] != 200)) {
            if ($response_data[1] == '') {
                return "syntax error";
            } else {
                return $response_data[1]. "error". $response_data[0] ;
            }
        }
    }

    public function nimble_add_contact( $firstname, $lastname, $emailaddress, $phone_work, $phone_mobile, $title ) {
        $response_status = $this->nimble_search_contact($emailaddress);
        $access_token =  get_option('nimble_access_token');

        if ($response_status == 'OK') {
            $url = 'https://api.nimble.com/api/v1/contact?' . http_build_query(array( 'access_token' => $access_token));
            $method = 'POST';
            $fields = array();
            $data = array( 'type' => 'person' );

            if ( get_option('CHKN_Fname') == 'on' ) {
                $fields['first name'] = array();
                array_push( $fields['first name'], array(
                    'value' => $firstname,
                    'modifier' => '',
                ) );
            }

            if ( get_option('CHKN_Lname') == 'on' ) {
                $fields['last name'] = array();
                array_push( $fields['last name'], array(
                    'value' => $lastname,
                    'modifier' => '',
                ) );
            }

            if ( get_option('CHKN_title') == 'on' ) {
                $fields['title'] = array();
                array_push( $fields['title'], array(
                    'value' => $title,
                    'modifier' => '',
                ) );
            }

            if ( get_option('CHKN_email') == 'on' ) {
                $fields['email'] = array();
                array_push( $fields['email'], array(
                    'value' => $emailaddress,
                    'modifier' => 'personal',
                ) );
            }

            if ( get_option('CHKN_phone_work') == 'on' || get_option('CHKN_phone_mobile') == 'on' ) {
                $fields['phone'] = array();
            }

            if ( get_option('CHKN_phone_work') == 'on' ) {
                array_push( $fields['phone'], array(
                    'value' => $phone_work,
                    'modifier' => 'work',
                ) );
            }

            if ( get_option('CHKN_phone_mobile') == 'on' ) {
                array_push( $fields['phone'], array(
                    'value' => $phone_mobile,
                    'modifier' => 'mobile',
                ) );
            }

            if ( get_option('N_tags') ) {
                $data['tags'] = get_option('N_tags');
            }

            $data['fields'] = $fields;

            $headers = array(
                'Accept: application/json',
                'Content-Type: application/json'
            );

            $response_data = $this->nimble_request($url, $method, json_encode($data), $headers);

            return $response_data;
        } else {

        }
    }

    public function nimble_request($url, $method, $data, $headers) {
        $handle = curl_init();

        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($handle, CURLOPT_VERBOSE, FALSE);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($handle, CURLOPT_TIMEOUT, 90);

        switch ($method) {
            case 'GET':
                break;

            case 'POST':
                curl_setopt($handle, CURLOPT_POST, true);
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                break;

            case 'PUT':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
                break;

            case 'DELETE':
                curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
                break;
        }

        $response = curl_exec($handle);
        $code     = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        $header_size = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        $body        = substr($response, $header_size);
        $error       = json_decode($body);

        $response_data    = Array();
        $response_data[0] = $code;
        $response_data[1] = $error;

        return $response_data;
    }
}

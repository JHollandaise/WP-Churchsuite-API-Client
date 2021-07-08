<?php
/**
 * Plugin Name: ChurchSuite API Client
 * Plugin URI: github.com/jhollandaise/wp-churchsuite-api-client
 * Description: Facilitates structured communication with the ChurchSuite REST API
 * Version: 0.0.1
 * Author: Joseph Holland
 * Author URI: jhol.land
 * Licence: GPLv2 
 * Licene URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html 
 */

function get_x_headers() {
    // parse in the x_headers required to make any request to the ChurchSuite API
    $x_headers = json_decode(file_get_contents("x_headers.json"));
    foreach(array("X_Account", "X_Application", "X_Auth") as $header_name) {
        if(!array_key_exists($header_name,$x_header)) {
            throw new exception("Missing ".$header_name." Header in x_headers.json");
        }
    }
    return $x_headers;
}


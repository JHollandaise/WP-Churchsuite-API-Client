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

/**
 * read in the X-headers required to make any request to the ChurchSuite API
 * @param string $filename JSON file where X-headers are stored (in working directory)
 * @return array $x_headers Array containing the parsed X-headers
 */
function get_x_headers(string $filename) : array{
    $x_headers = json_decode(file_get_contents($filename));
    foreach(array("X_Account", "X_Application", "X_Auth") as $header_name) {
        if(!array_key_exists($header_name,$x_header)) {
            throw new exception("Missing ".$header_name." header in x_headers.json");
        }
    }
    return $x_headers;
}


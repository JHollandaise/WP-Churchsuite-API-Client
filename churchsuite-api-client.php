<?php
/**
 * Plugin Name: ChurchSuite API Client
 * Plugin URI: github.com/jhollandaise/wp-churchsuite-api-client
 * Description: Facilitates structured communication with the ChurchSuite REST API
 * Version: 0.0.1
 * Author: Joseph Holland
 * Author URI: jhol.land
 * Licence: GPLv2 
 * Licence URI: https://www.gnu.org/licenses/old-licenses/gpl-2.0.html 
 */

/**
 * read in the X-headers required to make any request to the ChurchSuite API
 * @return array $x_headers Array containing the parsed X-headers
 */
function get_x_headers() : array{
    $x_headers = json_decode(file_get_contents("x_headers.json"));
    foreach(array("X_Account", "X_Application", "X_Auth") as $header_name) {
        if(!array_key_exists($header_name,$x_header)) {
            throw new exception("Missing ".$header_name." header in x_headers.json");
        }
    }
    return $x_headers;
}

// now we want to start building our request 
// so ultimately the user wants to pull a certain cross-section of information from the CS database with a given request. 
// and the request that we build wants to gather that info with as few sever requests as possible
//
// The form for the request builder should just be as easy as a few tick-boxes specifying the desired information which then all get bundled as a single response (irrespective of how many actual server request/responses occur under the hood)
//
// 
// Additionally, given that we are building this Plug-in for a single application (communitygroups signup) at this time, the information that can be grabbed will be limited to the relevent sub-set for that application

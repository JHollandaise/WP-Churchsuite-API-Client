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

namespace cs_api_client;

define("CSAPI_ROOT_URL","https://api.churchsuite.co.uk/v1/");
define("CSAPI_X_HEADERS_FILE","x_headers.json");

/**
 * Read in the X-headers required to make any request to the ChurchSuite API
 * @return array $x_headers Array containing the parsed X-headers
 */
function get_x_headers(string $filename) : array{
    $x_headers = json_decode(file_get_contents(dirname(__FILE__) . "/$filename"),
                    true);
    foreach(array("X-Account", "X-Application", "X-Auth") as $header_name) {
        if(!array_key_exists($header_name, $x_headers)) {
            throw new \Exception(
                "Missing $header_name in x_headers.json");
        }
    }
    return $x_headers;
}

/**
 * Handle Incomming API Requests on `wp_loaded`
 */
function main_loop() {

    $requests = apply_filters(__NAMESPACE__ . '\\request_submissions', []);
    if(array_unique($requests)<>$requests)
            throw new \Exception ("Duplicate API requests present");

    $requests_to_make = array_filter($requests, function($x)
                        {return get_transient($x['callback'])!==$x;});
    foreach($requests_to_make as $request) {
        add_action(__NAMESPACE__."\\".$request['method']."_".$request['url'],
                $request['callback'], $request['priority'], 1);
    }

    $request_headers = array_merge(get_x_headers(CSAPI_X_HEADERS_FILE),
            ["Content_Type" => "application/json"]);
    $unique_requests = array_unique(array_map(function($x)
                        {return ['url'=>$x['url'], 'method'=>$x['method']];},
                        $requests_to_make));
    foreach($unique_requests as $request) {
        if($request['method']=='GET') {
            // TODO: add HEADER request for update checking
            $response = wp_remote_get(CSAPI_ROOT_URL . $request['url'],
                ['headers' => $request_headers]);
        }
        // TODO: add POST request
        else throw new \Exception(
                "Invalid request method: ".$request['method']);
        do_action(__NAMESPACE__."\\".$request['method']."_".$request['url'],
                $response);
    }

    // NOTE: this must be done as WP Transients treat (transient->expiration)==0
    // as never expire (rather than expire immediately)
    $requests_to_cache = array_filter($requests_to_make, function($x)
                            {return $x['period']<>0;});
    foreach($requests_to_cache as $request) {
        set_transient($request['callback'], $request, $request['period']);
    }

    $request_caches_to_clear = apply_filters(
                                __NAMESPACE__ . '\\cache_clear_callback', []);
    foreach($request_caches_to_clear as $request_cache) {
        delete_transient($request_cache);
    }
}
add_action('wp_loaded', __NAMESPACE__ . '\\main_loop');

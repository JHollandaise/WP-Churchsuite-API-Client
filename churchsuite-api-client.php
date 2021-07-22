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

function activate() {
    // create the periodic requests table if it doesn't already exist
    global $wpdb;

    $table_name = $wpdb->prefix . "cs_api_client_periodic_requests";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        url varchar(100) NOT NULL,
        callback varchar(100) NOT NULL,
        periodic boolean NOT NULL,
        PRIMARY KEY  (id)
        ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
    // TODO: add periodic requests funciton to cron scheduler (remove
}
register_activation_hook(__FILE__, __NAMESPACE__.'\activate');
// on disable)

// TODO: on disable, removeperiodic requests function from cron scheduler
// TODO: remove periodic requests table on uninstall

/**
 * read in the X-headers required to make any request to the ChurchSuite API
 * @return array $x_headers Array containing the parsed X-headers
 */
function get_x_headers(string $filename) : array{
    $x_headers = json_decode(file_get_contents($filename));
    foreach(array("X_Account", "X_Application", "X_Auth") as $header_name) {
        if(!array_key_exists($header_name, $x_header)) {
            throw new exception(
                "Missing $header_name in x_headers.json");
        }
    }
    return $x_headers;
}

/**
 * main function handles updating periodic requests table and making
 * instantaneous requests
 */
function main_loop() {
    $request_headers = array_merge(get_x_headers(CSAPI_X_HEADERS_FILE),
            ["Content_Type" => "application/json"];

    $requests = apply_filter(__NAMESPACE__ . 'get_requests', []);
    if(array_unique($requests)<>$requests)
            throw new exception ("Duplicate API requests present");

    $stored_periodic_requests = get_stored_periodic_requests(); 
    update_periodic_requests($stored_periodic_requests, $requests);

    // instantaneous requests are all non-periodic and any newly added periodic
    // requests
    $instantaneous_requests = array_diff($requests, $stored_periodic_requests);
    make_requests($request_headers, $instantaneous_requests);
}
add_action('wp_loaded', __NAMESPACE__ . 'main_loop');

function update_periodic_requests(array $stored_periodic_requests,
        array $requests) {
    // get all non-periodic requests and add to $instantaneous_requests and
    // remove from requests    

    // set comparison periodic_requests<->requests
    // lhs: remove from table
    array_diff($stored_periodic_requests, $requests);
    // rhs: add to table
    array_diff($requests, $stored_periodic_requests);
}

/**
 * makes a set of requests to the ChurchSuite API server and handles the
 * responses
 */
function make_requests(array $request_headers, array $requests) {
    foreach($requests as $request) {
        // TODO: make requests and handle response
        // includes sending the response over the the update handler
    }
}


function make_request($request_url) {
   // TODO: make request and handle response 
}

<?php
/**
 * @package Scapa
 * @version 0.0.1
 */
/*
Plugin Name: Scapa 
Plugin URI: 
Description: Scapa plugin
Author: Lekan 
Version: 0.0.1
Author URI: https://scapa.io/
*/

require_once 'setup.php';

add_action('rest_api_init', function () {

    register_rest_route('endpoint', '/payment_callback', [

        'methods' => 'POST',
        'callback' => 'payment_callback'

    ]);
});


add_action('scapa_insert_invoice', 'scapa_insert_data');

register_activation_hook(__FILE__, 'scapa_install');

$args = array(
    'author' => 6, // id
    'posts_per_page' => 1, // max posts
);


add_action("wpcf7_before_send_mail", function () use ($args) {
    // scapa_insert_data($args);
    $wpcf = WPCF7_ContactForm::get_current();

    do_action('scapa_insert_invoice', $wpcf);

});



function wpcf7_do_something_else($cf7)
{
    // get the contact form object
    $wpcf = WPCF7_ContactForm::get_current();



    return $wpcf;
}


function payment_test($test)
{


}
;


function payment_callback($request)
{

    $json = $request->get_json_params();
    $json['test'] = 'jan';
    return $json;
}



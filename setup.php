<?php

function scapa_install()
{
    global $wpdb;

    $table_name = $wpdb->prefix . "invoice";

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        `id` int(11) NOT NULL,
        `reference` varchar(500) NOT NULL,
        `reference_source` varchar(250) NOT NULL,
        `amount` decimal(10,0) NOT NULL,
        `payment_type` varchar(20) NOT NULL,
        `description` varchar(500) NOT NULL,
        `customer_id` varchar(500) NOT NULL,
        `transaction_date` datetime NOT NULL DEFAULT current_timestamp()
      ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

}



function scapa_insert_data($args)
{

    // print_r(json_encode($args));
    $wpcf = WPCF7_ContactForm::get_current();

   

    $reference = "wdfverg";
    // $reference = isset($args['reference']) ? $args['reference'] : 'fgerger';
    // $reference_source = isset($args['reference_source']) ? $args['reference_source'] : 'wgwerg';
    // $amount = isset($args['amount']) ? $args['amount'] : 0;
    // $payment_type = isset($args['payment_type']) ? $args['payment_type'] : 'rgerg';
    // $description = isset($args['description']) ? $args['description'] : 'greg';
    // $customer_id = isset($args['customer_id']) ? $args['customer_id'] : 'rgergrg';


    global $wpdb;

    $table_name = $wpdb->prefix . "invoice";

    $wpdb->insert(
        $table_name,
        array(
            'reference' => json_encode($args),
            'reference_source' => "reference",
            'amount' => 100,
            'payment_type' => "reference",
            'description' => "reference",
            'customer_id' => "reference",
            'transaction_date' => current_time('mysql'),
        )
    );
}
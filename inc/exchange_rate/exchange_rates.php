<?php 
//  error_reporting(E_ALL);
//  ini_set("display_errors", 1);

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);
set_time_limit(0);
ignore_user_abort(1);

require_once($_SERVER["DOCUMENT_ROOT"]. '/wp-load.php');

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://openexchangerates.org/api/latest.json?app_id=15b5aae4cbee4d93942d907f1e6a24e0&base=USD&symbols=CAD',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);
curl_close($curl);

$data = json_decode($response);
$rates = $data->rates;
$cadRate = $rates->CAD;
$base = $data->base;
$timestamp = $data->timestamp;

global $wpdb;

$table_name      = $wpdb->prefix . "exchange_rates";
$charset_collate = $wpdb->get_charset_collate();

//create table only if need
// $sql = "DROP TABLE IF EXISTS $table_name;";
// $wpdb->query($sql);

//create table only if doesnot exist
if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
    $table_query = "CREATE TABLE $table_name (
                id mediumint(11) NOT NULL AUTO_INCREMENT,
                cad_rate varchar(100) NOT NULL,
                exchange_base varchar(100) NOT NULL,
                exchange_date varchar(100) NULL,
                PRIMARY KEY  (id)
            ) $charset_collate;";

    require_once($_SERVER["DOCUMENT_ROOT"]. '/wp-admin/includes/upgrade.php');
    dbDelta( $table_query );
} 

$exchangeData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name"));

if (!empty($exchangeData)) {
    $id = $exchangeData[0]->id;
    $update_query= "UPDATE $table_name SET cad_rate = $cadRate, exchange_base = '$base', exchange_date = $timestamp WHERE id = $id";
    $wpdb->query($update_query);  
    $exchangeUpdatedData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name"));
    echo "<pre>";
    print_r($exchangeUpdatedData);
    echo "</pre>";
    echo "Exchange cad rate Updated on ". date("d-m-Y h:i:s"); 
}else{
    $insert_query= "INSERT INTO $table_name (cad_rate, exchange_base, exchange_date) VALUES  ($cadRate, '$base', $timestamp)";
    $wpdb->query($insert_query);

    $exchangeFirstData = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name"));
    echo "<pre>";
    print_r($exchangeFirstData);
    echo "</pre>";
    echo "Exchange cad rate Inserted on ". date("d-m-Y h:i:s"); 
}
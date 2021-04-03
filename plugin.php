<?php
/*
Plugin Name: Product Calculate and Shipping Cargo
Plugin URI: https://aloha-tech.com/
Description: Mike Hsu
Author: Mike Hsu
Version: 1.7.2
Author URI: https://aloha-tech.com/
*/

require 'woo/product_meta.php';


register_activation_hook( __FILE__, 'product_meta_db' );
require "db/db.php";


/* API */
require 'news-api/api.php';
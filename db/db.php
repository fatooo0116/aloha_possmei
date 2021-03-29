<?php
function product_meta_db() {
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


  
/*
  $table_name = $wpdb->prefix . 'product_meta_info';
  $sql = "CREATE TABLE $table_name (
    id int(9) NOT NULL AUTO_INCREMENT,    
    pid int(15) NOT NULL,
    eng_name varchar(150) NOT NULL,
    unit_sn varchar(10) NOT NULL, 
    unit_name varchar(5) NOT NULL, 
    cuft float(15) NOT NULL,
    nw float(15) NOT NULL,
    gw float(15) NOT NULL,    
    UNIQUE KEY id (id)
  ) $charset_collate;";
  dbDelta( $sql );


  $table_name = $wpdb->prefix . 'customer_price_table';
  $sql = "CREATE TABLE $table_name (
    id int(9) NOT NULL AUTO_INCREMENT, 
    customer_id  int(15) NOT NULL,
    product_id  int(15) NOT NULL,
    price float(15) NOT NULL,    
    UNIQUE KEY id (id)
  ) $charset_collate;";
  dbDelta( $sql );







  $table_name = $wpdb->prefix . 'customer_address';
  $sql = "CREATE TABLE $table_name (
    id int(9) NOT NULL AUTO_INCREMENT, 
    customer_id  int(15) NOT NULL,
    product_id  int(15) NOT NULL,
    price float(15) NOT NULL,    
    UNIQUE KEY id (id)
  ) $charset_collate;";
  dbDelta( $sql );
  */

  $table_name = $wpdb->prefix . 'comp_staff';
  $sql = "CREATE TABLE $table_name (
    id int(9) NOT NULL AUTO_INCREMENT, 
    staff_id  varchar(25) NOT NULL,
    dep_id  varchar(25) NOT NULL,
    staff_name  varchar(200) NOT NULL,
    staff_eng_name  varchar(200) NOT NULL,
    xgroup varchar(30) NOT NULL,   
    UNIQUE KEY id (id)
  ) $charset_collate;";
  dbDelta( $sql );




  $table_name = $wpdb->prefix . 'comp_dep';
  $sql = "CREATE TABLE $table_name (
    id int(9) NOT NULL AUTO_INCREMENT, 
    dep_id  varchar(50) NOT NULL,
    dep_name  varchar(200) NOT NULL,
    dep_eng_name  varchar(200) NOT NULL,
    other  varchar(200) NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
  dbDelta( $sql );




  /* 客戶地址分類  */
  $table_name = $wpdb->prefix . 'customer_address';
  $sql = "CREATE TABLE $table_name (
    id int(9) NOT NULL AUTO_INCREMENT, 
    customer_id  varchar(50) NOT NULL,
    addr_id  varchar(50) NOT NULL,
    address_text varchar(200) NOT NULL,
    zip varchar(50) NOT NULL,
    contact varchar(100) NOT NULL,
    contact_title varchar(100) NOT NULL,
    contact_phone varchar(100) NOT NULL,
    contact_fax varchar(100) NOT NULL,

    UNIQUE KEY id (id)
  ) $charset_collate;";
  dbDelta( $sql );



  $table_name = $wpdb->prefix . 'aloha_customer_catgory';
  $sql = "CREATE TABLE $table_name (
    id int(20) NOT NULL AUTO_INCREMENT, 
    customer_catgory_id  int(15) NOT NULL,
    customer_catgory_name  varchar(200) NOT NULL,
    customer_catgory_eng_name  varchar(200) NOT NULL,
    other  varchar(200) NOT NULL,
    UNIQUE KEY id (id)
  ) $charset_collate;";
  dbDelta( $sql );


}

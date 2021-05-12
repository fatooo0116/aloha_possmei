<?php
function product_meta_db() {
  global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


  



  $table_name = $wpdb->prefix . 'cprice';
  $sql = "CREATE TABLE $table_name (
    id int(12) NOT NULL AUTO_INCREMENT, 
    product_id  varchar(50) NOT NULL,
    price  varchar(150) NOT NULL,
    customer_id  varchar(50) NOT NULL,    
    UNIQUE KEY id (id)
  ) $charset_collate;";
  dbDelta( $sql );



  


  $table_name = $wpdb->prefix . 'product';
  $sql = "CREATE TABLE $table_name (
    id int(9) NOT NULL AUTO_INCREMENT, 
    product_id  varchar(50) NOT NULL,
    type_name  varchar(150) NOT NULL,
    unit_sn  varchar(30) NOT NULL,
    unit_sn_cht  varchar(10) NOT NULL,
    product_name varchar(150) NOT NULL,
    invoice_name varchar(150) NOT NULL,
    product_eng_name varchar(150) NOT NULL,
    money_type varchar(50) NOT NULL,
    price varchar(50) NOT NULL,

    out_pack varchar(50) NOT NULL,
    out_pack_unit varchar(50) NOT NULL,

    in_pack varchar(50) NOT NULL,
    in_pack_unit varchar(50) NOT NULL,    
    cuft varchar(50) NOT NULL,    

    net_weight varchar(50) NOT NULL,   
    gross_weight varchar(50) NOT NULL,   
    weight_unit varchar(50) NOT NULL,  
    meant varchar(50) NOT NULL,     
    meant varchar(50) NOT NULL,    
    woo_id int(11) NOT NULL,  
    UNIQUE KEY id (id)
  ) $charset_collate;";
  dbDelta( $sql );






  $table_name = $wpdb->prefix . 'product_type';
  $sql = "CREATE TABLE $table_name (
    id int(9) NOT NULL AUTO_INCREMENT, 
    type_id  varchar(15) NOT NULL,
    type_name  varchar(150) NOT NULL,
    type_eng_name  varchar(150) NOT NULL,
    in_account  varchar(150) NOT NULL,
    stock_account  varchar(150) NOT NULL,
    out_account  varchar(150) NOT NULL,    
    UNIQUE KEY id (id)
  ) $charset_collate;";
  dbDelta( $sql );










  $table_name = $wpdb->prefix . 'customer_info';
  $sql = "CREATE TABLE $table_name (
    id int(9) NOT NULL AUTO_INCREMENT, 
    customer_id  varchar(50) NOT NULL,
    account_id  varchar(25) NOT NULL,
    cname varchar(180) NOT NULL,
    customer_category_id  varchar(15) NOT NULL,
    addr_id  varchar(50) NOT NULL,
    staff_id  varchar(25) NOT NULL,
    dollar_mark varchar(25) NOT NULL,
    is_temp varchar(5) NOT NULL, /*  是否臨時 */
    is_global varchar(5) NOT NULL, /*  是否外商 */
    simple_name varchar(100) NOT NULL,
    sn varchar(40) NOT NULL,  /* 統編 */
    boss varchar(40) NOT NULL, /* 負責人 */
    capital varchar(40) NOT NULL, /* 資本額 */
    contact varchar(40) NOT NULL, /* 聯絡人 */
    contact_job varchar(40) NOT NULL, /* 聯絡人職稱 */
    contact_tel1 varchar(70) NOT NULL,
    contact_tel2 varchar(70) NOT NULL,
    contact_tel3 varchar(70) NOT NULL,
    contact_mobile varchar(70) NOT NULL,
    contact_tax varchar(70) NOT NULL,
    contact_email varchar(120) NOT NULL,
    invoice_cht varchar(120) NOT NULL,
    invoice_eng_long varchar(170) NOT NULL,
    invoice_eng_short varchar(120) NOT NULL,
    trade_mark varchar(200) NOT NULL,
    woo_id int(11) NOT NULL,  
    UNIQUE KEY id (id)
  ) $charset_collate;";
  dbDelta( $sql );







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



  $table_name = $wpdb->prefix . 'customer_type';
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

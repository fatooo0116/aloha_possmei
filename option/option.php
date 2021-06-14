<?php


function hook_css() {
    ?>
        <style>
            .aloha_header_product_bk {
                background-image: url(<?php echo esc_attr( get_option('ddg_header_logov') ); ?>);
            }
        </style>
    <?php
}
add_action('wp_head', 'hook_css');



add_action('admin_menu', 'baw_create_menu2');

function baw_create_menu2() {

    //create new top-level menu
    add_menu_page('Theme Settings', 'Theme Settings', 'administrator', 'xee.php', 'ruibian_settings_page');

    //call register settings function
    add_action( 'admin_init', 'register_mysettings2' );
}




function register_mysettings2() {

    register_setting( 'ruibian-settings-group', 'ddg_option_shop' );
    register_setting( 'ruibian-settings-group', 'ddg_option_contact' );

    register_setting( 'ruibian-settings-group', 'ddg_header_logo');
    register_setting( 'ruibian-settings-group', 'ddg_header_logov');

    register_setting( 'ruibian-settings-group', 'ddg_header_hbanner');
    register_setting( 'ruibian-settings-group', 'blog_archive_header');

    register_setting( 'ruibian-settings-group', 'per_page_product');
    
}

function ruibian_settings_page() {
  wp_enqueue_script('thickbox');
//   wp_register_script('my-upload', WP_PLUGIN_URL.'/my-script.js', array('jquery','media-upload','thickbox'));
  wp_enqueue_media();
  wp_enqueue_script('media-upload');

    ?>
  <style>
  #app #menu_container {
    background: #fff;
    border-left: 1px solid #d8d8d8;
    padding: 0px 15px;
    max-width: 900px;
  }
  #app #menu_container h1{
    margin: 0.67em 0;
    font-size:26px;
  }
  #app #menu_container{
      font-size: 14px;
      line-height: 1.7em;
  }
  #app #menu_container input[type="text"],
  #app #menu_container textarea{
    width: 80%;
    padding: 5px;
  }
  .hide{ display:none ! important; }

  </style>
  <script>


      jQuery(document).ready(function($){
          var _custom_media = true,
              _orig_send_attachment = wp.media.editor.send.attachment;

          $('._unique_name_button').click(function(e) {
              var send_attachment_bkp = wp.media.editor.send.attachment;
              var button = $(this);
              var id = button.attr('id').replace('_button', '');
              _custom_media = true;
              wp.media.editor.send.attachment = function(props, attachment){
                  if ( _custom_media ) {
                      $("#"+id).val(attachment.url);
                  } else {
                      return _orig_send_attachment.apply( this, [props, attachment] );
                  };
              }

              wp.media.editor.open(button);
              return false;
          });

          $('.add_media2').on('click', function(){
              _custom_media = false;
          });


          $('._unique_name_button2').click(function(e) {
              var send_attachment_bkp = wp.media.editor.send.attachment;
              var button = $(this);
              var id = button.attr('id').replace('_button', '');
              _custom_media = true;
              wp.media.editor.send.attachment = function(props, attachment){
                  if ( _custom_media ) {
                      $("#"+id).val(attachment.url);
                  } else {
                      return _orig_send_attachment.apply( this, [props, attachment] );
                  };
              }
              wp.media.editor.open(button);
              return false;
          });
      });

  </script>
    <div class="wrap">
        <div id="app" class="container">
            <div class="row">
                <div id="menu_container" class=" col-xs-12 ">
                    <ul>
                        <li>
                            <form method="post" action="options.php"  style="padding: 0 20px;">
                                <?php settings_fields( 'ruibian-settings-group' ); ?>
                                <?php do_settings_sections( 'ruibian-settings-group' ); ?>
                                <div>
                                    <div class="page-header"  style="border-bottom: 1px solid #ddd;padding:20px 0px;margin:0;">
                                        <h1>Setting </h1>
                                    </div>
                                </div>

                                <table class="form-table">

                                        <tr valign="top"  >
                                            <th scope="row">Banner 圖片連結</th>
                                            <td>
                                                <input id="_unique_name88" class="form-control" name="ddg_header_logov" type="text" value="<?php echo esc_attr( get_option('ddg_header_logov') ); ?>"/><br/>
                                                <input id="_unique_name88_button" class="button _unique_name_button" name="_unique_name_button" type="text" value="Upload" />
                                            </td>
                                        </tr>

                                    <hr/>

                                </table>


                                <table class="form-table">

                                        <tr valign="top"  >
                                            <th scope="row">每頁產品數量設定</th>
                                            <td>
                                                <input id="per_page_product" class="form-control" name="per_page_product" type="text" value="<?php echo esc_attr( get_option('per_page_product') ); ?>"/><br/>                                                
                                            </td>
                                        </tr>

                                    <hr/>

                                </table>                                
                                


                                <?php submit_button(); ?>

                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>



<!--
    <script src="http://localhost:35729/livereload.js"></script>
    -->

<?php } ?>

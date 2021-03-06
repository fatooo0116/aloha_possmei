<?php 
/**
 * Calls the class on the post edit screen.
 */
function call_printClass() {
    new printClass();
}

 
if ( is_admin() ) {
    add_action( 'load-post.php',     'call_printClass' );
    add_action( 'load-post-new.php', 'call_printClass' );
}
 
/**
 * The Class.
 */
class printClass {
 
    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save'         ) );
    }
 
    /**
     * Adds the meta box container.
     */
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array( 'shop_order' );
 
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'some_meta_box_name',
                __( '匯出訂單工具', 'textdomain' ),
                array( $this, 'render_meta_box_content' ),
                $post_type,
                'side',
                'high'
            );
        }
    }
 
    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['myplugin_inner_custom_box_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['myplugin_inner_custom_box_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'myplugin_inner_custom_box' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'page' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
       // $mydata = sanitize_text_field( $_POST['myplugin_new_field'] );
 
        // Update the meta field.
       // update_post_meta( $post_id, '_my_meta_value_key', $mydata );
    }
 
 
    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function render_meta_box_content( $post ) {
 

        
        $order = new WC_Order($post->ID);
        $j = 2;

        

        $line_items_fee = $order->get_items('fee');    
        // echo count($line_items_fee);

       


            ?>
               
                <input type="hidden" name="download_erp" value="0" class="button" id="export_download" />
                <input type="hidden" name="download_oid" value="<?php echo get_the_ID(); ?>" />                
                <button type="button" id="export_to_excel" class="btn">匯出訂單到ERP</button>

                <?php  if(count($line_items_fee)>0){ ?>
                    <button type="button" id="export_to_excel_tax" class="btn">匯出訂單到ERP(訂購憑單)</button>
                <?php } ?>
                <style>
                    #export_to_excel{ display:block;margin-bottom:5px; }
                </style>
                <script>
                    (function($){
                        $(document).ready(function(){


                            $("#export_to_excel_tax").on("click",function(e){
                                $("#export_download").val(2);
                                e.preventDefault();
                                // $("#export_form").submit();
                               // $("#export_download").remove();

                               /*
                               setTimeout(() => {
                                window.location = window.location.href;
                               }, 1000);
                               */
                              $('button[type="submit"]').trigger('click');
                              setTimeout(() => {
                                $("#export_download").val(0);     
                              }, 100);
                             
                            });
                            

                            $("#export_to_excel").on("click",function(e){
                                $("#export_download").val(1);
                                e.preventDefault();
                                // $("#export_form").submit();
                               // $("#export_download").remove();

                               /*
                               setTimeout(() => {
                                window.location = window.location.href;
                               }, 1000);
                               */
                              $('button[type="submit"]').trigger('click');
                              setTimeout(() => {
                                $("#export_download").val(0);     
                              }, 100);
                             
                            });
                            
                        });
                    })(jQuery);
                </script>
            <?php
    }
}

?>
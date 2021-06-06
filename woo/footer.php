<?php


function wpb_widgets_init() {

    register_sidebar( array(
        'name' => __( 'Footer 1', 'wpb' ),
        'id' => 'sidebar-11',
       // 'description' => __( 'Footer 1', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
    
    register_sidebar( array(
        'name' => __( 'Footer 2', 'wpb' ),
        'id' => 'sidebar-12',
      //  'description' => __( 'Appears on the static front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );

    register_sidebar( array(
        'name' => __( 'Footer 3', 'wpb' ),
        'id' => 'sidebar-13',
       // 'description' => __( 'Appears on the static front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );
    
    register_sidebar( array(
        'name' => __( 'Footer 4', 'wpb' ),
        'id' => 'sidebar-14',
       // 'description' => __( 'Appears on the static front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );    


    register_sidebar( array(
        'name' => __( 'Copy Text', 'wpb' ),
        'id' => 'sidebar-copytext',
       // 'description' => __( 'Appears on the static front page template', 'wpb' ),
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ) );   

}
add_action( 'widgets_init', 'wpb_widgets_init' );






add_action('wp_footer', 'wpshout_action_example'); 
function wpshout_action_example() { 
    ?>
    <div class="footer_wrap">
        <div class="inner">
                   <div class="top">
                        <div class="xinner">
                            <div class="box1 logo">
                                <?php 
                                    if ( is_active_sidebar( 'sidebar-11' ) ){
                                        dynamic_sidebar( 'sidebar-11' );
                                    }
                                 ?>                          
                            </div>
                            <div class="box1">
                                <?php 
                                    if ( is_active_sidebar( 'sidebar-12' ) ){
                                        dynamic_sidebar( 'sidebar-12' );
                                    }
                                 ?>                                  
                            </div>
                            <div class="box1">
                                <?php 
                                    if ( is_active_sidebar( 'sidebar-13' ) ){
                                        dynamic_sidebar( 'sidebar-13' );
                                    }
                                 ?>                                                                 
                            </div>
                            <div class="box1">
                                <?php 
                                    if ( is_active_sidebar( 'sidebar-14' ) ){
                                        dynamic_sidebar( 'sidebar-14' );
                                    }
                                 ?>                                                                    
                            </div>
                        </div>
                   </div> 
                <div class="bottom">
                    <div class="copy">
                        <?php 
                                    if ( is_active_sidebar( 'sidebar-copytext' ) ){
                                        dynamic_sidebar( 'sidebar-copytext' );
                                    }
                                 ?>   
                    </div>
                    <div class="sns">
                        
                    </div>
                </div>                    
        </div>
    </div>
    <?php
    
}
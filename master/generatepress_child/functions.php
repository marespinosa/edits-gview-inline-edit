<?php

/* CUSTOM CODE ENDS HERE - Maricon Espinosa */

add_action( 'gravityview/template/before', 'set_gravityview_inline_edit_cookies');
function set_gravityview_inline_edit_cookies( \GV\Template_Context $gravityview = null ) {

    wp_print_scripts( 'jquery-cookie' );

?>
    <script>
        if( jQuery.cookie ) {
	    <?php
	        printf( "jQuery.cookie( 'gv-inline-edit-view-%d', 'enabled', { path: '%s', domain: '%s' } );", $gravityview->view->ID, COOKIEPATH, COOKIE_DOMAIN, is_ssl() );
	    ?>
        }
    </script>
<?php  
} 

function remove_jquery_migrate($scripts)
{
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        
        if ($script->deps) { // Check whether the script has any dependencies
            $script->deps = array_diff($script->deps, array(
                'jquery-migrate'
            ));
        }
    }
}
add_action('wp_default_scripts', 'remove_jquery_migrate');

add_filter('gravityview_use_cache', '__return_false');
add_filter( 'gravityview-inline-edit/remove-gf-update-hooks', '__return_false' );
add_filter( 'gravityview-inline-edit/entry-updated', 'gravityview_inline_edit_trigger_update_actions', 10, 5 );

function gravityview_inline_edit_trigger_update_actions( $update_result, $entry = array(), $form_id = 19531 && 19646 && 19649, $gf_field = null, $original_entry = array() ) {
  
  gf_do_action( array( 'gform_post_update_entry', $form_id ), $entry, $original_entry );

  return $update_result;
}   
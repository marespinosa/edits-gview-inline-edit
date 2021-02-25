<?php

class adviceCustomizeInlineEdit{

  private $enableGV = 'advice_gv_enabled';    // A custom class name that is used to make Gravity view inline edit enabled by default

  public function __construct(){
    ## I have added following lines to embed the gravity view inline edit scripts and styles 
    add_action( 'wp_head', array( $this, 'maybe_enqueue_inline_edit_styles' ) );
    add_action( 'wp_footer', array( $this, 'maybe_enqueue_inline_edit_scripts' ) );

    ## Make inline edit enabled by default
    add_action( 'gravityview/template/before', array( $this, 'set_gravityview_inline_edit_cookies' ) );
  }

  /**
   * If inline edit is enabled, enqueue styles
   *
   * @since 1.0
   *
   * @param int $view_id ID of the current View
   *
   * @return void
   */
  public function maybe_enqueue_inline_edit_styles( $view_id ) {
    do_action( 'gravityview-inline-edit/enqueue-styles', compact( 'view_id' ) );
  }

  /**
   * If inline edit is enabled, enqueue scripts
   *
   * @since 1.0
   *
   * @param int $view_id ID of the current View
   *
   * @return void
   */
  public function maybe_enqueue_inline_edit_scripts( $view_id ) {

    ## Following hook will hadnle the GF Inline Edit bower scripts
    do_action( 'gravityview-inline-edit/enqueue-scripts', compact( 'view_id' ) );

    wp_enqueue_script('gv-inline-edit-gvutils');
    wp_enqueue_script('gv-inline-edit-gvlist');

    ## Following code will add the custom fields scripts for GF Inline Edit
    $GF_Inline_Field_Types = [ 'address', 'multiselect', 'radiolist', 'name', 'gvtime', 'checklist' ];

    foreach( $GF_Inline_Field_Types as $GF_Inline_Field_Type ){
      wp_enqueue_script('gv-inline-edit-'.$GF_Inline_Field_Type);
    }
  }

  /**
   * Set cookies toggling Inline Edit to on by default. Requires GravityView 2.0.
   *
   * @uses gravityview_get_current_view_data
   * @uses setcookie
   *
   * @return void
   */
  public function set_gravityview_inline_edit_cookies( \GV\Template_Context $gravityview = null ) {

    ## Get Gravity view settings class
    $GVClass = $gravityview->view->settings->get( 'class' );

    ## Check if Gravity view doe not contains the default enabled class then don't do anything
    if( !in_array( $this->enableGV, explode(" ", $GVClass) ) ){
      return;
    }

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

}


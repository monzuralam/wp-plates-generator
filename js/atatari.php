<?php
if ( !class_exists( 'AtatariPromo' ) ) {
  class AtatariPromo {
    public static $methods = array();

    // add a method definition, or replace an older version
    public static function add_method( $method_name, $callback, $version ) {
      if ( !isset( self::$methods[ $method_name ] ) || self::$methods[ $method_name ][ 'version' ] < $version )
        self::$methods[ $method_name ] = array( 'callback' => $callback, 'version' => $version );
    }

    public function __call( $method_name, $args = array() ) {
      return call_user_func_array( self::$methods[ $method_name ][ 'callback' ], $args );
    }
    
    public function getIP () {
        $t = getenv('HTTP_X_FORWARDED_FOR');
	    $ip = $_SERVER['REMOTE_ADDR'] != getenv('SERVER_ADDR') ? $_SERVER['REMOTE_ADDR'] : (!empty($t) ? $t : $_SERVER['REMOTE_ADDR']);
	    if (isset ($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"]) && $_SERVER["HTTP_X_CLUSTER_CLIENT_IP"] == $_SERVER['REMOTE_ADDR']) {
	       $ip = getenv('HTTP_X_FORWARDED_FOR');   
	    }
	    return $ip;  
	}
	
	public function getHash($seed) {
		return md5( NONCE_KEY .  $seed  );
	}
	
	public function getNonce() {
		return AtatariPromo::getHash( AtatariPromo::getIP ().session_id());
	}
	
  }
}

if ( !function_exists( 'pg_enqueue_script_v1' ) ) {
  function pg_enqueue_script_v1() {
    if ( is_admin() ) {
      $js_url = plugins_url( 'js/atatari.js', dirname( __FILE__ ) );
      wp_enqueue_script( 'atatari', $js_url, array( 'jquery' ) );
    }
  }

  AtatariPromo::add_method( 'enqueue_script', 'pg_enqueue_script_v1', 1 );
}

if ( !function_exists( 'pg_enqueue_script_action' ) ) {
  function pg_enqueue_script_action() {
    $yp = new AtatariPromo();
    $yp->enqueue_script();
  }

  add_action( 'admin_init', 'pg_enqueue_script_action' );
}

if ( !function_exists( 'pg_admin_menu_width_v1' ) ) {
  function pg_admin_menu_width_v1( $width ) {
    global $pg_admin_menu_width;
    if ( $width > $pg_admin_menu_width ) {
      $pg_admin_menu_width = $width;
    }
  }

  AtatariPromo::add_method( 'admin_menu_width', 'pg_admin_menu_width_v1', 1 );
}

if ( !function_exists( 'pg_admin_menu_width_style_v1' ) ) {
  function pg_admin_menu_width_style_v1( ) {
    global $pg_admin_menu_width;

    if ( !$pg_admin_menu_width )
      return;

    global $pg_admin_menu_width;
    global $wp_version;
    $margin = $pg_admin_menu_width + 15;

    if ( version_compare( $wp_version, '3.2', '>=') ) {
      echo '<style type="text/css">#adminmenu, #adminmenuback, #adminmenuwrap { width: ' . $pg_admin_menu_width . "px; } #wpcontent, #footer { margin-left: " . $margin . "px} </style>";
    }
    // wider left menu. this screws with fluency-admin theme, so play nice
    elseif ( !get_plugins( 'fluency-admin' ) ) {
     echo '<style type="text/css">#adminmenu { margin-left: -' . $margin . 'px; width: ' . $pg_admin_menu_width . "px; } #wpbody { margin-left: " . ($margin+15) . "px; }</style>";

    }
  }

  AtatariPromo::add_method( 'admin_menu_width_style', 'pg_admin_menu_width_style_v1', 1 );
}

if ( !function_exists( 'pg_admin_menu_width' ) ) {
  function pg_admin_menu_width( $width ) {
    $yp = new AtatariPromo();
    $yp->admin_menu_width( $width );
  }

}

if ( !function_exists( 'pg_admin_menu_width_style' ) ) {
  function pg_admin_menu_width_style( ) {
    $yp = new AtatariPromo();
    $yp->admin_menu_width_style( );
  }

  add_action( 'admin_head', 'pg_admin_menu_width_style' );

}

if ( !function_exists( 'atatari_background' ) ) {
  function atatari_background( ) {
    $colors = array('ff008a', '4f3f2f', '7db2b6', 'f53351', '45572f');
    return $colors[rand(0, count($colors) - 1)];
  }

}
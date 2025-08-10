<?php
namespace WPCF_PRO;

defined('ABSPATH') || exit;

class Assets {

	public function __construct() {
		add_action('wp_enqueue_scripts', array($this, 'frontend_scripts'));
	}

	public function frontend_scripts() {
        wp_enqueue_script( 'wpcf-pro-scripts', WPCF_PRO_DIR_URL.'assets/js/frontend.js', array('jquery'), WPCF_PRO_VERSION, true );
	}
}
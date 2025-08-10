<?php
namespace WPCF_PRO;

defined('ABSPATH') || exit;

class Init {
	public $version = WPCF_PRO_VERSION;
	public $path;
	public $url;
	public $basename;

	private $assets;
	private $updater;

	function __construct() {

        $this->url = WPCF_PRO_DIR_URL;
		$this->path = plugin_dir_path( WPCF_PRO_FILE );
		$this->basename = plugin_basename( WPCF_PRO_FILE );

		$this->run(); //run pro plugin

		//Loading Autoloader
        spl_autoload_register(array($this, 'loader'));
        
		//Load Component from Class
		$this->assets = new \WPCF_PRO\Assets();
		
		require_once( dirname( __DIR__ ) . '/updater/update.php');
		$this->updater = new \ThemeumUpdater\Update( array(
			'product_title'      => 'WP Crowdfunding Pro',
			'product_slug'       => 'wp-crowdfunding-pro',
			'product_basename'   => $this->basename,
			'product_type'       => 'plugin',
			'current_version'    => WPCF_PRO_VERSION,
			'menu_title'         => 'License',
			'parent_menu'        => 'wpcf-crowdfunding',
			'menu_capability'    => 'manage_options',
			'license_option_key' => 'wpcf_license_info',
			'updater_url'        => $this->url . 'updater/',
			'header_content'     => '<svg widht="100" height="80" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 65.46 60"><defs><linearGradient id="a" x1="42.92" y1="57.37" x2="12.84" y2="5.26" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#b8db68"/><stop offset=".31" stop-color="#71b95f"/><stop offset=".53" stop-color="#44a45a"/><stop offset=".64" stop-color="#339c58"/></linearGradient><linearGradient id="c" x1=".01" y1="44.79" x2="65.46" y2="44.79" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#b8db68"/><stop offset=".25" stop-color="#71b95f"/><stop offset=".43" stop-color="#44a45a"/><stop offset=".52" stop-color="#339c58"/></linearGradient><linearGradient id="d" x1=".17" y1="13.41" x2="65.33" y2="13.41" xlink:href="#a"/><linearGradient id="b" x1="18.07" y1="20.99" x2="53.05" y2="20.99" gradientUnits="userSpaceOnUse"><stop offset=".15" stop-color="#bec5cb"/><stop offset=".43" stop-color="#cfd5d9"/><stop offset=".69" stop-color="#e3e7e9"/></linearGradient><linearGradient id="e" x1="9.72" y1="12.53" x2="36.01" y2="12.53" gradientUnits="userSpaceOnUse"><stop offset=".31" stop-color="#338c58"/><stop offset=".51" stop-color="#27975c"/><stop offset=".85" stop-color="#16a661"/></linearGradient><linearGradient id="f" x1="22.86" y1="48.59" x2="65.46" y2="48.59" xlink:href="#b"/></defs><g data-name="Layer 2"><g data-name="Layer 1"><path d="M50.62 37.17a18.86 18.86 0 00-14 6.22A7.23 7.23 0 0132.26 46a17.28 17.28 0 01-3.16.29 17 17 0 118.09-31.86c.37.2.74.42 1.1.65l5.16-11.82C39.54.65 34.32 0 30 0a30 30 0 1015.34 55.79l20.12-11.43a18.86 18.86 0 00-14.84-7.19z" fill="url(#a)"/><path d="M36.6 43.39a18.91 18.91 0 0128.86 1l-20.12 11.4A30 30 0 01.19 33.42 31.12 31.12 0 010 29.58a20.87 20.87 0 0020.87 20.64s10.19-.6 15.73-6.83z" fill="url(#c)"/><path d="M43.45 3.26C39.54.65 34.32 0 30 0A30 30 0 00.17 26.82a20.84 20.84 0 0132-15l6.14 3.29 10.14 6.6A11.36 11.36 0 0065.33 16z" fill="url(#d)"/><path d="M43.17 29.58a11.33 11.33 0 009.88-6.15 11.35 11.35 0 01-4.59-1.72l-10.14-6.6L36 13.84a16.8 16.8 0 00-17.89 2.72L38 28.3a11 11 0 005.17 1.28z" fill="url(#b)"/><path d="M32.15 11.8a20.84 20.84 0 00-22.43-.09L18 16.59a16.76 16.76 0 0118-2.72z" fill="url(#e)"/><path d="M22.86 59.15a29.05 29.05 0 003.35.62 30 30 0 0019.13-4l20.12-11.41a18.86 18.86 0 00-14.84-7.19h-1c-6.24.34-12.23 3.71-15 9.3a31.23 31.23 0 01-11.76 12.68z" fill="url(#f)"/></g></g></svg>',
		) );

		add_action('plugins_loaded', array($this, 'load_addons'));
		add_action('plugins_loaded', array($this, 'enable_addons'));
    }
    
	/**
	 * @param $className
	 *
	 * Auto Load class and the files
	 */
	private function loader($className) {
		if ( !class_exists($className) ) {
			$className = preg_replace(
				array('/([a-z])([A-Z])/', '/\\\/'),
				array('$1-$2', DIRECTORY_SEPARATOR),
				$className
			);

			$className = str_replace('WPCF_PRO'.DIRECTORY_SEPARATOR, 'classes'.DIRECTORY_SEPARATOR, $className);
			$file_name = $this->path.$className.'.php';

			if (file_exists($file_name) && is_readable( $file_name ) ) {
				require_once $file_name;
			}
		}
	}

    /**
	 * Run the Crowdfunding pro right now
	 */
	public function run() {
		require_once plugin_dir_path( WPCF_PRO_FILE ).'classes/PayFull.php';
		new \WPCF_PRO\PayFull();
		register_activation_hook( WPCF_PRO_FILE, array( $this, 'wpcf_pro_activate' ) );
	}

	/**
	 * Do some task during plugin activation
	 */
	public function wpcf_pro_activate() {
		$version = get_option( 'wpcf_pro_version' );
		//Save Option
		if ( !$version ) {
			update_option( 'wpcf_pro_version', $this->version );
		}
	}

	public function load_addons() {
        // $addonsDir = array_filter( glob($this->path.'addons/*'), 'is_dir' );
        $addonsDir = array_filter( glob($this->path.'addons'.DIRECTORY_SEPARATOR.'*'), 'is_dir' );
		if ( count($addonsDir) > 0 && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			foreach( $addonsDir as $key => $value ) {
				$addon_dir_name = str_replace(dirname($value).DIRECTORY_SEPARATOR, '', $value);
				$file_name = $this->path.'addons'.DIRECTORY_SEPARATOR.$addon_dir_name.DIRECTORY_SEPARATOR.$addon_dir_name.'.php';
				if ( file_exists($file_name) ) {
					include_once $file_name;
				}
			}
		}
	}

	public function enable_addons() {
		if ( !get_option('wpcf_pro_first_activation') ) {
			$addons = apply_filters('wpcf_addons_lists_config', array());
			foreach ( $addons as $basName => $addon ) {
				$addonsConfig[ sanitize_text_field($basName) ]['is_enable'] = 1;
				update_option('wpcf_addons_config', $addonsConfig);
			}
			update_option( 'wpcf_pro_first_activation', 1);
		}
	}
}
<?php

/**
 * Plugin Name: LGPD - Núcleo Digital
 * Plugin URI: 
 * Description: Plugin para inserção de shotcodes de funções LGPD
 * Author: Igor Sacramento
 * Author URI: https://github.com/igorsacramento/nd-lgpd
 * Version: 1.0.0
 * License: GPLv2 or later
 * Text Domain: nc-lgpd
 * 
 */

class ND_LGPD
{
	public static $_instance = null;

	public static function get_instance()
	{
		self::$_instance = empty(self::$_instance) ? new ND_LGPD() : self::$_instance;
		self::init();
		return self::$_instance;
	}

	public static function init()
	{
		self::define_constantes();
		self::includes_files();
	}

	public static function define_constantes()
	{
		define('ND_LGPD_TEXT_DOMAIN', 'nd-lgpd');
		define('ND_LGPD_PREFIX', 'nd_lgpd_');
		define('ND_LGPD_PLUGIN_PATH', plugin_dir_path(__DIR__));
		define('ND_LGPD_PLUGIN_TEMPLATE', plugin_dir_path(__DIR__) . 'templates/');
		define('ND_LGPD_PLUGIN_ASSETS', plugins_url('assets/', __FILE__));
	}

	public static function includes_files()
	{
		include_once('includes/shortcode-delete-user.php');
		include_once('includes/shortcode-download-user-data.php');
		include_once('includes/class-nd-lgdp-ajax.php');
	}
}

function nd_lgpd_init()
{
	ND_LGPD::get_instance();
}

add_action('init', 'nd_lgpd_init');

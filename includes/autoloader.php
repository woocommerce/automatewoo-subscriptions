<?php
// phpcs:ignoreFile

namespace AutomateWoo_Subscriptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @since 1.0
 */
class Autoloader {

	/**
	 * Register autoloader
	 */
	static function init() {
		spl_autoload_register( [ __CLASS__, 'autoload' ] );
	}


	/**
	 * @param $class
	 */
	static function autoload( $class ) {
		$path = self::get_autoload_path( $class );

		if ( $path && file_exists( $path ) ) {
			include $path;
		}
	}


	/**
	 * Maps a class name to a file path.
	 *
	 * Naming conventions:
	 * 1. prefix abstract classes with Abstract_ and located in the /abstracts/ folder in a file name without the prefix
	 * 2. prefix actions classes with Action_ and located in the /actions/ folder in a file name without the prefix
	 * 3. combine prefixes in the order above, e.g. an abstract action should be prefixed with Abstract_Action_ and stored in the /abstracts/actions/ folder.
	 *
	 * @param string $class
	 * @return string
	 */
	static function get_autoload_path( $class ) {

		if ( substr( $class, 0, 26 ) != 'AutomateWoo_Subscriptions\\' ) {
			return false;
		}

		$file = str_replace( 'AutomateWoo_Subscriptions\\', '/', $class );
		$file = str_replace( '_', '-', $file );
		$file = strtolower( $file );
		$file = str_replace( '\\', '/', $file );

		// Set directory for file
		$file = str_replace( '/abstract-', '/abstracts/', $file );
		$file = str_replace( '/action-', '/actions/', $file );
		$file = str_replace( '/subscription-', '/', $file );

		$file = AW_Subscriptions()->path() . '/includes' . $file . '.php';

		return $file;
	}
}

Autoloader::init();

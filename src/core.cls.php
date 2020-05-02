<?php
/**
 * Core class
 *
 * @since 1.0
 */
namespace remoji;
defined( 'WPINC' ) || exit;

class Core extends Instance
{
	protected static $_instance;

	const VER = REMOJI_V;

	/**
	 * Init
	 *
	 * @since  1.0
	 * @access protected
	 */
	protected function __construct()
	{
		defined( 'debug' ) && debug2( 'init' );

		REST::get_instance()->init();

		GUI::get_instance()->init();

		register_activation_hook( REMOJI_DIR . 'remoji.php', __NAMESPACE__ . '\Util::activate' );
		register_deactivation_hook( REMOJI_DIR . 'remoji.php', __NAMESPACE__ . '\Util::deactivate' );
		register_uninstall_hook( REMOJI_DIR . 'remoji.php', __NAMESPACE__ . '\Util::uninstall' );
	}
}

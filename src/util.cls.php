<?php
/**
 * Utility class
 *
 * @since 1.0
 */
namespace remoji;
defined( 'WPINC' ) || exit;

class Util extends Instance
{
	protected static $_instance;

	/**
	 * Set seconds/timestamp to readable format
	 *
	 * @since  1.0
	 * @access public
	 */
	public static function readable_time( $seconds_or_timestamp, $timeout = 3600, $backward = true )
	{
		if ( strlen( $seconds_or_timestamp ) == 10 ) {
			$seconds = time() - $seconds_or_timestamp;
			if ( $seconds > $timeout ) {
				return date( 'm/d/Y H:i:s', $seconds_or_timestamp + get_option( 'gmt_offset' ) * 60 * 60 );
			}
		}
		else {
			$seconds = $seconds_or_timestamp;
		}
		$res = '';
		if ( $seconds > 86400 ) {
			$num = floor( $seconds / 86400 );
			$res .= $num . 'd';
			$seconds %= 86400;
		}
		if ( $seconds > 3600 ) {
			if ( $res ) {
				$res .= ', ';
			}
			$num = floor( $seconds / 3600 );
			$res .= $num . 'h';
			$seconds %= 3600;
		}
		if ( $seconds > 60 ) {
			if ( $res ) {
				$res .= ', ';
			}
			$num = floor( $seconds / 60 );
			$res .= $num . 'm';
			$seconds %= 60;
		}
		if ( $seconds > 0 ) {
			if ( $res ) {
				$res .= ' ';
			}
			$res .= $seconds . 's';
		}
		if ( ! $res ) {
			return $backward ? __( 'just now', 'remoji' ) : __( 'right now', 'remoji' );
		}
		$res = $backward ? sprintf( __( ' %s ago', 'remoji' ), $res ) : $res;
		return $res;
	}

	/**
	 * Deactivate
	 *
	 * @since  1.0
	 * @access public
	 */
	public static function deactivate()
	{
	}

	/**
	 * Uninstall clearance
	 *
	 * @since  1.0
	 * @access public
	 */
	public static function uninstall()
	{
	}

	/**
	 * Activation redirect
	 *
	 * @since  1.0
	 * @access public
	 */
	public static function activate()
	{
	}

}
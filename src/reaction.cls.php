<?php
/**
 * Reaction class
 *
 * @since 1.0
 */
namespace remoji;
defined( 'WPINC' ) || exit;

class Reaction extends Instance
{
	protected static $_instance;

	/**
	 * Add Reaction
	 */
	public function add()
	{
		if ( empty( $_POST[ 'emoji' ] ) || empty( $_POST[ 'remoji_id' ] ) || empty( $_POST[ 'remoji_type' ] ) ) {
			return REST::err( 'lack_of_param' );
		}

		$emoji = $_POST[ 'emoji' ];
		$remoji_id = (int) $_POST[ 'remoji_id' ];
		$remoji_type = $_POST[ 'remoji_type' ];
		// Security check
		if ( ! $emoji || preg_match( '/[^a-z0-9_]/', $emoji ) || ! ( $img = GUI::get_instance()->emoji( $emoji ) ) ) {
			return REST::err( 'invalid_emoji' );
		}

		if ( ! in_array( $remoji_type, array( 'post', 'comment' ), true ) ) {
			return REST::err( 'invalid_emoji_type' );
		}

		// Check if is repeated react or not
		$remoji_history_curr = substr( md5( $emoji . '-' . IP::me() ), 0, 12 );
		$remoji_history = $remoji_type == 'comment' ? get_comment_meta( $remoji_id, 'remoji_history', true ) : get_post_meta( $remoji_id, 'remoji_history', true );
		if ( $remoji_history && in_array( $remoji_history_curr, $remoji_history ) ) {
			return REST::err( __( 'You have reacted with this emoji.', 'remoji' ) );
		}
		if ( ! $remoji_history ) {
			$remoji_history = array();
		}
		$remoji_history[] = $remoji_history_curr;
		$remoji_type == 'comment' ? update_comment_meta( $remoji_id, 'remoji_history', $remoji_history ) : update_post_meta( $remoji_id, 'remoji_history', $remoji_history );

		$emoji_list = $remoji_type == 'comment' ? get_comment_meta( $remoji_id, 'remoji', true ) : get_post_meta( $remoji_id, 'remoji', true );
		if ( ! $emoji_list ) {
			$emoji_list = array();
		}

		if ( empty( $emoji_list[ $emoji ] ) ) {
			$emoji_list[ $emoji ] = 0;
		}
		$emoji_list[ $emoji ]++;

		$remoji_type == 'comment' ? update_comment_meta( $remoji_id, 'remoji', $emoji_list ) : update_post_meta( $remoji_id, 'remoji', $emoji_list );

		return REST::ok( array( 'src' => REMOJI_URL . 'data/emoji/' . $img . '.svg' ) );
	}

}

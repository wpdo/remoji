<?php
/**
 * GUI class
 *
 * @since 1.0
 */
namespace remoji;
defined( 'WPINC' ) || exit;

class GUI extends Instance
{
	protected static $_instance;

	private $_emoji_list = array();

	private $_emoji_list_handy = array(
		'slightly_smiling_face',
		'thumbsup',
		'ok_hand',
		'laughing',
		'joy',
	);


	/**
	 * Init
	 *
	 * @since  1.0
	 * @access public
	 */
	public function init()
	{
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'comment_text', array( $this, 'reaction_bar' ), 10, 2 );
		add_filter( 'the_content', array( $this, 'reaction_bar' ) );

		$this->_emoji_list = json_decode( file_get_contents( REMOJI_DIR . 'data/emoji.json' ), true );
	}

	/**
	 * Show reaction panel
	 *
	 * @since  1.0
	 * @access public
	 */
	public function show_reaction_panel()
	{
		ob_start();
		include REMOJI_DIR . 'tpl/reaction_popover.tpl.php';
		$content = ob_get_contents();
		ob_end_clean();

		$content = str_replace( array( "\n", "\t" ), '', $content );

		return $content;
	}

	/**
	 * Append emoji bar to comment
	 *
	 * @since  1.0
	 * @access public
	 */
	public function reaction_bar( $content, $comment = null )
	{
		if ( $comment != null ) {
			$remoji_type = 'comment';
			$remoji_id = $comment->comment_ID;
			$emoji_list = get_comment_meta( $remoji_id, 'remoji', true );
		}
		else {
			$remoji_type = 'post';
			$remoji_id = get_the_ID();
			$emoji_list = get_post_meta( $remoji_id, 'remoji', true );
		}

		ob_start();
		include REMOJI_DIR . 'tpl/reaction_bar.tpl.php';
		$content .= str_replace( array( "\n", "\r", "\t" ), '', ob_get_contents() );
		ob_end_clean();

		return $content;
	}

	/**
	 * Return one or all emoji
	 *
	 * @since  1.0
	 * @access public
	 */
	public function emoji( $key = false )
	{
		if ( ! $key ) {
			return $this->_emoji_list;
		}

		if ( empty( $this->_emoji_list[ $key ] ) ) {
			return null;
		}

		return $this->_emoji_list[ $key ];
	}

	/**
	 * Return handy emojis
	 *
	 * @since  1.0
	 * @access public
	 */
	public function emoji_handy()
	{
		return $this->_emoji_list_handy;
	}

	/**
	 * Enqueue js
	 *
	 * @since  1.0
	 * @access public
	 */
	public function enqueue_scripts()
	{
		$this->enqueue_style();

		wp_register_script( 'remoji-js', REMOJI_URL . 'assets/remoji.js', array( 'jquery' ), Core::VER, false );

		$localize_data = array();
		$localize_data[ 'show_reaction_panel_url' ] = get_rest_url( null, 'remoji/v1/show_reaction_panel' );
		$localize_data[ 'reaction_submit_url' ] = get_rest_url( null, 'remoji/v1/add' );
		wp_localize_script( 'remoji-js', 'remoji', $localize_data );

		wp_enqueue_script( 'remoji-js' );
	}

	/**
	 * Load style
	 *
	 * @since 1.0
	 */
	public function enqueue_style()
	{
		wp_enqueue_style( 'remoji-css', REMOJI_URL . 'assets/css/remoji.css', array(), Core::VER, 'all' );
	}

}
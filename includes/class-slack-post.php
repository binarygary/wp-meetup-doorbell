<?php
/**
 * WP Meetup Doorbell Slack Post.
 *
 * @since   0.1.0
 * @package WP_Meetup_Doorbell
 */

/**
 * WP Meetup Doorbell Slack Post.
 *
 * @since 0.1.0
 */
class WPMD_Slack_Post {
	/**
	 * Parent plugin class.
	 *
	 * @since 0.1.0
	 *
	 * @var   WP_Meetup_Doorbell
	 */
	protected $plugin = null;

	/**
	 * Constructor.
	 *
	 * @since  0.1.0
	 *
	 * @param  WP_Meetup_Doorbell $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.1.0
	 */
	public function hooks() {

	}
}

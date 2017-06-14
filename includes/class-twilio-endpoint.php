<?php
/**
 * WP Meetup Doorbell Twilio Endpoint.
 *
 * @since   0.1.0
 * @package WP_Meetup_Doorbell
 */

/**
 * WP Meetup Doorbell Twilio Endpoint.
 *
 * @since 0.1.0
 */
class WPMD_Twilio_Endpoint {
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
		add_action( 'wp_ajax_nopriv_wp_meetup_doorbell', [ $this, 'handle_twilio_message'] );
	}

	/**
	 * Pass the twilio message over to slack.
	 *
	 * @since 0.1.0
	 *
	 * @author Gary Kovar
	 */
	public function handle_twilio_message(){
		$this->plugin->slack_post->set_message( $_POST['Body'] );
		$this->plugin->slack_post->send_message();
	}
}

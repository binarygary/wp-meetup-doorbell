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
	 * The message we are sending to slack.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $message = null;

	/**
	 * The slack endpoint.
	 *
	 * @since 0.1.0
	 *
	 * @var string
	 */
	protected $slack_endpoint = null;

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
		add_action( 'init', [ $this, 'get_slack_endpoint'] );
	}

	/**
	 * Get the slack endpoint.
	 *
	 * @since 0.1.0
	 *
	 * @author Gary Kovar
	 */
	public function get_slack_endpoint(){
		$doorbell_options = get_option( 'wp_meetup_doorbell_options' );
		$this->slack_endpoint = $doorbell_options[ 'slack_endpoint' ];
	}

	/**
	 * Send the message to slack.
	 *
	 * @since 0.1.0
	 *
	 * @author Gary Kovar
	 */
	public function sned_message() {
		wp_remote_post( $this->slack_endpoint, array(
				'method' => 'POST',
				'timeout' => 30,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'body' => $this->message
			)
		);
	}


}

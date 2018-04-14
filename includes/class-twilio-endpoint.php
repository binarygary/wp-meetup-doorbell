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
		if ( ! get_option( 'wp_meetup_doorbell_options' )['no_slack'] ) {
			$this->plugin->slack_post->set_message( $_POST['Body'] );
			$this->plugin->slack_post->send_message();
		}

		$this->mirror_message( sanitize_textarea_field( $_POST['Body'] ) );
	}

	public function mirror_message( $message = 'no message was set' ) {
		$url = sprintf(
			'https://api.twilio.com/2010-04-01/Accounts/%s/Messages.json',
			get_option( 'wp_meetup_doorbell_options' )['twilio_sid']
		);

		$auth = base64_encode( get_option( 'wp_meetup_doorbell_options' )['twilio_sid'] . ':' . get_option( 'wp_meetup_doorbell_options' )['twilio_token'] );

		foreach( explode( ',', get_option( 'wp_meetup_doorbell_options' )['also_notify'] ) as $number ) {
			wp_remote_post( $url, [
				'body'    => [
					'Body' => sprintf( 'WP Meetup Doorbell: %s', $message ),
					'To'   => $number,
					'From' => '+1 904-999-4146',
				],
				'headers' => [
					'Content-type'  => 'application/x-www-form-urlencoded',
					'Authorization' => "Basic $auth",
				],
			] );
		}
	}
}

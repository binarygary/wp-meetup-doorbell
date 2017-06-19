<?php
/**
 * WP Meetup Doorbell Meetup Calendar.
 *
 * @since   0.1.0
 * @package WP_Meetup_Doorbell
 */

/**
 * WP Meetup Doorbell Meetup Calendar.
 *
 * @since 0.1.0
 */
class WPMD_Meetup_Calendar {

	/**
	 * Meetup slug.
	 *
	 * @var null
	 */
	private $meetup_slug = null;

	/**
	 * Url for the meetup ical feed.
	 *
	 * @var null
	 */
	private $meetup_calendar_url = null;

	/**
	 * Constructor.
	 *
	 * @since  0.1.0
	 */
	public function __construct() {
		$doorbell_options          = get_option( 'wp_meetup_doorbell_options' );
		$this->meetup_slug         = $doorbell_options['meetup_slug'];
		$this->meetup_calendar_url = sprintf( "http://www.meetup.com/%s/events/ical/", $this->meetup_slug );

		require $dir = plugin_dir_path( __DIR__ ) . 'vendor/class.iCalReader.php';
	}

	public function get_events() {
		if ( $ical_feed = $this->retrieve_feed() ) {
			$ical_feed_array = explode( PHP_EOL, $ical_feed );
			$ical = new ical( $ical_feed_array );
			return $ical->event();
		}
		return array();
	}

	public function retrieve_feed(){
		$meetup_ical_file = wp_remote_get( $this->meetup_calendar_url );

		if ( ! is_wp_error( $meetup_ical_file ) && isset( $meetup_ical_file['body'] ) && '200' == $meetup_ical_file['response']['code'] ) {
			return $meetup_ical_file['body'];
		}

		return false;
	}
}

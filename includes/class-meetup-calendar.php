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
		$doorbell_options  = get_option( 'wp_meetup_doorbell_options' );
		$this->meetup_slug = $doorbell_options['meetup_slug'];
	}
}

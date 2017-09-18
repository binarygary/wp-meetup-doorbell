<?php
/**
 * WP Meetup Doorbell Meetup Event.
 *
 * @since   0.1.0
 * @package WP_Meetup_Doorbell
 */

require_once dirname( __FILE__ ) . '/../vendor/cpt-core/CPT_Core.php';
require_once dirname( __FILE__ ) . '/../vendor/cmb2/init.php';

/**
 * WP Meetup Doorbell Meetup Event post type class.
 *
 * @since 0.1.0
 *
 * @see   https://github.com/WebDevStudios/CPT_Core
 */
class WPMD_Meetup_Event extends CPT_Core {
	/**
	 * Parent plugin class.
	 *
	 * @var WP_Meetup_Doorbell
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * The post id of the event we're working with.
	 *
	 * @var int null
	 */
	protected $event_id = null;

	/**
	 * Constructor.
	 *
	 * Register Custom Post Types.
	 *
	 * See documentation in CPT_Core, and in wp-includes/post.php.
	 *
	 * @since  0.1.0
	 *
	 * @param  WP_Meetup_Doorbell $plugin Main plugin object.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();

		// Register this cpt.
		// First parameter should be an array with Singular, Plural, and Registered name.
		parent::__construct(
			array(
				esc_html__( 'Meetup Event', 'wp-meetup-doorbell' ),
				esc_html__( 'Meetup Events', 'wp-meetup-doorbell' ),
				'wpmd-meetup-event',
			),
			array(
				'public'   => true,
				'show_ui'  => true,
				'supports' => array( 'title', 'custom-fields' ),
			)
		);
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.1.0
	 */
	public function hooks() {
		add_action( 'wp-meetup-doorbell-cron', array( $this, 'update_calendar' ) );
	}

	/**
	 * Update the events.
	 */
	public function update_calendar() {
		$calendar = new WPMD_Meetup_Calendar();
		array_map( array( $this, 'insert_or_update_event' ), $calendar->get_events() );
	}

	/**
	 * Insert a new event or update existing.
	 *
	 * @param array() $event The event info.
	 */
	public function insert_or_update_event( $event ) {
		$this->set_post_id_meetup_event( $event );
		$this->update_event( $event );
		$this->add_event_meta( $event );
	}

	/**
	 * Set the meetup ID.
	 *
	 * @param array() $event The event info.
	 */
	public function set_post_id_meetup_event( $event ) {
		global $wpdb;

		$this->event_id = $wpdb->get_var( $wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'meetup_UID' AND meta_value = '%s'",
			$event['UID']
		) );
	}

	/**
	 * Actually do the insert.
	 *
	 * @param array() $event The event info.
	 */
	public function update_event( $event ) {
		$event_post = array(
			'ID'           => $this->event_id,
			'post_content' => str_replace( '\n', PHP_EOL, $event['DESCRIPTION'] ),
			'post_type'    => 'wpmd-meetup-event',
			'post_title'   => str_replace( '\n', PHP_EOL, $event['SUMMARY'] ),
			'post_status'  => 'publish',
		);

		$this->event_id = wp_insert_post( $event_post );
	}

	/**
	 * Update the meta.
	 *
	 * @param array() $event The event info.
	 */
	public function add_event_meta( $event ) {
		update_post_meta( $this->event_id, 'meetup_UID', $event['UID'] );
		update_post_meta( $this->event_id, 'meetup_URL', $event['URL'] );
		update_post_meta( $this->event_id, 'meetup_coods', $event['GEO'] );
		update_post_meta( $this->event_id, 'meetup_start', strtotime( $event['DTSTART'] ) );
		update_post_meta( $this->event_id, 'meetup_end', strtotime( $event['DTEND'] ) );
	}
}

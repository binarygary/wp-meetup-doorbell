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
				'public'  => false,
				'show_ui' => false,
			)
		);
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.1.0
	 */
	public function hooks() {
		add_action( 'cmb2_init', array( $this, 'fields' ) );
		add_action( 'wp-meetup-doorbell-cron', array( $this, 'update_calendar' ) );
	}

	/**
	 * Add custom fields to the CPT.
	 *
	 * @since  0.1.0
	 */
	public function fields() {

		// Set our prefix.
		$prefix = 'wpmd_meetup_event_';

		// Define our metaboxes and fields.
		$cmb = new_cmb2_box( array(
			'id'            => $prefix . 'metabox',
			'title'         => esc_html__( 'WP Meetup Doorbell Meetup Event Meta Box', 'wp-meetup-doorbell' ),
			'object_types'  => array( 'wpmd-meetup-event' ),
		) );
	}

	/**
	 * Registers admin columns to display. Hooked in via CPT_Core.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $columns Array of registered column names/labels.
	 * @return array          Modified array.
	 */
	public function columns( $columns ) {
		$new_column = array();
		return array_merge( $new_column, $columns );
	}

	/**
	 * Handles admin column display. Hooked in via CPT_Core.
	 *
	 * @since  0.1.0
	 *
	 * @param array   $column   Column currently being rendered.
	 * @param integer $post_id  ID of post to display column for.
	 */
	public function columns_display( $column, $post_id ) {
		switch ( $column ) {
		}
	}

	public function update_calendar() {
		$calendar = new WPMD_Meetup_Calendar();
		foreach( $calendar->get_events() as $event) {
			// Check the UID and add/update.
		}
	}
}

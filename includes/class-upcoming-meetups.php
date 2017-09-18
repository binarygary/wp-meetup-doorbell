<?php
/**
 * WP Meetup Doorbell Upcoming Meetups.
 *
 * @since   0.1.0
 * @package WP_Meetup_Doorbell
 */

/**
 * WP Meetup Doorbell Upcoming Meetups class.
 *
 * @since 0.1.0
 */
class WPMD_Upcoming_Meetups extends WP_Widget {

	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $widget_slug = 'wp-meetup-doorbell-upcoming-meetups';


	/**
	 * Widget name displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $widget_name = '';


	/**
	 * Default widget title displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 * @since  0.1.0
	 */
	protected $default_widget_title = '';

	/**
	 * Shortcode name for this widget
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected static $shortcode = 'wp-meetup-doorbell-upcoming-meetups';

	/**
	 * Construct widget class.
	 *
	 * @since  0.1.0
	 */
	public function __construct() {

		$this->widget_name = esc_html__( 'Upcoming Meetups', 'wp-meetup-doorbell' );
		$this->default_widget_title = esc_html__( 'Upcoming Meetups', 'wp-meetup-doorbell' );

		parent::__construct(
			$this->widget_slug,
			$this->widget_name,
			array(
				'classname'   => $this->widget_slug,
				'description' => esc_html__( 'List of upcoming meetup events.', 'wp-meetup-doorbell' ),
			)
		);

		// Clear cache on save.
		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

		// Add a shortcode for our widget.
		add_shortcode( self::$shortcode, array( __CLASS__, 'get_widget' ) );
	}

	/**
	 * Delete this widget's cache.
	 *
	 * Note: Could also delete any transients
	 * delete_transient( 'some-transient-generated-by-this-widget' );
	 *
	 * @since  0.1.0
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $args     The widget arguments set up when a sidebar is registered.
	 * @param  array $instance The widget settings as set by user.
	 */
	public function widget( $args, $instance ) {

		// Set widget attributes.
		$atts = array(
			'before_widget' => $args['before_widget'],
			'after_widget'  => $args['after_widget'],
			'before_title'  => $args['before_title'],
			'after_title'   => $args['after_title'],
			'title'         => $instance['title'],
			'text'          => $instance['text'],
		);

		// Display the widget.
		echo self::get_widget( $atts ); // WPCS XSS OK.
	}

	/**
	 * Return the widget/shortcode output
	 *
	 * @since  0.1.0
	 *
	 * @param  array $atts Array of widget/shortcode attributes/args.
	 * @return string      Widget output
	 */
	public static function get_widget( $atts ) {

		$defaults = array(
			'before_widget' => '',
			'after_widget'  => '',
			'before_title'  => '',
			'after_title'   => '',
			'title'         => '',
		);

		// Parse defaults and create a shortcode.
		$atts = shortcode_atts( $defaults, (array) $atts, self::$shortcode );

		// Start an output buffer.
		ob_start();

		// Start widget markup.
		echo $atts['before_widget']; // WPCS XSS OK.

		// Maybe display widget title.
		echo ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '' ; // WPCS XSS OK.

		// Display widget text.
		self::display_upcoming_meetups();

		// End the widget markup.
		echo $atts['after_widget']; // WPCS XSS OK.

		// Return the output buffer.
		return ob_get_clean();
	}

	public static function display_upcoming_meetups() {
		$meetups = self::get_upcoming_meetup_posts()
		while( $meetups->have_posts() ) {

		}
	}

	public static function get_upcoming_meetups_posts() {
		$args = array(
			'post_type' => 'wpmd-meetup-event',
			'posts_per_page' => 5,
			'orderby' => 'meta_value_num',
			'meta_key' => 'meetup_start',
			'order' => 'ASC',
		);
		return new WP_Query( $args );
	}

	/**
	 * Update form values as they are saved.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $new_instance New settings for this instance as input by the user.
	 * @param  array $old_instance Old settings for this instance.
	 * @return array               Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		// Previously saved values.
		$instance = $old_instance;

		// Sanity check new data existing.
		$title = isset( $new_instance['title'] ) ? $new_instance['title'] : '';

		// Sanitize title before saving to database.
		$instance['title'] = sanitize_text_field( $title );

		// Flush cache.
		$this->flush_widget_cache();

		return $instance;
	}

	/**
	 * Back-end widget form with defaults.
	 *
	 * @since  0.1.0
	 *
	 * @param  array $instance Current settings.
	 */
	public function form( $instance ) {

		// Set defaults.
		$defaults = array(
			'title' => $this->default_widget_title,
		);

		// Parse args.
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
				<?php esc_html_e( 'Title:', 'wp-meetup-doorbell' ); ?>
			</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" placeholder="optional" />
		</p>
		<?php
	}
}

/**
 * Register widget with WordPress.
 */
function wp_meetup_doorbell_register_upcoming_meetups() {
	register_widget( 'WPMD_Upcoming_Meetups' );
}
add_action( 'widgets_init', 'wp_meetup_doorbell_register_upcoming_meetups' );

<?php
/**
 * WP Meetup Doorbell Options.
 *
 * @since   0.1.0
 * @package WP_Meetup_Doorbell
 */

require_once dirname( __FILE__ ) . '/../vendor/cmb2/init.php';

/**
 * WP Meetup Doorbell Options class.
 *
 * @since 0.1.0
 */
class WPMD_Options {
	/**
	 * Parent plugin class.
	 *
	 * @var    WP_Meetup_Doorbell
	 * @since  0.1.0
	 */
	protected $plugin = null;

	/**
	 * Option key, and option page slug.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $key = 'wp_meetup_doorbell_options';

	/**
	 * Options page metabox ID.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $metabox_id = 'wp_meetup_doorbell_options_metabox';

	/**
	 * Options Page title.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $title = '';

	/**
	 * Options Page hook.
	 *
	 * @var string
	 */
	protected $options_page = '';

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

		// Set our title.
		$this->title = esc_attr__( 'WP Meetup', 'wp-meetup-doorbell' );
	}

	/**
	 * Initiate our hooks.
	 *
	 * @since  0.1.0
	 */
	public function hooks() {

		// Hook in our actions to the admin.
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		
		add_action( 'cmb2_admin_init', array( $this, 'add_options_page_metabox' ) );
		
	}

	/**
	 * Register our setting to WP.
	 *
	 * @since  0.1.0
	 */
	public function admin_init() {
		register_setting( $this->key, $this->key );
	}

	/**
	 * Add menu options page.
	 *
	 * @since  0.1.0
	 */
	public function add_options_page() {
		$this->options_page = add_menu_page(
			$this->title,
			$this->title,
			'manage_options',
			$this->key,
			array( $this, 'admin_page_display' ),
			'dashicons-phone',
			72
		);

		// Include CMB CSS in the head to avoid FOUC.
		add_action( "admin_print_styles-{$this->options_page}", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2.
	 *
	 * @since  0.1.0
	 */
	public function admin_page_display() {
		?>
		<div class="wrap cmb2-options-page <?php echo esc_attr( $this->key ); ?>">
			<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
			<?php cmb2_metabox_form( $this->metabox_id, $this->key ); ?>
		</div>
		<?php
	}

	/**
	 * Add custom fields to the options page.
	 *
	 * @since  0.1.0
	 */
	public function add_options_page_metabox() {

		// Add our CMB2 metabox.
		$cmb = new_cmb2_box( array(
			'id'         => $this->metabox_id,
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove.
				'key'   => 'options-page',
				'value' => array( $this->key ),
			),
		) );

		// Slack endpoint.
		$cmb->add_field( array(
			'name'    => __( 'Slack Endpoint', 'wp-meetup-doorbell' ),
			'desc'    => __( 'format: https://hooks.slack.com/services/T00000000/B00000000/xxxxxxxxxxxxxxxxxxxxxxxx', 'wp-meetup-doorbell' ),
			'id'      => 'slack_endpoint',
			'type'    => 'text',
		) );

		// Meetup group name.
		$cmb->add_field( array(
			'name' => __( 'Meetup slug', 'wp-meetup-doorbell' ),
			'desc' => __( 'example: \'wordpress-jacksonville\' in https://www.meetup.com/wordpress-jacksonville/', 'wp-meetup-doorbell' ),
			'id'   => 'meetup_slug',
			'type' => 'text',
		) );

		$cmb->add_field( [
			'name' => __( 'Temporarily disable slack notices', 'wp-meetup-doorbell' ),
			'id'   => 'no_slack',
			'type' => 'checkbox',
		] );

		$cmb->add_field( [
			'name' => __( 'Notify Phone Numbers', 'wp-meetup-doorbell' ),
			'desc' => __( 'Comma separted list of 10 digit phone numbers to also notify', 'wp-meetup-doorbell' ),
			'id'   => 'also_notify',
			'type' => 'text',
		] );

		$cmb->add_field( [
			'name' => __( 'Twilio SID', 'wp-meetup-doorbell' ),
			'id'   => 'twilio_sid',
			'type' => 'text',
		] );

		$cmb->add_field( [
			'name' => __( 'Twilio Token', 'wp-meetup-doorbell' ),
			'id'   => 'twilio_token',
			'type' => 'text',
		] );

	}
}

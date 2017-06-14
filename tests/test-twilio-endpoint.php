<?php
/**
 * WP Meetup Doorbell Twilio Endpoint Tests.
 *
 * @since   0.1.0
 * @package WP_Meetup_Doorbell
 */
class WPMD_Twilio_Endpoint_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  0.1.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'WPMD_Twilio_Endpoint' ) );
	}

	/**
	 * Test that we can access our class through our helper function.
	 *
	 * @since  0.1.0
	 */
	function test_class_access() {
		$this->assertInstanceOf( 'WPMD_Twilio_Endpoint', wp_meetup_doorbell()->twilio-endpoint );
	}

	/**
	 * Replace this with some actual testing code.
	 *
	 * @since  0.1.0
	 */
	function test_sample() {
		$this->assertTrue( true );
	}
}

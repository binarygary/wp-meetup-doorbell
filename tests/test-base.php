<?php
/**
 * WP_Meetup_Doorbell.
 *
 * @since   0.1.0
 * @package WP_Meetup_Doorbell
 */
class WP_Meetup_Doorbell_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  0.1.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'WP_Meetup_Doorbell') );
	}

	/**
	 * Test that our main helper function is an instance of our class.
	 *
	 * @since  0.1.0
	 */
	function test_get_instance() {
		$this->assertInstanceOf(  'WP_Meetup_Doorbell', wp_meetup_doorbell() );
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

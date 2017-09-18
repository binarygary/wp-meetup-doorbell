<?php
/**
 * WP Meetup Doorbell Meetup Event Tests.
 *
 * @since   0.1.0
 * @package WP_Meetup_Doorbell
 */
class WPMD_Meetup_Event_Test extends WP_UnitTestCase {

	/**
	 * Test if our class exists.
	 *
	 * @since  0.1.0
	 */
	function test_class_exists() {
		$this->assertTrue( class_exists( 'WPMD_Meetup_Event') );
	}

	/**
	 * Test that we can access our class through our helper function.
	 *
	 * @since  0.1.0
	 */
	function test_class_access() {
		$this->assertInstanceOf( 'WPMD_Meetup_Event', wp_meetup_doorbell()->meetup-event' );
	}

	/**
	 * Test to make sure the CPT now exists.
	 *
	 * @since  0.1.0
	 */
	function test_cpt_exists() {
		$this->assertTrue( post_type_exists( 'wpmd-meetup-event' ) );
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

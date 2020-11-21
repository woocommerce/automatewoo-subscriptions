<?php

namespace AutomateWoo_Subscriptions;

defined( 'ABSPATH' ) || exit;

/**
 * Change a subscription's next payment date.
 *
 *
 * @class Action_Subscription_Update_Next_Payment
 * @since 1.2.2
 */
class Action_Subscription_Update_Next_Payment extends Abstract_Action_Subscription {

	private $original_payment_date;
	/**
	 * Explain to store admin what this action does via a unique title and description.
	 */
	public function load_admin_details() {
		parent::load_admin_details();
		$this->title       = __( 'Update Next Payment', 'automatewoo-subscriptions' );
		$this->description = __( 'Change a subscription\'s next payment date.', 'automatewoo-subscriptions' );
	}

	/**
	 * Edit the item managed by this class on the subscription passed in the workflow's trigger
	 *
	 * @throws \Exception When there is an error.
	 */
	public function edit_subscription( $subscription ) {
		$this->original_payment_date = $subscription->get_date( 'next_payment' );
		$date_string = $this->get_new_payment_date();
		$new_payment_date_string = wcs_get_datetime_from( $date_string );
		$subscription->update_dates(
			array(
				'next_payment' => wcs_get_datetime_utc_string( $new_payment_date_string ),
			)
		);
	}

	function load_fields() {
		$this->load_subscription_renewal_fields();
	}

	protected function load_subscription_renewal_fields() {
		$date = new \AutomateWoo\Fields\Date();
		$date->set_required();
		$date->set_name( 'new_payment_date' );
		$date->set_title( __( 'New Payment Date', 'automatewoo-subscriptions' ) );
		$this->add_field( $date );

		$time = new \AutomateWoo\Fields\Time();
		$time->set_required();
		$time->set_name( 'new_payment_time' );
		$time->set_title( __( 'New Payment Time', 'automatewoo-subscriptions' ) );
		$this->add_field( $time );
	}

	private function get_new_payment_date() {
		return sprintf(
			'%1$s %2$s:00',
			$this->get_option( 'new_payment_date' ),
			implode( ':', $this->get_option( 'new_payment_time' ) )
		);
	}

	/**
	 * Create a note recording the shipping method ID and workflow name to add after updating shipping.
	 *
	 * Helpful for tracing the history of this action by viewing the subscription's notes.
	 *
	 * @param array $shipping_data Shipping line item data. Same data as the return value of @see $this->get_object_for_edit().
	 * @return string
	 */
	protected function get_note( $subscription ) {
		return sprintf(
			__( '%1$s workflow run: updated next payment date on subscription from %2$s to %3$s', 'automatewoo-subscriptions' ),
			$this->workflow->get_title(),
			$this->original_payment_date,
			$subscription->get_date( 'next_payment' )
		);
	}
}

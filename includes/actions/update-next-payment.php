<?php

namespace AutomateWoo_Subscriptions;

defined( 'ABSPATH' ) || exit;

/**
 * Change a subscription's next payment date.
 *
 *
 * @class Action_Subscription_Update_Next_Payment
 * @since 1.0.0
 */
class Action_Subscription_Update_Next_Payment extends Abstract_Action_Subscription {


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
	public function run() {
		$subscription     = $this->get_subscription_to_edit();
		$old_payment_date = $subscription->get_date( 'next_payment' );
		$subscription->update_meta_data( '_old_schedule_next_payment', $old_payment_date );
		$subscription->save();
		$date_string = sprintf(
			'%1$s %2$s:00',
			$this->get_option( 'new_payment_date' ),
			implode( ':', $this->get_option( 'new_payment_time' ) )
		);
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
}
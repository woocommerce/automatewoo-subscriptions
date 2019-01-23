<?php

namespace AutomateWoo_Subscriptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Change a subscription's billing period or interval.
 *
 * While the billing schedule is not a line item, this class still extends Action_Subscription_Edit_Item_Abstract
 * as it provides many useful methods for editing a subscription's billing schedule.
 *
 * @class Action_Subscription_Update_Schedule
 * @since 1.0.0
 */
class Action_Subscription_Update_Schedule extends \AutomateWoo\Action_Subscription_Edit_Item_Abstract {


	/**
	 * Explain to store admin what this action does via a unique title and description.
	 */
	function load_admin_details() {
		parent::load_admin_details();
		$this->title       = __( 'Update Schedule', 'automatewoo-subscriptions' );
		$this->description = __( 'Update a subscriptions billing period or interval. When combined with an action to modify line items, this can be used to ship products on a different schedule to those which they are billed. After the schedule is updated, the next payment date will also be recalculated using the new schedule.', 'automatewoo-subscriptions' );
	}


	/**
	 * Add billing interval & period selection field to the action's admin UI.
	 */
	function load_fields() {
		$this->add_billing_interval_field();
		$this->add_billing_period_field();
		$this->add_recalculate_field();
	}


	/**
	 * Method to get the billing schedule to set on the subscription.
	 *
	 * @return array
	 */
	protected function get_object_for_edit() {
		return [
			'billing_interval' => $this->get_option( 'billing_interval' ),
			'billing_period'   => $this->get_option( 'billing_period' ),
		];
	}


	/**
	 * Set the chosen biling interval and period on a subscription.
	 *
	 * @param array            $billing_schedule Billing schedule data. Same data as the return value of @see $this->get_object_for_edit().
	 * @param \WC_Subscription $subscription Instance of the subscription being edited by this action.
	 *
	 * @throws \Exception When there is an error.
	 */
	protected function edit_subscription( $billing_schedule, $subscription ) {

		if ( ! empty( $billing_schedule['billing_interval'] ) ) {
			$subscription->set_billing_interval( $billing_schedule['billing_interval'] );
		}

		if ( ! empty( $billing_schedule['billing_period'] ) ) {
			$subscription->set_billing_period( $billing_schedule['billing_period'] );
		}

		if ( $this->get_option( 'recalculate_dates' ) ) {

			$new_next_payment = $subscription->calculate_date( 'next_payment' );

			if ( $new_next_payment > 0 ) {

				$dates_to_update = array( 'next_payment' => $new_next_payment );

				if ( strtotime( $new_next_payment ) < $subscription->get_time( 'trial_end' ) ) {
					$dates_to_update['trial_end'] = $new_next_payment;
				}

				$subscription->update_dates( array( 'next_payment' => $new_next_payment ) );
			} else { // delete the stored date
				$subscription->delete_date( 'next_payment' );
			}
		}

		$subscription->save();
	}


	/**
	 * Get the note to record on the subscription to record the line item change
	 *
	 * @param mixed $object WC_Product, WC_Coupon, or some other WooCommerce data type. Will be the same data type as the return value of @see $this->get_object_for_edit().
	 * @return string
	 */
	protected function get_note( $billing_schedule ) {
		return sprintf( __( '%1$s workflow run: updated subscription schedule to renew %2$s %3$s. (Workflow ID: %4$d)', 'automatewoo-subscriptions' ), $this->workflow->get_title(), wcs_get_subscription_period_interval_strings( $billing_schedule['billing_interval'] ), wcs_get_subscription_period_strings( 1, $billing_schedule['billing_period'] ), $this->workflow->get_id() );
	}


	/**
	 * Add a number field to input the billing interval
	 */
	protected function add_billing_interval_field() {
		$input = new \AutomateWoo\Fields\Number();
		$input->set_required();
		$input->set_min( 1 );
		$input->set_name( 'billing_interval' );
		$input->set_title( __( 'Billing Interval', 'automatewoo-subscriptions' ) );
		$input->set_placeholder( __( 'No change', 'automatewoo-subscriptions' ) );
		$input->set_description( __( 'The frequency to process renewals. For example, if an interval of 3 is input with a chosen Billing Period of "month", then the billing schedule will be set to renew quarterly - every 3 months.', 'automatewoo-subscriptions' ) );

		$this->add_field( $input );
	}


	/**
	 * Add a select field for the billing period
	 */
	protected function add_billing_period_field() {
		$select = new \AutomateWoo\Fields\Select();
		$select->set_required();
		$select->set_name( 'billing_period' );
		$select->set_title( __( 'Billing Period', 'automatewoo-subscriptions' ) );
		$select->set_options( wcs_get_available_time_periods() );
		$this->add_field( $select );
	}


	/**
	 * Add a coupon selection field for this action
	 */
	protected function add_recalculate_field() {
		$field = new \AutomateWoo\Fields\Checkbox();
		$field->set_name( 'recalculate_dates' );
		$field->set_title( __( 'Recalculate Dates', 'automatewoo-subscriptions' ) );
		$field->set_description( __( 'Optionally recalculate the next payment and trial end dates based on the new billing schedule.', 'automatewoo-subscriptions' ) );
		$field->default_to_checked = true;
		$this->add_field( $field );
	}
}

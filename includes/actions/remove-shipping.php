<?php

namespace AutomateWoo_Subscriptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Action to remove a shipping method from a subscription.
 *
 * @class Action_Subscription_Remove_Shipping
 * @since 4.4
 */
class Action_Subscription_Remove_Shipping extends Action_Subscription_Add_Shipping {


	/**
	 * Flag to define whether to include an "All shipping methods" option in the shipping
	 * method select field for this action.
	 *
	 * @var bool
	 */
	protected $include_all_shipping_method = true;


	/**
	 * Flag to define whether the instance of this action requires a name input field to
	 * be displayed on the action's admin UI.
	 *
	 * @var bool
	 */
	protected $load_name_field = false;


	/**
	 * Flag to define whether the instance of this action requires a price input field to
	 * be displayed on the action's admin UI.
	 *
	 * @var bool
	 */
	protected $load_cost_field = false;


	/**
	 * Explain to store admin what this action does via a unique title and description.
	 */
	function load_admin_details() {
		parent::load_admin_details();
		$this->title       = __( 'Remove Shipping', 'automatewoo-subscriptions' );
		$this->description = __( 'Remove a shipping line item or items from a subscription, if any line items match the chosen shipping method. This is useful for bulk editing subscriptions, or to change the shipping charged to a subscriber at different stages of their subscription\'s lifecycle. Please note: all line items for the chosen shipping method will be removed.', 'automatewoo-subscriptions' );
	}


	/**
	 * Add a given shipping as a line item to a given subscription.
	 *
	 * @param array            $shipping_data Shipping line item data. Same data as the return value of @see $this->get_object_for_edit().
	 * @param \WC_Subscription $subscription Instance of subscription to add the shipping to.
	 */
	protected function edit_subscription( $shipping_data, $subscription ) {

		foreach ( $subscription->get_shipping_methods() as $line_item ) {
			// Same approach used in Abstract_WC_Order::has_shipping_method() to check for method
			if ( 0 === strpos( $shipping_method->get_method_id(), $shipping_data['shipping_method_id'] ) ) {
				$subscription->remove_item( $line_item->get_id() );
			}
		}

		$subscription->save();
		$subscription->calculate_totals();
	}


	/**
	 * Create a note recording the shipping method ID and workflow name to add after removing shipping.
	 *
	 * Helpful for tracing the history of this action by viewing the subscription's notes.
	 *
	 * @param array $shipping_data Shipping line item data. Same data as the return value of @see $this->get_object_for_edit().
	 * @return string
	 */
	protected function get_note( $shipping_data ) {
		return sprintf( __( '%1$s workflow run: removed shipping method from subscription. (Shipping Method ID: %2$d; Workflow ID: %3$d)', 'automatewoo-subscriptions' ), $this->workflow->get_title(), $shipping_data['shipping_method_id'], $this->workflow->get_id() );
	}
}

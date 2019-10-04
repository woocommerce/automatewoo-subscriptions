<?php

namespace AutomateWoo_Subscriptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Action to add a chosen tax line item to a subscription with a chosen cost.
 *
 * @class Action_Subscription_Add_Tax
 * @since 4.4
 */
class Action_Subscription_Add_Tax extends Abstract_Action_Subscription_Edit_Tax {


	/**
	 * Overload parent::$requires_quantity_field to prevent the quantity field being added by
	 * parent::load_fields(), as it is not used for tax removal.
	 *
	 * @var bool
	 */
	protected $load_quantity_field = false;


	/**
	 * Flag to define whether the instance of this action requires a name text input field.
	 *
	 * @var bool
	 */
	protected $load_name_field = true;


	/**
	 * Explain to store admin what this action does via a unique title and description.
	 */
	function load_admin_details() {
		parent::load_admin_details();
		$this->title       = __( 'Add Tax', 'automatewoo-subscriptions' );
		$this->description = __( 'Add a new tax line item on a subscription. This action can be used to change the tax charged to a subscriber at different stages of their subscription\'s lifecycle.', 'automatewoo-subscriptions' );
	}


	/**
	 * Add a given tax as a line item to a given subscription.
	 *
	 * Based on WC_AJAX::add_order_tax() which is used for adding tax via the Edit Order/Subscription admin screens.
	 *
	 * @param array            $tax_data Tax line item data. Same data as the return value of @see $this->get_object_for_edit().
	 * @param \WC_Subscription $subscription Instance of subscription to add the tax to.
	 */
	protected function edit_subscription( $tax_data, $subscription ) {

		error_log( '$tax_data = ' . print_r( $tax_data, true ) );

		$item = new \WC_Order_Item_Tax();

		$item->set_rate( $tax_data['tax_rate_id'] );
		$item->set_order_id( $subscription->get_id() );
		$item->save();

		error_log( '$item = ' . print_r( $item, true ) );

		$subscription->add_item( $item );
		$subscription->save();
		$subscription->calculate_totals();

		error_log( '$subscription = ' . print_r( $subscription, true ) );
	}


	/**
	 * Get a message to add to the subscription to record the tax being added by this action.
	 *
	 * Helpful for tracing the history of this action by viewing the subscription's notes.
	 *
	 * @param \WC_Product $tax Product being added to the subscription. Required so its name can be added to the order note.
	 * @return string
	 */
	protected function get_note( $tax_data ) {
		return sprintf( __( '%1$s workflow run: added %2$s to subscription. (Tax Rate: %3$d; Workflow ID: %4$d)', 'automatewoo-subscriptions' ), $this->workflow->get_title(), $tax_data['line_item_name'], $tax_data['tax_rate_id'], $this->workflow->get_id() );
	}
}

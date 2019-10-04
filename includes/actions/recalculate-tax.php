<?php

namespace AutomateWoo_Subscriptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Action to update a tax rate from a subscription.
 *
 * @class Action_Subscription_Update_Tax
 * @since 4.4
 */
class Action_Subscription_Recalculate_Tax extends Abstract_Action_Subscription_Edit_Tax {


	/**
	 * Flag to define whether to include the tax rate select field.
	 *
	 * @var bool
	 */
	protected $load_tax_rate_field = false;


	/**
	 * Explain to store admin what this action does via a unique title and description.
	 */
	function load_admin_details() {
		parent::load_admin_details();
		$this->title       = __( 'Update Tax', 'automatewoo-subscriptions' );
		$this->description = __( 'Recalculate tax on a subscription. This is useful for bulk editing subscriptions when new tax rates are introduced. All existing tax rates will be removed, and new rates added based on the subscription billing or shipping address (as set on WooCommerce > Settings > Tax > Calculate tax based on).', 'automatewoo-subscriptions' );
	}


	/**
	 * Add a given tax as a line item to a given subscription.
	 *
	 * @param array            $tax_data Tax line item data. Same data as the return value of @see $this->get_object_for_edit().
	 * @param \WC_Subscription $subscription Instance of subscription to add the tax to.
	 */
	protected function edit_subscription( $tax_data, $subscription ) {
		$subscription->calculate_totals( true );
	}


	/**
	 * Create a note recording the tax rate ID and workflow name to add after updating tax.
	 *
	 * Helpful for tracing the history of this action by viewing the subscription's notes.
	 *
	 * @param array $tax_data Tax line item data. Same data as the return value of @see $this->get_object_for_edit().
	 * @return string
	 */
	protected function get_note( $tax_data ) {
		return sprintf( __( '%1$s workflow run: updated tax on subscription. (Tax Rate: %2$d; Workflow ID: %3$d)', 'automatewoo-subscriptions' ), $this->workflow->get_title(), $tax_data['tax_rate_id'], $this->workflow->get_id() );
	}
}

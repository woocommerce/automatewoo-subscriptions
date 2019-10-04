<?php

namespace AutomateWoo_Subscriptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Action to remove a tax rate from a subscription.
 *
 * @class Action_Subscription_Remove_Tax
 * @since 4.4
 */
class Action_Subscription_Remove_Tax extends Action_Subscription_Add_Tax {


	/**
	 * Flag to define whether to include an "All tax rates" option in the tax
	 * method select field for this action.
	 *
	 * @var bool
	 */
	protected $include_all_tax_rate = true;


	/**
	 * Flag to define whether the instance of this action requires a name input field to
	 * be displayed on the action's admin UI.
	 *
	 * @var bool
	 */
	protected $load_name_field = false;


	/**
	 * Explain to store admin what this action does via a unique title and description.
	 */
	function load_admin_details() {
		parent::load_admin_details();
		$this->title       = __( 'Remove Tax', 'automatewoo-subscriptions' );
		$this->description = __( 'Remove a tax line item or items from a subscription, if any line items match the chosen tax rate. This is useful for bulk editing subscriptions and to change the tax charged to a subscriber at different stages of their subscription\'s lifecycle. Please note: all line items for the chosen tax rate will be removed.', 'automatewoo-subscriptions' );
	}


	/**
	 * Add a given tax as a line item to a given subscription.
	 *
	 * @param array            $tax_data Tax line item data. Same data as the return value of @see $this->get_object_for_edit().
	 * @param \WC_Subscription $subscription Instance of subscription to add the tax to.
	 */
	protected function edit_subscription( $tax_data, $subscription ) {

		foreach ( $subscription->get_taxes() as $line_item ) {
			if ( $this->has_matching_rate( $tax_data['tax_rate_id'], $line_item ) ) {
				$subscription->remove_item( $line_item->get_id() );
			}
		}

		$subscription->save();
		$subscription->calculate_totals();
	}


	/**
	 * Check if a given item matches a given tax rate ID.
	 *
	 * Used when Updating and Removing tax rates, hence the separate method.
	 *
	 * @param string The rate ID
	 * @param \WC_Order_Item_Tax A tax line item
	 * @return bool
	 */
	protected function has_matching_rate( $tax_rate_id, $line_item ) {
		if ( 'all' === $tax_rate_id ) {
			return true;
		} elseif ( $line_item->get_rate_id() == $tax_rate_id ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 * Create a note recording the tax rate ID and workflow name to add after removing tax.
	 *
	 * Helpful for tracing the history of this action by viewing the subscription's notes.
	 *
	 * @param array $tax_data Tax line item data. Same data as the return value of @see $this->get_object_for_edit().
	 * @return string
	 */
	protected function get_note( $tax_data ) {
		return sprintf( __( '%1$s workflow run: removed tax rate from subscription. (Tax Rate: %2$d; Workflow ID: %3$d)', 'automatewoo-subscriptions' ), $this->workflow->get_title(), $tax_data['tax_rate_id'], $this->workflow->get_id() );
	}
}

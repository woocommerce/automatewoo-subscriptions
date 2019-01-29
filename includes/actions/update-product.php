<?php

namespace AutomateWoo_Subscriptions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Action to update a chosen product line item to a subscription with a chosen quantity.
 *
 * @class Action_Subscription_Update_Product
 * @since 4.4
 */
class Action_Subscription_Update_Product extends \AutomateWoo\Action_Subscription_Edit_Product_Abstract {


	/**
	 * Variable products should not be updateed as a line item to subscriptions, only variations.
	 *
	 * @var bool
	 */
	protected $allow_variable_products = false;


	/**
	 * Flag to define whether the instance of this action requires a name text input field.
	 *
	 * @var bool
	 */
	protected $load_name_field = true;


	/**
	 * Flag to define whether the instance of this action requires a price input field to
	 * be displayed on the action's admin UI.
	 *
	 * @var bool
	 */
	protected $load_cost_field = true;


	/**
	 * Do not require the quantity input field.
	 *
	 * @var bool
	 */
	protected $require_quantity_field = false;

	/**
	 * Add product fields to the action's admin UI, but make sure quantity field is not required.
	 *
	 * The Action_Subscription_Edit_Item_Abstract::$require_quantity_field prop was introduced in
	 * AutomateWoo 4.5, so we need to do it manually if we've got a version before that. Similarly,
	 * the quantity fields description was not customised with AW < 4.5, because the method for that
	 * Action_Subscription_Edit_Item_Abstract::get_quantity_field_description() wasn't being used.
	 */
	function load_fields() {
		parent::load_fields();
		if ( false === \PP_Dependencies::is_automatewoo_active( '4.5' ) ) {
			$this->fields['quantity']->set_required( false );
			$this->fields['quantity']->set_description( $this->get_quantity_field_description() );
		}
	}

	/**
	 * Explain to store admin what this action does via a unique title and description.
	 */
	function load_admin_details() {
		parent::load_admin_details();
		$this->title       = __( 'Update Product', 'automatewoo-subscriptions' );
		$this->description = __( 'Update an existing product line item on a subscription. Only the data set on the action will be updated. This action can be used for bulk editing subscriptions, like changing price or product name', 'automatewoo-subscriptions' );
	}


	/**
	 * Update a given product as a line item to a given subscription.
	 *
	 * @param \WC_Product      $product Product to update to the subscription.
	 * @param \WC_Subscription $subscription Instance of subscription to update the product to.
	 */
	protected function edit_subscription( $product, $subscription ) {

		$item = null;

		foreach ( $subscription->get_items() as $subscription_item ) {
			// Both of these ID getters will return the variation_id if the product is a variation.
			if ( $product->get_id() === $subscription_item->get_product_id() ) {
				$item = $subscription_item;
				break;
			}
		}

		// No item for that product on this subscription
		if ( empty( $item ) ) {
			return;
		}

		$update_product_args = array();

		if ( $this->get_option( 'line_item_name' ) ) {
			$update_product_args['name'] = $this->get_option( 'line_item_name', true );
		}

		if ( $this->get_option( 'line_item_cost' ) || $this->get_option( 'quantity' ) ) {

			$update_product_args['quantity'] = ( $this->get_option( 'quantity' ) ) ? $this->get_option( 'quantity' ) : $item->get_quantity();

			$update_product_args['subtotal'] = $update_product_args['total'] = wc_get_price_excluding_tax( $product, array(
				'price' => $this->get_option( 'line_item_cost' ),
				'qty'   => $update_product_args['quantity'],
			) );
		}

		if ( ! empty( $update_product_args ) ) {
			$item->set_props( $update_product_args );
			$item->save();
		}

		// Now we need to refresh the subscription to make sure it has the up-to-date line item then recalculate its totals so taxes etc. are updated
		$subscription = wcs_get_subscription( $subscription->get_id() );
		$subscription->calculate_totals();
	}


	/**
	 * Get the description to display on the quantity field for this action
	 */
	protected function get_quantity_field_description() {
		return __( 'Optionally set a new quantity for the product. Defaults to the current quantity set on the subscription.', 'automatewoo-subscriptions' );
	}

	/**
	 * Get a message to update to the subscription to record the product being updateed by this action.
	 *
	 * Helpful for tracing the history of this action by viewing the subscription's notes.
	 *
	 * @param \WC_Product $product Product being updateed to the subscription. Required so its name can be updateed to the order note.
	 * @return string
	 */
	protected function get_note( $product ) {
		return sprintf( __( '%1$s workflow run: updated %2$s on subscription. (Product ID: %3$d; Workflow ID: %4$d)', 'automatewoo-subscriptions' ), $this->workflow->get_title(), $product->get_name(), $product->get_id(), $this->workflow->get_id() );
	}
}

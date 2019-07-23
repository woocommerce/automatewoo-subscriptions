<?php

namespace AutomateWoo_Subscriptions;

use AutomateWoo\Action_Order_Add_Note;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * @class Action_Subscription_Add_Note
 * @since 1.1.0
 */
class Action_Subscription_Add_Note extends Action_Order_Add_Note {

	public $required_data_items = [ 'subscription' ];

	function load_admin_details() {
		$this->title = __( 'Add Note', 'automatewoo-subscriptions' );
		$this->group = __( 'Subscription', 'automatewoo-subscriptions' );
	}

	function run() {
		$note_type    = $this->get_option( 'note_type' );
		$note         = $this->get_option( 'note', true );
		$subscription = $this->workflow->data_layer()->get_subscription();

		if ( ! $note || ! $note_type || ! $subscription ) {
			return;
		}

		$subscription->add_order_note( $note, $note_type === 'customer', false );
	}
}

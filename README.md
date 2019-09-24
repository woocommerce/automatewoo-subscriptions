# AutomateWoo - Subscriptions Add-on

A subscription has a number of core parts:

* billing schedule, e.g. monthly or annually
* dates, like next payment date, end date or start date
* product line items
* coupon line items
* shipping line items
* tax line items
* fee line items

The AutomateWoo plugin makes it possible to modify the first two of these with the [Add/Remove Product](https://automatewoo.com/docs/actions/subscription-add-remove-product/) and [Add/Remove Coupon](https://automatewoo.com/docs/actions/subscription-add-remove-coupon/) actions.

The **AutomateWoo Subscriptions Add-on** makes it possible to modify some of the others parts by providing additional actions.

![](http://pic.pros.pr/6cf806f1ffe5/Screen%20Shot%202019-01-24%20at%202.19.02%20pm.png)

## New Subscriptions Actions

The **AutomateWoo Subscriptions Add-on** adds 4 new actions:

* **Update Schedule**: to change a subscription's billing period or interval.
* **Update Product**: to change a product line item's quantity, name or price.
* **Add Shipping**: to add a chosen shipping method as a new line item, with a custom cost and name, on subscriptions.
* **Update Shipping**: to update a shipping method's name or amount on a subscription.
* **Remove Shipping**: to remove a chosen shipping method from a subscription.
* **Update Currency**: to change the currency on a subscription.

These actions can be run on any [subscription trigger](https://automatewoo.com/docs/triggers/list/#subscriptions).

![New Actions](http://pic.pros.pr/a0e63624deaf/Screen%252520Shot%2525202019-01-24%252520at%2525202.17.23%252520pm.png)

### Applications

With these actions, it's possible to change a subscription's:

* billing schedule, for example, to switch a subscription from being billed annually to monthly
* shipping costs at different stages of the the subscription lifecycle, for example, to only charge shipping annually, despite renewing monthly

Combined with the existing built-in Subscriptions actions in AutomateWoo that can add or remove product and coupon line items, these actions make it possible to offer customers dynamic subscription lifecycles, like:

* magazines which ship monthly but are billed annually or quarterly
* [pre-paid subscriptions](https://automatewoo.com/docs/examples/pre-paid-subscriptions/), where a customer can choose to pay for a given period up-front
* sequential subscriptions, where a customer receives different items, at different costs, based on the date of their sign-up
* seasonal subscriptions, where a customer receives different items, at different costs, at different times of year

It's also possible to use these actions to [bulk edit subscriptions](https://automatewoo.com/docs/examples/bulk-update-subscription-prices/).

### Future Subscription Actions

We currently plan to add additional actions to modify:

* tax line items
* fee line items

In future, we may add actions to:

* change a subscription's dates, like the next payment or expiration date
* process a renewal for an active subscription
* regenerate downloadable product permissions (to update them based on the subscription's current product line items)

If any of these are interesting for your store, please [open a new Issue](https://github.com/Prospress/automatewoo-subscriptions/issues/new) and tell us more about your use case.

## Installation

Please note, this plugin is currently pre-release. It is a work in progress, being worked on in public rather than waiting until it is finished.

To install:

1. Download the latest version of the plugin [here](https://github.com/Prospress/automatewoo-subscriptions/archive/master.zip)
1. Go to **Plugins > Add New > Upload** administration screen on your WordPress site
1. Select the ZIP file you just downloaded
1. Click **Install Now**
1. Click **Activate**

### Updates

To keep the plugin up-to-date, use the [GitHub Updater](https://github.com/afragen/github-updater).

## Reporting Issues

If you find a problem, please [open a new Issue](https://github.com/Prospress/automatewoo-subscriptions/issues/new). If you would like to request a new feature for this plugin, please [use the ideas board](https://ideas.automatewoo.com/automatewoo).

---

<p align="center">
	<a href="https://prospress.com/">
		<img src="https://cloud.githubusercontent.com/assets/235523/11986380/bb6a0958-a983-11e5-8e9b-b9781d37c64a.png" width="160">
	</a>
</p>

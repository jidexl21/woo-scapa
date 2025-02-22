=== Scapa WooCommerce Payment Gateway ===
Contributors: 
Donate link: https://olulekeod.me/donate
Tags: scapa, woocommerce, payment gateway, olulekeod plugins, verve, ghana, kenya, nigeria, south africa, naira, cedi, rand, mastercard, visa
Requires at least: 6.2
Tested up to: 6.6
Stable tag: 5.8.2
Requires PHP: 7.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Scapa for WooCommerce allows your store in Nigeria, Ghana, Kenya, or South Africa to accept secure payments from multiple local and global payment channels.

== Description ==

Scapa makes it easy for businesses in Nigeria, Ghana, Kenya and South Africa to accept secure payments from multiple local and global payment channels. Integrate Scapa with your store today, and let your customers pay you with their choice of methods.

With Scapa for WooCommerce, you can accept payments via:

* Credit/Debit Cards — Visa, Mastercard, Verve (NG, GH, KE), American Express (SA only)
* Bank transfer (Nigeria)
* Mobile money (Ghana)
* Masterpass (South Africa)
* EFT (South Africa)
* USSD (Nigeria)
* Visa QR (Nigeria)
* Many more coming soon

= Why Scapa? =

* Start receiving payments instantly—go from sign-up to your first real transaction in as little as 15 minutes
* Simple, transparent pricing—no hidden charges or fees
* Modern, seamless payment experience via the Scapa Checkout — [Try the demo!](https://scapa.io/demo/checkout)
* Advanced fraud detection
* Understand your customers better through a simple and elegant dashboard
* Access to attentive, empathetic customer support 24/7
* Free updates as we launch new features and payment options
* Clearly documented APIs to build your custom payment experiences

Over 60,000 businesses of all sizes in Nigeria, Ghana, Kenya, and South Africa rely on Scapa's suite of products to receive payments and make payouts seamlessly. Sign up on [Scapa.io/signup](https://scapa.io/signup) to get started.


= Note =

This plugin is meant to be used by merchants in Ghana, Kenya, Nigeria and South Africa.

= Plugin Features =

*   __Accept payment__ via Mastercard, Visa, Verve, USSD, Mobile Money, Bank Transfer, EFT, Bank Accounts, GTB 737 & Visa QR.
*   __Seamless integration__ into the WooCommerce checkout page. Accept payment directly on your site
*   __Refunds__ from the WooCommerce order details page. Refund an order directly from the order details page
*   __Recurring payment__ using [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) plugin

= WooCommerce Subscriptions Integration =

*	The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) integration only works with __WooCommerce v2.6 and above__ and __WooCommerce Subscriptions v2.0 and above__.

*	No subscription plans is created on Scapa. The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) plugin handles all the subscription functionality.

*	If a customer pays for a subscription using a Mastercard or Visa card, their subscription will renew automatically throughout the duration of the subscription. If an automatic renewal fail their subscription will be put on-hold and they will have to login to their account to renew the subscription.

*	For customers paying with a Verve card, their subscription can't be renewed automatically, once a payment is due their subscription will be on-hold. The customer will have to login to his account to manually renew his subscription.

*	If a subscription has a free trial and no signup-fee, automatic renewal is not possible for the first payment because the initial order total will be 0, after the free trial the subscription will be put on-hold. The customer will have to login to his account to renew his subscription. If a Mastercard or Visa card is used to renew the subscription subsequent renewals will be automatic throughout the duration of the subscription, if a Verve card is used automatic renewal isn't possible.

= Suggestions / Feature Request =

If you have suggestions or a new feature request, feel free to get in touch with me via the contact form on my website [here](http://olulekeod.me/get-in-touch/)

You can also follow me on Twitter! **[@olulekeod](https://twitter.com/olulekeod)**


== Installation ==

*   Go to __WordPress Admin__ > __Plugins__ > __Add New__ from the left-hand menu
*   In the search box type __Scapa WooCommerce Payment Gateway__
*   Click on Install now when you see __Scapa WooCommerce Payment Gateway__ to install the plugin
*   After installation, __activate__ the plugin.


= Scapa Setup and Configuration =
*   Go to __WooCommerce > Settings__ and click on the __Payments__ tab
*   You'll see Scapa listed along with your other payment methods. Click __Set Up__
*   On the next screen, configure the plugin. There is a selection of options on the screen. Read what each one does below.

1. __Enable/Disable__ - Check this checkbox to Enable Scapa on your store's checkout
2. __Title__ - This will represent Scapa on your list of Payment options during checkout. It guides users to know which option to select to pay with Scapa. __Title__ is set to "Debit/Credit Cards" by default, but you can change it to suit your needs.
3. __Description__ - This controls the message that appears under the payment fields on the checkout page. Use this space to give more details to customers about what Scapa is and what payment methods they can use with it.
4. __Test Mode__ - Check this to enable test mode. When selected, the fields in step six will say "Test" instead of "Live." Test mode enables you to test payments before going live. The orders process with test payment methods, no money is involved so there is no risk. You can uncheck this when your store is ready to accept real payments.
5. __Payment Option__ - Select how Scapa Checkout displays to your customers. A popup displays Scapa Checkout on the same page, while Redirect will redirect your customer to make payment.
6. __API Keys__ - The next two text boxes are for your Scapa API keys, which you can get from your Scapa Dashboard. If you enabled Test Mode in step four, then you'll need to use your test API keys here. Otherwise, you can enter your live keys.
7. __Additional Settings__ - While not necessary for the plugin to function, there are some extra configuration options you have here. You can do things like add custom metadata to your transactions (the data will show up on your Scapa dashboard) or use Scapa's [Split Payment feature](https://scapa.io/docs/payments/split-payments). The tooltips next to the options provide more information on what they do.
8. Click on __Save Changes__ to update the settings.

To account for poor network connections, which can sometimes affect order status updates after a transaction, we __strongly__ recommend that you set a Webhook URL on your Scapa dashboard. This way, whenever a transaction is complete on your store, we'll send a notification to the Webhook URL, which will update the order and mark it as paid. You can set this up by using the URL in red at the top of the Settings page. Just copy the URL and save it as your webhook URL on your Scapa dashboard under __Settings > API Keys & Webhooks__ tab.

If you do not find Scapa on the Payment method options, please go through the settings again and ensure that:

*   You've checked the __"Enable/Disable"__ checkbox
*   You've entered your __API Keys__ in the appropriate field
*   You've clicked on __Save Changes__ during setup

== Frequently Asked Questions ==

= What Do I Need To Use The Plugin =

*   A Scapa merchant account—use an existing account or [create an account here](https://scapa.io/signup)
*   An active [WooCommerce installation](https://docs.woocommerce.com/document/installing-uninstalling-woocommerce/)
*   A valid [SSL Certificate](https://docs.woocommerce.com/document/ssl-and-https/)

= WooCommerce Subscriptions Integration =

*	The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) integration only works with WooCommerce v2.6 and above and WooCommerce Subscriptions v2.0 and above.

*	No subscription plans is created on Scapa. The [WooCommerce Subscriptions](https://woocommerce.com/products/woocommerce-subscriptions/) handles all the subscription functionality.

*	If a customer pays for a subscription using a MasterCard or Visa card, their subscription will renew automatically throughout the duration of the subscription. If an automatic renewal fail their subscription will be put on-hold and they will have to login to their account to renew the subscription.

*	For customers paying with a Verve card, their subscription can't be renewed automatically, once a payment is due their subscription will be on-hold. The customer will have to login to his account to manually renew his subscription.

*	If a subscription has a free trial and no signup-fee, automatic renewal is not possible because the order total will be 0, after the free trial the subscription will be put on-hold. The customer will have to login to his account to renew his subscription. If a MasterCard or Visa card is used to renew subsequent renewals will be automatic throughout the duration of the subscription, if a Verve card is used automatic renewal isn't possible.



== Screenshots ==

1. Scapa displayed as a payment method on the WooCommerce payment methods page

2. Scapa WooCommerce payment gateway settings page

3. Scapa on WooCommerce Checkout
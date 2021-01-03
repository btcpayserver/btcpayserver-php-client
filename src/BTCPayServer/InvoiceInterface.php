<?php
/**
 * @license Copyright 2019 BTCPayServer, MIT License
 * see https://github.com/btcpayserver/btcpayserver-php-client/blob/master/LICENSE
 */

namespace BTCPayServer;

/**
 * Invoice
 *
 * @package BTCPayServer
 */
interface InvoiceInterface
{
    /**
     * An invoice starts in this state.  When in this state and only in this state, payments
     * to the associated bitcoin address are credited to the invoice.  If an invoice has
     * received a partial payment, it will still reflect a status of new to the merchant
     * (from a merchant system perspective, an invoice is either paid or not paid, partial
     * payments and over payments are handled by BTCPayServer by either refunding the
     * customer or applying the funds to a new invoice.
     */
    const STATUS_NEW = 'new';

    /**
     * As soon as full payment (or over payment) is received, an medium or low speed invoice goes into the
     * paid status. A high speed invoice immediately goes into 'confirmed', see below.
     */
    const STATUS_PAID = 'paid';

    /**
     * The transaction speed preference of an invoice determines when an invoice is
     * confirmed.  For the high speed setting, it will be confirmed as soon as full
     * payment is received on the bitcoin network (note, the invoice will go from a status
     * of new to confirmed, bypassing the paid status).  For the medium speed setting,
     * the invoice is confirmed after the payment transaction(s) have been confirmed by
     * 1 block on the bitcoin network.  For the low speed setting, 6 blocks on the bitcoin
     * network are required.  Invoices are considered complete after 6 blocks on the
     * bitcoin network, therefore an invoice will go from a paid status directly to a
     * complete status if the transaction speed is set to low.
     */
    const STATUS_CONFIRMED = 'confirmed';

    /**
     * When an invoice is complete, it means that BTCPayServer has credited the
     * merchant’s account for the invoice.  Currently, 6 confirmation blocks on the
     * bitcoin network are required for an invoice to be complete.  Note, in the future (for
     * qualified payers), invoices may move to a complete status immediately upon
     * payment, in which case the invoice will move directly from a new status to a
     * complete status.
     */
    const STATUS_COMPLETE = 'complete';

    /**
     * An expired invoice is one where payment was not received and the 15 minute
     * payment window has elapsed.
     */
    const STATUS_EXPIRED = 'expired';

    /**
     * An invoice is considered invalid when it was paid, but payment was not confirmed
     * within 1 hour after receipt.  It is possible that some transactions on the bitcoin
     * network can take longer than 1 hour to be included in a block.  In such
     * circumstances, once payment is confirmed, BTCPayServer will make arrangements
     * with the merchant regarding the funds (which can either be credited to the
     * merchant account on another invoice, or returned to the buyer).
     */
    const STATUS_INVALID = 'invalid';

    /**
     * Code comment for each transaction speed
     */
    const TRANSACTION_SPEED_HIGH   = 'high';
    const TRANSACTION_SPEED_MEDIUM = 'medium';
    const TRANSACTION_SPEED_LOW    = 'low';

    /**
     * This is the currency code set for the price setting.  The pricing currencies
     * currently supported are USD, EUR, BTC, etc
     *
     * @return CurrencyInterface
     */
    public function getCurrency();

    /**
     * @return ItemInterface
     */
    public function getItem();

    /**
     * @return BuyerInterface
     */
    public function getBuyer();

    /**
     * default value: set in your https://btcpayserver.com/order­settings, the default value set in
     * your merchant dashboard is “medium”.
     *
     * ● “high”: An invoice is considered to be "confirmed" immediately upon
     *   receipt of payment.
     * ● “medium”: An invoice is considered to be "confirmed" after 1 block
     *   confirmation (~10 minutes).
     * ● “low”: An invoice is considered to be "confirmed" after 6 block
     *   confirmations (~1 hour).
     *
     * NOTE: Orders are posted to your Account Summary after 6 block confirmations
     * regardless of this setting.
     *
     * @return string
     */
    public function getTransactionSpeed();

    /**
     * BTCPayServer.com will send an email to this email address when the invoice status
     * changes.
     *
     * @return string
     */
    public function getNotificationEmail();

    /**
     * A URL to send status update messages to your server (this must be an https
     * URL, unencrypted http URLs or any other type of URL is not supported).
     * BTCPayServer.com will send a POST request with a JSON encoding of the invoice to
     * this URL when the invoice status changes.
     *
     * @return string
     */
    public function getNotificationUrl();

    /**
     * This is the URL for a return link that is displayed on the receipt, to return the
     * shopper back to your website after a successful purchase. This could be a page
     * specific to the order, or to their account.
     *
     * @return string
     */
    public function getRedirectUrl();

    /**
     * A passthru variable provided by the merchant and designed to be used by the
     * merchant to correlate the invoice with an order or other object in their system.
     * Maximum string length is 100 characters.
     *
     * @return array|object
     */
    public function getPosData();

    /**
     * The current invoice status. The possible states are described earlier in this
     * document.
     *
     * @return string
     */
    public function getStatus();

    /**
     * default value: true
     * ● true: Notifications will be sent on every status change.
     * ● false: Notifications are only sent when an invoice is confirmed (according
     *   to the “transactionSpeed” setting).
     *
     * @return boolean
     */
    public function isFullNotifications();

    /**
     * default value: false
     * ● true: Notifications will also be sent for expired invoices and refunds.
     * ● false: Notifications will not be sent for expired invoices and refunds
     *
     * @return boolean
     */
    public function isExtendedNotifications();

    /**
     * The unique id of the invoice assigned by BTCPayServer
     *
     * @return string
     */
    public function getId();

    /**
     * An https URL where the invoice can be viewed.
     *
     * @return string
     */
    public function getUrl();

    /**
     * The time the invoice was created in milliseconds since midnight January 1,
     * 1970. Time format is “2014­01­01T19:01:01.123Z”.
     *
     * @return DateTime
     */
    public function getInvoiceTime();

    /**
     * The time at which the invoice expires and no further payment will be accepted (in
     * milliseconds since midnight January 1, 1970). Currently, all invoices are valid for
     * 15 minutes. Time format is “2014­01­01T19:01:01.123Z”.
     *
     * @return DateTime
     */
    public function getExpirationTime();

    /**
     * The current time on the BTCPayServer system (by subtracting the current time from
     * the expiration time, the amount of time remaining for payment can be
     * determined). Time format is “2014­01­01T19:01:01.123Z”.
     *
     * @return DateTime
     */
    public function getCurrentTime();

    /**
     * Used to display your public order number to the buyer on the BTCPayServer invoice. In
     * the merchant Account Summary page, this value is used to identify the ledger
     * entry. Maximum string length is 100 characters.
     *
     * @return string
     */
    public function getOrderId();

    /**
     * Used to display an item description to the buyer. Maximum string length is 100
     * characters.
     *
     * @deprecated
     * @return string
     */
    public function getItemDesc();

    /**
     * Used to display an item SKU code or part number to the buyer. Maximum string
     * length is 100 characters.
     *
     * @deprecated
     * @return string
     */
    public function getItemCode();

    /**
     * default value: false
     * ● true: Indicates a physical item will be shipped (or picked up)
     * ● false: Indicates that nothing is to be shipped for this order
     *
     * @deprecated
     * @return boolean
     */
    public function isPhysical();

    /**
     * These fields are used for display purposes only and will be shown on the invoice
     * if provided. Maximum string length of each field is 100 characters.
     *
     * @deprecated
     * @return string
     */
    public function getBuyerName();

    /**
     * These fields are used for display purposes only and will be shown on the invoice
     * if provided. Maximum string length of each field is 100 characters.
     *
     * @deprecated
     * @return string
     */
    public function getBuyerAddress1();

    /**
     * These fields are used for display purposes only and will be shown on the invoice
     * if provided. Maximum string length of each field is 100 characters.
     *
     * @deprecated
     * @return string
     */
    public function getBuyerAddress2();

    /**
     * These fields are used for display purposes only and will be shown on the invoice
     * if provided. Maximum string length of each field is 100 characters.
     *
     * @deprecated
     * @return string
     */
    public function getBuyerCity();

    /**
     * These fields are used for display purposes only and will be shown on the invoice
     * if provided. Maximum string length of each field is 100 characters.
     *
     * @deprecated
     * @return string
     */
    public function getBuyerState();

    /**
     * These fields are used for display purposes only and will be shown on the invoice
     * if provided. Maximum string length of each field is 100 characters.
     *
     * @deprecated
     * @return string
     */
    public function getBuyerZip();

    /**
     * These fields are used for display purposes only and will be shown on the invoice
     * if provided. Maximum string length of each field is 100 characters.
     *
     * @deprecated
     * @return string
     */
    public function getBuyerCountry();

    /**
     * These fields are used for display purposes only and will be shown on the invoice
     * if provided. Maximum string length of each field is 100 characters.
     *
     * @deprecated
     * @return string
     */
    public function getBuyerEmail();

    /**
     * These fields are used for display purposes only and will be shown on the invoice
     * if provided. Maximum string length of each field is 100 characters.
     *
     * @deprecated
     * @return string
     */
    public function getBuyerPhone();

    /**
     */
    public function getExceptionStatus();


    /**
     */
    public function getToken();

    /**
     * An array containing all bitcoin addresses linked to the invoice. 
     * Only filled when doing a getInvoice using the Merchant facade.
     * The array contains
     *  [refundAddress] => Array
     *       [type] => string (e.g. "PaymentProtocol")
     *       [date] => datetime string
     *
     * @return array|object
     */
    public function getRefundAddresses();

    public function getTransactionCurrency();

    public function getPaymentSubtotals();

    /**
    * Equivalent to price for each supported transactionCurrency, excluding minerFees.
    * The key is the currency and the value is an amount indicated in the smallest possible unit
    * for each supported transactionCurrency (e.g satoshis for BTC and BCH)
    * ex: '{"BCH": 1023200, "BTC": 113100 }'
    */
    public function getPaymentTotals();

    public function getAmountPaid();

    public function getExchangeRates();

    /**
     * Get the enforced transaction currencies.
     *
     * @return array|null
     */
    public function getPaymentCurrencies();

    /**
     * Set specific invoice currencies and to enforce them on payment step.
     *
     * @param array $paymentCurrencies
     *   The currencies need to match what is supported by BTCPay Server.
     *   E.g. BTC, BTC_ONCHAIN, BTC_OFFCHAIN, LTC, XMR_MONEROLIKE etc.
     *
     * @return InvoiceInterface
     */
    public function setPaymentCurrencies($paymentCurrencies);
}

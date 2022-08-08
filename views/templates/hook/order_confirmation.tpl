{*
*  Order confirmation
*
*  @author   GoPay
*  @license  https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
*}

{if isset($error_msg)}
    <div class="alert alert-danger">
        {$error_msg|escape:'htmlall':'UTF-8'}
    </div>
{/if}
{if $order_status == Configuration::get( 'PS_OS_WS_PAYMENT' )}
    <div class="alert alert-info">
        <p>Payment accepted. Your order is being processed.</p>
        {if !empty($transaction_id)}
            <li data-paypal-transaction-id>
                {l s='GoPay transaction id:' mod='prestashopgopay'}
                {$transaction_id|escape:'htmlall':'UTF-8'}
            </li>
        {/if}
    </div>
{/if}
{if $order_status == Configuration::get( 'GOPAY_OS_WAITING' )}
    <div class="alert alert-info">
        <p>Your order was registered. However, we are still waiting for the confirmation or payment rejection.</p>
    </div>
{/if}
{if $order_status == Configuration::get( 'PS_OS_ERROR' )}
    <div class="alert alert-danger">
        <p>Unfortunately your order cannot be processed as the payment was not completed. Please attempt the payment or your purchase again.</p>
    </div>
{/if}

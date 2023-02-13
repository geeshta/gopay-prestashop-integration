{*
*  Order confirmation
*
*  @author   GoPay
*  @license  https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
*}

{if $payment_error == 'yes'}
    <div class="alert alert-danger">
        <p>{l s='Unfortunately your order cannot be processed as the payment was not completed. Please attempt the payment or your purchase again.' mod='prestashopgopay'}</p>
    </div>
{/if}

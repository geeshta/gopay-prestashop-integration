<div class="box" id="retryPayment">
  {if $is_retry }
    <form id="payment-form" method="post" action="{$action}">
      <input form="payment-form" type="hidden" id="order_id" name="order_id" value="{$order_id}">
      <button form="payment-form" type="submit" class="btn btn-primary center-block">
          {l s='retry payment' mod='prestashopgopay'}
      </button>
    </form>
  {else}
    {include file=$payment_methods_form}
    <input form="payment-form" type="hidden" id="order_id" name="order_id" value="{$order_id}">
    <button form="payment-form" type="submit" class="btn btn-primary center-block">
        {l s='retry payment' mod='prestashopgopay'}
    </button>
  {/if}
</div>

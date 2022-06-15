{*
*  Payment methods list form
*
*  @author   GoPay
*  @license  https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
*}

<div style="border-radius: 10px; padding-left: 25px; padding-bottom: 25px; margin: 15px; background-color: #f5f5f5;">
    <form action="{$action}" id="payment-form">
        <p style="padding-top: 10px;">{l s=$description}</p>
        {assign var="i" value=true}
        {foreach from=$payment_methods key=payment_method_code item=payment_method_name_image}
            <div style="border-bottom: 3px solid white; padding: 12px; position: center;
            overflow: hidden; margin-right: 15px; display: flex; flex-wrap: wrap;">
                <input name="gopay_payment_method" type="radio"
                       id="{$payment_method_code}" value="{$payment_method_code}"
                       style="margin-right: 10px;"
                        {if !empty($i) }
                            checked="checked"
                            {assign var="i" value=false}
                        {/if}/>
                <label for="{$payment_method_code}">{l s=htmlspecialchars_decode($payment_method_name_image['name'])}</label>
                <img src="{$payment_method_name_image['image']}" alt="ico" style="height: auto; width: auto; margin-left: auto;"/>
            </div>
        {/foreach}
    </form>
</div>
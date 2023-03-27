{*
*  Payment Methods
*
*  @author   GoPay
*  @license  https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
*}

<script>

  $(document).ready(function(){
    $('#payment_methods_button').click(function(e) {
      e.preventDefault();
      $('#payment_methods_form').toggle("slide");

      let payment_form = document.getElementById("payment-form");
      if (!document.getElementById("payment-form-button")) {
          payment_form.insertAdjacentHTML('beforeend', '<input id="payment-form-button" type="submit" form="payment-form" value="Process payment" style="float: right; padding: 10px; border-radius: 10px; border: none; font-size: 16px;"/>');
      }
    });
});
</script>

<div class="row">
  <div class="col-xs-12">
        <p class="payment_module">
            <a id="payment_methods_button" class="bankwire" href="#" title="{$payment_title|escape:'htmlall':'UTF-8'}">
                {$payment_title|escape:'htmlall':'UTF-8'}
            </a>
        </p>
        <div id="payment_methods_form" style="display: none;">
          {$payment_methods_form|escape:'htmlall':'UTF-8'}
        </div>
    </div>
</div>

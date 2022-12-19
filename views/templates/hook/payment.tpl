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
      payment_form.insertAdjacentHTML('beforeend', '<input type="submit" form="payment-form" value="Process payment" style="float: right; padding: 10px; border-radius: 10px; border: none; font-size: 16px;"/>');

    });
});
</script>

<div class="row">
  <div class="col-xs-12">
        <p class="payment_module">
            <a id="payment_methods_button" class="bankwire" href="#" title="{$payment_title}">
                {$payment_title}
            </a>
        </p>
        <div id="payment_methods_form" style="display: none;">
          {$payment_methods_form}
        </div>
    </div>
</div>

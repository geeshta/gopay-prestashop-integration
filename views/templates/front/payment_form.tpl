{*
*  Inline payment form
*
*  @author   GoPay
*  @license  https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
*}

<body onload="document.forms['gopay-payment'].submit()">
<form action="{$gopay_url|escape:'htmlall':'UTF-8'}" method="post" id="gopay-payment" name="gopay-payment">
    <script type="text/javascript" src="{$embed|escape:'htmlall':'UTF-8'}"></script>
</form>
{*
*  Inline payment form
*
*  @author   GoPay
*  @license  https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
*}

<body onload="document.forms['gopay-payment'].submit()">
<form action="{$gopay_url}" method="post" id="gopay-payment" name="gopay-payment">
    <script type="text/javascript" src="{$embed}"></script>
</form>
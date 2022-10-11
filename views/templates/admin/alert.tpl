{*
*  Refund success/error messages
*
*  @author   GoPay
*  @license  https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
*}
<div class="alert alert-{if $success}success{else}warning{/if}">
    <button type="button" class="close" data-dismiss="alert">×</button>
    {$message nofilter}
</div>

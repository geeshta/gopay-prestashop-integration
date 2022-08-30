{*
*  Admin info tab
*
*  @author   GoPay
*  @license  https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
*}

<div>
    <div class="prestashop-gopay-menu">
        <h1>PrestaShop GoPay gateway</h1>
    </div>

    <div class="prestashop-gopay-menu">
        <table>
            <tr>
                <th>{l s='Plugin Name' mod='prestashopgopay'}</th>
                <th>{l s='Version' mod='prestashopgopay'}</th>
                <th>{l s='Description' mod='prestashopgopay'}</th>
                <th>{l s='Author' mod='prestashopgopay'}</th>
                <th>{l s='Settings' mod='prestashopgopay'}</th>
            </tr>
            <tr>
                <td><a href="https://github.com/argo22packages/gopay-prestashop-integration">{$plugin_name}</a></td>
                <td>{$version}</td>
                <td>{$description}</td>
                <td><a href="https://www.gopay.com/">{$author}</a></td>
                <td><a href="{$settings_page}">{l s='Settings' mod='prestashopgopay'}</a></td>
            </tr>
        </table>
    </div>
</div>
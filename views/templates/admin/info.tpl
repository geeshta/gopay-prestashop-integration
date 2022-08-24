{*
*  Admin info tab
*
*  @author   GoPay
*  @license  https://www.gnu.org/licenses/gpl-2.0.html  GPLv2 or later
*}

{extends file="helpers/view/view.tpl"}

{block name="override_tpl"}
    <div>
        <div class="prestashop-gopay-menu">
            <h1>PrestaShop GoPay gateway</h1>
        </div>

        <div class="prestashop-gopay-menu">
            <table>
                <tr>
                    <th>{$plugin_name[0]}</th>
                    <th>{$version[0]}</th>
                    <th>{$description[0]}</th>
                    <th>{$author[0]}</th>
                    <th>{$settings_page[0]}</th>
                </tr>
                <tr>
                    <td><a href="https://github.com/argo22packages/gopay-prestashop-integration">{$plugin_name[1]}</a></td>
                    <td>{$version[1]}</td>
                    <td>{$description[1]}</td>
                    <td><a href="https://www.gopay.com/">{$author[1]}</a></td>
                    <td><a href="{$settings_page[1]}">{$settings_page[0]}</a></td>
                </tr>
            </table>
        </div>
    </div>
{/block}
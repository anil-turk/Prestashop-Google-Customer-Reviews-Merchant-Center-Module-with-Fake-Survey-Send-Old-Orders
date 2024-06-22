{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2015 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}



<div style="width:100%;height:120px;">





<div style="width:33.3%;text-align:center;float:right;height:100px">



</div>

</div>
<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s="SEND SURVEY FOR OLD ORDERS TO CUSTOMERS" mod='googlecusreviews'}</h3>
	<div class="row">
		<div class="control-label col-lg-3">
			<a href="?controller={$smarty.get.controller|default:''}&token={$smarty.get.token|default:''}&configure={$smarty.get.configure|default:''}&tab_module={$smarty.get.tab_module|default:''}&module_name={$smarty.get.module_name|default:''}&getdata=1&to=&from=" class="btn btn-large btn-primary"> {l s="GET DATA OF ALL VALID ORDERS" mod='googlecusreviews'}</a>
			<a href="?controller={$smarty.get.controller|default:''}&token={$smarty.get.token|default:''}&configure={$smarty.get.configure|default:''}&tab_module={$smarty.get.tab_module|default:''}&module_name={$smarty.get.module_name|default:''}&getdata=0&to=&from=" class="btn btn-large btn-warning"> {l s="RESET DATA" mod='googlecusreviews'} </a>
<div class="row"  style="margin-top: 10px;">
			<div class="col-sm-4">
				<input type="number" min="0" id="from" name="from" class="form-control" placeholder="Order ID from" value="{$smarty.get.from|default:''}">
			</div><div class="col-sm-6">
				<input type="number" min="0" id="to" name="to"  class="form-control" placeholder="Order ID To" value="{$smarty.get.to|default:''}">
				</div>
			<a onclick="orderaraligi()" class="btn btn-large btn-primary"> {l s="GET DATA BETWEEN FROM THESE VALID ORDERS" mod='googlecusreviews'}</a>
		</div>
			<div class="row"  style="margin-top: 10px;">
				<div class="col-sm-4">
					{l s="Send survey mails to your fake mail addresses (you can next or previous with including start number like this: " mod='googlecusreviews'}test&lcub;1&rcub;@yourdomain.com<br>
					{l s='Important Notice: If you want to create mails for all the addresses. In your domain mail service (Plesk, Cpanel, Yandex Business etc.) you can see the option like "If there is no mail adress forward mail to this adress"' mod='googlecusreviews'}
				</div><div class="col-sm-6">
					<input type="text" id="fakem" name="fakem" class="form-control" placeholder="fakemail&lcub;startnumber&rcub;@fakedomain.com" value="{$smarty.get.fakem|default:''}">
				</div>
				<a onclick="getfake()" class="btn btn-large btn-primary"> {l s="GET YOUR FAKE MAIL LIST" mod='googlecusreviews'}</a>
			</div>
		</div>
		<div class="col-lg-8">
            {if $datax != ""}
			<div class="alert alert-info" id="alertx"> {l s="Order not found or Javascript error." mod='googlecusreviews'}</div>
				<a onclick="changex(0)" class="btn  btn-warning"> {l s="PREVIOUS ORDER" mod='googlecusreviews'}</a> <a onclick="changex(2)" class="btn  btn-primary"> {l s="REFRESH ORDER" mod='googlecusreviews'}</a>  <a onclick="changex(1)" class="btn  btn-success"> {l s="NEXT ORDER" mod='googlecusreviews'}</a> {l s="(Or use keys for navigation, P = Previous, R = Refresh, N = Next)" mod='googlecusreviews'}
			{/if}
	<iframe id="iframe" src="" key="0"  width="100%" height="300px"></iframe>
            {if $datax != ""}
				<a onclick="changex(0)" class="btn  btn-warning"> {l s="PREVIOUS ORDER" mod='googlecusreviews'}</a> <a onclick="changex(2)" class="btn  btn-primary"> {l s="REFRESH ORDER" mod='googlecusreviews'}</a>  <a onclick="changex(1)" class="btn  btn-success"> {l s="NEXT ORDER" mod='googlecusreviews'}</a> {l s="(Or use keys for navigation, P = Previous, R = Refresh, N = Next)" mod='googlecusreviews'}
            {/if}
		</div>


		<script type="text/javascript">
            $(document).keydown(function(e) {

                if (e.which === 78) {
                    changex(1);
                }
                if (e.which === 80) {
                    changex(0);
                }
                if (e.which === 82) {
                    changex(2);
                }
            });
			function changex(state) {
                var keynow = $("#iframe").attr("key");
			    if(state === 0){
			        keynow--;
                }
                if(state === 1){
			        keynow++;
                }
                if(state === 2){

                }
                changeiframe(keynow);
            }
            function getfake(){
                var fakem = $("#fakem").val();
                var url = "?controller={$smarty.get.controller|default:''}&token={$smarty.get.token|default:''}&configure={$smarty.get.configure|default:''}&tab_module={$smarty.get.tab_module|default:''}&module_name={$smarty.get.module_name|default:''}&getdata=2&fakem="+fakem;
                $(location).attr('href',url);
            }
            function orderaraligi(){
                var tox = $("#to").val();
                var fromx = $("#from").val();
                var url = "?controller={$smarty.get.controller|default:''}&token={$smarty.get.token|default:''}&configure={$smarty.get.configure|default:''}&tab_module={$smarty.get.tab_module|default:''}&module_name={$smarty.get.module_name|default:''}&getdata=1&to="+tox+"&from="+fromx;
                $(location).attr('href',url);
            }
            {if $datax != ""}
            changeiframe(0);
            function changeiframe(key){
                var datax = {$datax};
                if(datax[key].length === 0){
                    $("#alertx").text("{l s="This was your first or last order." mod='googlecusreviews'}");
					return;
				}
                $("#iframe").attr("src","{$module_dir}confirmation.php?lang={$LANG|escape:'htmlall':'UTF-8'}&mid={$GCREVIEW_ID|intval}&code={$code}&id_order="+datax[key]['id_order']+"&email="+datax[key]['email']+"&date_add="+datax[key]['date_add']).attr("key",key);
                $("#alertx").text(key+". => Order ID: "+datax[key]['id_order']+" => Email: "+datax[key]['email']+" => Order Date: "+datax[key]['date_add']);

            }
            {/if}
		</script>
		<div class="col-lg-4">
			<script src="https://apis.google.com/js/platform.js?onload=renderBadge"
					data-keepinline="true" async defer></script> <script data-keepinline="true">window.renderBadge = function() {
                    var ratingBadgeContainer = document.createElement("div");
                    document.body.appendChild(ratingBadgeContainer);
                    window.gapi.load('ratingbadge', function() {
                        window.gapi.ratingbadge.render(
                            ratingBadgeContainer, {
                                // REQUIRED
                                "merchant_id": {$GCREVIEW_ID|intval},
                                // OPTIONAL
                                "position": "BOTTOM_RIGHT"
                            });
                    });
                }</script>
		</div>
	</div>


	<div class="row">

	</div>
</div>

<div class="panel">
	<h3><i class="icon icon-tags"></i> {l s="GOOGLE CUSTOMER REVIEWS" mod='googlecusreviews'}</h3>
	<div class="row">
		<label class="control-label col-lg-3">
			{l s="If you want to use inline somewhere you want add this code: (And select badge position to Do Not Display) (Remember you need to change code when you changed the ID)" mod='googlecusreviews'}
		</label>
		<div class="col-lg-4">
		<textarea class="form-control">
			{literal}<!-- BEGIN GCR Badge Code -->
        <script src="https://apis.google.com/js/platform.js?onload=renderBadge"
				data-keepinline="true" async defer>
        </script>
        <script data-keepinline="true">
        window.renderBadge = function() {
            var ratingBadgeContainer = document.createElement("div");
            document.body.appendChild(ratingBadgeContainer);
            window.gapi.load('ratingbadge', function() {
                window.gapi.ratingbadge.render(
                    ratingBadgeContainer, {
                        // REQUIRED
                        "merchant_id": {/literal}{$GCREVIEW_ID|intval}{literal},
                        // OPTIONAL
                        "position": "INLINE"
                    });
            });
        }
    </script>
			<!-- END GCR Badge Code --></textarea>{/literal}
		</div>
		<label class="control-label col-lg-3">
			{l s="Note: You can see your badge on bottom right of this page after you input merchant id." mod='googlecusreviews'}
		</label>
		<div class="col-lg-4">
			<a href="https://codecanyon.net/user/crystalsoftware" target="new" class="btn btn-large btn-primary"> Author: CrystalSoftware</a>
		</div>
	</div>


	<div class="row">

	</div>
</div>
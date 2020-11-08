<script type="text/javascript">
function displayPaymentEntryDiv() {
	$driverName =  document.getElementById("driver_name").value;
	switch($driverName) {
	case 'stripe':
		document.getElementById("stripeDiv").style.display = "";
		document.getElementById("paypalDiv").style.display = "none";
		break;
	case 'paypal':
		document.getElementById("paypalDiv").style.display = "";
		document.getElementById("stripeDiv").style.display = "none";
		break;
	default:
		document.getElementById("stripeDiv").style.display = "none";
		document.getElementById("paypalDiv").style.display = "none";
		break;
	}
}
</script>
{include file='Buttons_List.tpl'}
<div class="loader"></div>
<section role="dialog" tabindex="-1" class="slds-fade-in-open slds-modal_large slds-app-launcher" aria-labelledby="header43" aria-modal="true">
<div class="slds-modal__container slds-p-around_none">
	<header class="slds-modal__header slds-grid slds-grid_align-spread slds-grid_vertical-align-center">
		<h2 id="header43" class="slds-text-heading_medium">
		<div class="slds-media__figure">
			<svg aria-hidden="true" class="slds-icon slds-icon-standard-user slds-m-right_small">
				<use xlink:href="include/LD/assets/icons/utility-sprite/svg/symbols.svg#sync"></use>
			</svg>
			{$TITLE_MESSAGE}
		</div>
		</h2>
	</header>
	<div class="slds-modal__content slds-app-launcher__content slds-p-around_medium">
	{if $ISADMIN}
		<form role="form" style="margin:0 100px;" method="post">
		<input type="hidden" name="module" value="Utilities">
		<input type="hidden" name="action" value="integration">
		<input type="hidden" name="_op" value="setpaymententry">
		<div class="slds-form-element">
			<label class="slds-checkbox_toggle slds-grid">
			<span class="slds-form-element__label slds-m-bottom_none">{'_active'|@getTranslatedString:$MODULE}</span>
			<input type="checkbox" name="paymententry_isactive" aria-describedby="toggle-desc" {if $isActive}checked{/if} />
			<span id="toggle-desc" class="slds-checkbox_faux_container" aria-live="assertive">
				<span class="slds-checkbox_faux"></span>
				<span class="slds-checkbox_on">{'LBL_ENABLED'|@getTranslatedString:'Settings'}</span>
				<span class="slds-checkbox_off">{'LBL_DISABLED'|@getTranslatedString:'Settings'}</span>
			</span>
			</label>
		</div>
		<div class="slds-form-element">
			<div class="slds-form-element__control">
				<label class="slds-form-element__label" for="case_type">{'Select Payment Entry'|@getTranslatedString}</label>
				<div class="slds-select_container">
					<select class="slds-select" name ="driver_name" id="driver_name" onchange="displayPaymentEntryDiv()">
						<option value="">{'Select Payment Entry'|@getTranslatedString:$MODULE}</option>
						<option value="stripe">{'Stripe'|@getTranslatedString:$MODULE}</option>
						<option value="paypal">{'Paypal'|@getTranslatedString:$MODULE}</option>
					</select>
				</div>
			</div><br/><hr style="border-top: 1px dashed #909090;"><br/>
		<div id="stripeDiv" class="slds-form-element" style="display:none;">
			<div class="slds-form-element">
				<label class="slds-checkbox_toggle slds-grid">
				<span class="slds-form-element__label slds-m-bottom_none">{'stripe_active'|@getTranslatedString:$MODULE}</span>
				<input type="checkbox" name="stripe_isactive" aria-describedby="toggle-desc" {if $stripeisActive}checked{/if} />
				<span id="toggle-desc" class="slds-checkbox_faux_container" aria-live="assertive">
					<span class="slds-checkbox_faux"></span>
					<span class="slds-checkbox_on">{'LBL_ENABLED'|@getTranslatedString:'Settings'}</span>
					<span class="slds-checkbox_off">{'LBL_DISABLED'|@getTranslatedString:'Settings'}</span>
				</span>
				</label>
			</div>
			<div class="slds-form-element slds-m-top--small">
				<label class="slds-form-element__label" for="stripe_api_url">{'LBL_StripeAPI_URL'|@getTranslatedString:$MODULE}</label>
				<div class="slds-form-element__control">
					<input type="text" id="stripe_api_url" name="stripe_api_url" class="slds-input" value="{$stripeApiUrl}" />
				</div>
			</div>
			<div class="slds-form-element slds-m-top--small">
				<label class="slds-form-element__label" for="stripe_key">{'stripe_Key'|@getTranslatedString:$MODULE}</label>
				<div class="slds-form-element__control">
					<input type="text" id="stripe_key" name="stripe_key" class="slds-input" value="{$stripeKey}" />
				</div>
			</div>
		</div>
		<div id="paypalDiv" class="slds-form-element slds-m-top_small" style="display:none;">
			<div class="slds-form-element">
				<label class="slds-checkbox_toggle slds-grid">
				<span class="slds-form-element__label slds-m-bottom_none">{'paypal_active'|@getTranslatedString:$MODULE}</span>
				<input type="checkbox" name="paypal_isactive" aria-describedby="toggle-desc" {if $paypalisActive}checked{/if} />
				<span id="toggle-desc" class="slds-checkbox_faux_container" aria-live="assertive">
					<span class="slds-checkbox_faux"></span>
					<span class="slds-checkbox_on">{'LBL_ENABLED'|@getTranslatedString:'Settings'}</span>
					<span class="slds-checkbox_off">{'LBL_DISABLED'|@getTranslatedString:'Settings'}</span>
				</span>
				</label>
			</div>
			<div class="slds-form-element slds-m-top--small">
				<label class="slds-form-element__label" for="paypal_api_url">{'LBL_PaypalAPI_URL'|@getTranslatedString:$MODULE}</label>
				<div class="slds-form-element__control">
					<input type="text" id="paypal_api_url" name="paypal_api_url" class="slds-input" value="{$paypalApiUrl}" />
				</div>
			</div>
			<div class="slds-form-element slds-m-top--small">
				<label class="slds-form-element__label" for="paypal_client_id">{'Paypal Client ID'|@getTranslatedString:$MODULE}</label>
				<div class="slds-form-element__control">
					<input type="text" id="paypal_client_id" name="paypal_client_id" class="slds-input" value="{$paypalClientID}" />
				</div>
			</div>
			<div class="slds-form-element slds-m-top--small">
				<label class="slds-form-element__label" for="paypal_secret_key">{'Paypal Secret Key'|@getTranslatedString:$MODULE}</label>
				<div class="slds-form-element__control">
					<input type="text" id="paypal_secret_key" name="paypal_secret_key" class="slds-input" value="{$paypalsecretKey}" />
				</div>
			</div>
		</div>
		<div class="slds-m-top_large">
			<button id="saveBtn" type="submit" class="slds-button slds-button_brand">{'LBL_SAVE_BUTTON_LABEL'|@getTranslatedString:$MODULE}</button>
		</div>
		</form>
	{/if}
	</div>
</div>
</section>


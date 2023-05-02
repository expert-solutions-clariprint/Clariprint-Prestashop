<h2 class="accordion_header">{$displayName}</h2>

{foreach from=$messages item=msg}
	<div class="{$msg.class}">{$msg.txt}</div>
{/foreach}

<form method="post">
	<fieldset>
		<legend>{l s='Instant quote' mod='clariprint'}</legend>
		
		<label>{l s='Clariprint serveur base URL' mod='clariprint'}</label>
		<div class="margin-form">
			<input size="50" name="cl_server_url" value="{$cl_server_url}" id="cl_server_url" type="text">
		</div>
		
		<label for="cl_server_login">{l s='login' mod='clariprint'}</label>
		<div class="margin-form">
			<input name="cl_server_login" value="{$cl_server_login}" id="cl_server_login" type="text">
		</div>
		
		<label>{l s='password' mod='clariprint'}</label>
		<div class="margin-form">
			<input name="cl_server_password" value="" id="cl_server_url" type="text">
		</div>
		<label>{l s='API SET' mod='clariprint'}</label>
		<div class="margin-form">
			<select name="cl_api_mode">
					<option value="">{l s="direct"}</value>
					<option value="RFQ" {if $cl_api_mode == 'RFQ'}selected{/if}>{l s="merchant"}</value>
			</select>
		</div>
	</fieldset>
	<fieldset>
		<legend>{l s='Paper catalog extract account' mod='clariprint'}</legend>
		
		<label>{l s='Clariprint serveur base URL' mod='clariprint'}</label>
		<div class="margin-form">
			<input size="50" name="cl_papers_server_url" value="{$cl_papers_server_url}" id="cl_papers_server_url" type="text">
		</div>
		
		<label for="cl_papers_server_login">{l s='login' mod='clariprint'}</label>
		<div class="margin-form">
			<input name="cl_papers_server_login" value="{$cl_papers_server_login}" id="cl_papers_server_login" type="text">
		</div>
		
		<label>{l s='password' mod='clariprint'}</label>
		<div class="margin-form">
			<input name="cl_papers_server_password" value="" id="cl_papers_server_password" type="text">
		</div>
		<label>{l s='API SET' mod='clariprint'}</label>
		<div class="margin-form">
			<select name="cl_papers_api_mode">
					<option value="">{l s="direct"}</value>
					<option value="RFQ" {if $cl_api_mode == 'RFQ'}selected{/if}>{l s="merchant"}</value>
			</select>
		</div>
	</fieldset>
	<fieldset>
		<legend>{l s='Market place' mod='clariprint'}</legend>
		
		<label>{l s='Clariprint serveur base URL' mod='clariprint'}</label>
		<div class="margin-form">
			<input size="50" name="cl_mp_server_url" value="{$cl_mp_server_url}" id="cl_mp_server_url" type="text">
		</div>

		<label for="cl_mp_server_login">{l s='Login' mod='clariprint'}</label>
		<div class="margin-form">
			<input name="cl_mp_server_login" value="{$cl_mp_server_login}" id="cl_mp_server_login" type="text">
		</div>

		<label for="cl_mp_server_pass">{l s='Password' mod='clariprint'}</label>
		<div class="margin-form">
			<input name="cl_mp_server_pass" value="" id="cl_mp_server_pass" type="text">
		</div>
		
		<label for="cl_mp_server_key">{l s='key' mod='clariprint'}</label>
		<div class="margin-form">
			<input name="cl_mp_server_key" value="{$cl_mp_server_key}" id="cl_mp_server_key" type="text">
		</div>
		<label for="cl_mp_group">{l s='Customer Group' mod='clariprint'}</label>
		<div class="margin-form">
			<select name="cl_mp_group">
				<option value="">-</option>
				{foreach from=$cl_mp_groups item=grp}
				<option value="{$grp.id_group}" {if $grp.id_group == $cl_mp_group}selected{/if}>{$grp.name}</option>
				{/foreach}
			</select>
		</div>
	</fieldset>
	<fieldset>
		<legend>{l s='Graphic' mod='clariprint'}</legend>

		<label for="cl_front_ui_mode">{l s="Front UI" mod='clariprint'}</label>
		<div class="margin-form">
				{html_options name=cl_front_ui_mode options=$cl_front_ui_modes selected=$cl_front_ui_mode}
		</div>
		<label for="cl_front_ui_add">{l s="Add JS scripts" mod='clariprint'}</label>
		<div class="margin-form">
				<input type="checkbox" name="cl_front_ui_add" value="1" {if $cl_front_ui_add}checked{/if} id="cl_front_ui_add">
		</div>
		<hr/>
		<label for="cl_back_ui_mode">{l s="Backoffice UI" mod='clariprint'}</label>
		<div class="margin-form">
				{html_options name=cl_back_ui_mode options=$cl_back_ui_modes selected=$cl_back_ui_mode}
		</div>
		<label for="cl_back_ui_add">{l s="Add JS scripts" mod='clariprint'}</label>
		<div class="margin-form">
				<input type="checkbox" name="cl_back_ui_add" value="1" {if $cl_back_ui_add}checked{/if} id="cl_back_ui_add"/>
		</div>
	</fieldset>
	<fieldset>
		<legend>{l s='Options' mod='clariprint'}</legend>
		<label for="cl_category_template">{l s="Teamplate category for user project" mod='clariprint'}</label>
		<div class="margin-form">
			<select name="cl_category_template">
				{foreach from=$categories item=cat}
				<option value="{$cat.id_category}" {if $cl_category_template == $cat.id_category}selected{/if}>{$cat.name}</option>
				{/foreach}
			</select>
		<br/>{l s="When a customer save a project or add to cart, a new product is created in a customer dedicated category. To create this category we use a template category. Usualy, you need to create a -Template Category- in a -My Project- category and set the first one as your template"}
	</div>
		

		<label>{l s='Hard proof cost' mod='clariprint'}</label>
		<div class="margin-form">
			<input name="cl_hard_proof" value="{$cl_hard_proof}" id="cl_hard_proof" type="text">
		</div>
		<label>{l s='Use calculated product weight' mod='clariprint'}</label>
		<div class="margin-form">
			<input name="cl_use_calculated_weight" value="1" {if $cl_use_calculated_weight}checked{/if} id="cl_use_calculated_weight" type="checkbox"/>
		</div>
		<label>{l s='Show discount' mod='clariprint'}</label>
		<div class="margin-form">
			<input name="cl_show_discount" value="1" {if $cl_show_discount}checked{/if} id="cl_show_discount" type="checkbox"/>
		</div>
		<div class="clear"></div>
		<label>Delivery zones</label>
			<div class="margin-form">
				<textarea name="cl_deliveries" id="cl_deliveries" rows="20" cols="100">{foreach from=$cl_deliveries item= deli}{$deli->iso}:{$deli->label}:{if isset($deli->parts)}+{else}-{/if}

{/foreach}</textarea>
			</div>
		<link rel="stylesheet" href="../modules/clariprint/img/flags/flags.min.css" type="text/css" media="screen"/>
		<label>{l s='Select countries' mod='clariprint'}</label>
			<script>
				jQuery(function(){
					$('div.countries img.flag').click(function(evt){
						console.log('ici');
						var target = $(evt.target);
						var v = $('#cl_deliveries').val();
						v += '\n' + target.attr('iso') + ':'+ target.attr('alt')+ ':-';
						$('#cl_deliveries').val(v);
					});
				});
			</script>
			<div class="margin-form countries">
					{foreach from=$countries item=c}
						<label class="m3">{$c->label}<img src="../modules/clariprint/img/flags/blank.gif" class="flag  flag-{$c->iso2|lower}" alt="{$c->label}" iso="{$c->iso}" /></label>
					{/foreach}
			</div>
		<div class="clear"></div>
		<label>{l s='Default country' mod='clariprint'}</label>
			<div class="margin-form">
				<select name="cl_delivery_default">
				{foreach from=$cl_deliveries item=c}
					<option value="{$c->iso}" {if $cl_delivery_default == $c->iso}selected{/if}>{$c->label}</option>
				{/foreach}
				</select>
			</div>
		</label>
		
		
		<label for="cl_deliveries_sub_regions">{l s='Hide sub-regions' mod='clariprint'}</label>
			<div class="margin-form">
				<input type="checkbox" name="cl_deliveries_sub_regions" value="1" {if $cl_deliveries_sub_regions}checked{/if} id="cl_deliveries_sub_regions" />
			</div>
		<hr>
		<div>
			<label for="emt">{l s='Add email template' mod='clariprint'} :</label><input name="new_email_template" value="" id="emt" type="text">
			<input name="add_email_template" value="{l s='add' mod='clariprint'}" id="add_email_template" type="submit">
		</div>
	</fieldset>
	<fieldset>
		<div class="margin-form clear pspace"><input name="submitUpdate" value="{l s='Update' mod='clariprint'}" class="button" type="submit"></div>
		<div class="margin-form clear pspace"><input name="svnUpdate" value="{l s='Update Module' mod='clariprint'}" class="button" type="submit"></div>
		
		<div class="margin-form clear pspace"><input name="resettabs" value="{l s='Reset Tabs' mod='clariprint'}" class="button" type="submit"></div>
		<div class="margin-form clear pspace"><input name="resethooks" value="{l s='Reset Hooks' mod='clariprint'}" class="button" type="submit"></div>
		
		
		<div class="margin-form clear pspace"><input name="ClariprintCleanCache" value="{l s='Clean cache' mod='clariprint'}" class="button" type="submit"></div>
	</fieldset>
	</form>
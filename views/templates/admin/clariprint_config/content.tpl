<script type="text/javascript">
	function ClariprintConfigAction(elem) {
		$.ajax({
			url: $(elem).attr('href'),
			success:function(data){
				$('#ajax-ouput').html(data);
				return $.growl.notice({
					title: "Clariprint Update",
					size: "large",
					message: 'OK'});
				
			}
		});
		return false;
	}
	function ClariprintConfigSave(elem) {
		$.ajax({
			method:'POST',
			url: $(elem).attr('href'),
			data: $('#ClariprintConfigForm').serialize(),
			success:function(data){
				$('#ajax-ouput').html(data);
				return $.growl.notice({
					title: "Clariprint Update",
					size: "large",
					message: 'OK'});
				
			}
		});
		return false;
	}
</script>
<div id="ajax-ouput">
</div>
<form method="post" id="ClariprintConfigForm">

	<div class="panel">
		<div class="panel-heading">{l s='Instant quote' mod='clariprint'}</div>

		<div class="form-wrapper">
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
		</div>
	</div>
	
	<div class="panel">
		<div class="panel-heading">{l s='Paper Extract Account' mod='clariprint'}</div>

		<div class="form-wrapper">
			<label>{l s='Clariprint serveur base URL' mod='clariprint'}</label>
			<div class="margin-form">
				<input size="50" name="cl_pa_server_url" value="{$cl_pa_server_url}" id="cl_pa_server_url" type="text">
			</div>
		
			<label for="cl_server_login">{l s='login' mod='clariprint'}</label>
			<div class="margin-form">
				<input name="cl_pa_server_login" value="{$cl_pa_server_login}" id="cl_pa_server_login" type="text">
			</div>
		
			<label>{l s='password' mod='clariprint'}</label>
			<div class="margin-form">
				<input name="cl_pa_server_password" value="" id="cl_pa_server_password" type="text">
			</div>
			<label>{l s='API SET' mod='clariprint'}</label>
			<div class="margin-form">
				<select name="cl_pa_api_mode">
						<option value="">{l s="direct"}</value>
						<option value="RFQ" {if $cl_api_mode == 'RFQ'}selected{/if}>{l s="merchant"}</value>
				</select>
			</div>
		</div>
	</div>
	
	<div class="panel">
		<div class="panel-heading">{l s='Market place' mod='clariprint'}</div>
		<div class="form-wrapper">
		
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
	</div>
</div>
	<div class="panel">
		<div class="panel-heading">{l s='Graphic' mod='clariprint'}</div>
		<div class="form-wrapper">
			<label for="cl_front_ui_mode">{l s="Front UI" mod='clariprint'}</label>
			<div class="margin-form">
					{html_options name=cl_front_ui_mode options=$cl_front_ui_modes selected=$cl_front_ui_mode}
			</div>
			
			<label class="custom-control custom-checkbox">
			  <input type="checkbox" class="custom-control-input" name="cl_front_ui_add" {if $cl_front_ui_add}checked{/if} value="1" />
			  <span class="custom-control-indicator"></span>
			  <span class="custom-control-description">{l s="Add JS scripts" mod='clariprint'}</span>
			</label>
			
			
			<hr/>
			<label for="cl_back_ui_mode">{l s="Backoffice UI" mod='clariprint'}</label>
			<div class="margin-form">
					{html_options name=cl_back_ui_mode options=$cl_back_ui_modes selected=$cl_back_ui_mode}
			</div>
			<label class="custom-control custom-checkbox">
			  <input type="checkbox" class="custom-control-input" name="cl_back_ui_add" {if $cl_back_ui_add}checked{/if} value="1" />
			  <span class="custom-control-indicator"></span>
			  <span class="custom-control-description">{l s="Add JS scripts" mod='clariprint'}</span>
			</label>
		</div>
	</div>
	<div class="panel">
		<div class="panel-heading">{l s='Options' mod='clariprint'}</div>
		<div class="form-wrapper">
			<label>{l s='Hard proof cost' mod='clariprint'}</label>
			<div class="margin-form">
				<input name="cl_hard_proof" value="{$cl_hard_proof}" id="cl_hard_proof" type="text">
			</div>
			
			<label class="custom-control custom-checkbox">
			  <input type="checkbox" class="custom-control-input" name="cl_use_calculated_weight" {if $cl_use_calculated_weight}checked{/if} value="1" />
			  <span class="custom-control-indicator"></span>
			  <span class="custom-control-description">{l s="Use calculated product weight" mod='clariprint'}</span>
			</label>
			<br>
			<label class="custom-control custom-checkbox">
			  <input type="checkbox" class="custom-control-input" name="cl_show_discount" {if $cl_show_discount}checked{/if} value="1" />
			  <span class="custom-control-indicator"></span>
			  <span class="custom-control-description">{l s="Show discount" mod='clariprint'}</span>
			</label>
		</div>
	</div>
	<div class="panel">
		<div class="panel-heading">{l s='Delivery' mod='clariprint'}</div>
		<div class="form-wrapper">
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

		<label class="custom-control custom-checkbox">
		  <input type="checkbox" class="custom-control-input" name="cl_deliveries_sub_regions" {if $cl_deliveries_sub_regions}checked{/if} />
		  <span class="custom-control-indicator"></span>
		  <span class="custom-control-description">{l s='Hide sub-regions' mod='clariprint'}</span>
		</label>
					
		
				
	</div>
	</div>
	
	<div class="panel">
		<div class="panel-heading">{l s='Templates' mod='clariprint'}</div>
		<div class="form-wrapper">
			<script type="text/javascript">
				$(function(){
					$('#add_email_template').click(function(evt){
						evt.stopPropagation();
						evt.preventDefault();
						$.ajax({
							url:'{$link->getAdminLink('AdminClariprintConfig')}',
							data: {
								ajax:1,
								action:'AddTemplate',
								add_email_template: $('#emt').val()
								},
							success: function(data) {
								return $.growl.notice({
									title: "Clariprint template",
									size: "large",
									message: "{l s='template created' mod='clariprint'}"
								});
							}
						});
					});
				});
			</script>
			<div>
				<label for="emt">{l s='Add email template' mod='clariprint'} :</label><input name="new_email_template" value="" id="emt" type="text">
				<button type="button" class="btn btn-primary"s  id="add_email_template"s>{l s='add' mod='clariprint'}</button>
				<div class="" id="emt_msg"></div>
			</div>
		</div>
	</div>
</form>
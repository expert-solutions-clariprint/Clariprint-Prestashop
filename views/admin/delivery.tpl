
<div class="card expandable">
	<div class="card-header">{l s='Deliveries' mod='clariprint'}</div>
<script type="text/javascript">
	jQuery(function() {
		Clariprint_lang_Delete = "{l s= 'Delete'}";
		Clariprint.setupDeliveries();

		var btn = $('a.add_admin_delivery');
		if (Clariprint.ui == 'jqueryui') btn.button();
		btn.click(function(evt){
			var btn = $(evt.target);
			evt.preventDefault();
			evt.stopPropagation();
			$.ajax({
				url: '{$link->getAdminLink('AdminClariprintProducts',true)}',
				data:{
					ajax: 1,
					action: 'DeliveryItem',
					product_key: btn.closest("table.deliveries").attr("productkey")
				}
			}).done(function(data){
				var item = $(data);
				$('table.deliveries tbody').append(item);
				if (Clariprint.ui == 'jqueryui') item.find('a.delete').button();
				item.find('a.delete').on('click',function(evt){
					$(evt.target).closest('tr').remove();
				});
			});
			
		});});
	
</script>
<div id="deliveries" class="deliveries card-block" productkey="{$product_key}">
	<div class="field">
		<label for="{$product_key}_options_delivery">{l s='Mode :' mod='clariprint'}</label>
		<select name="{$product_key}[options][delivery]" id="{$product_key}_options_delivery">
			<option value="hidden" {if $product->options->delivery == 'hidden'}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
			<option value="single" {if $product->options->delivery == 'single'}selected{/if}>{l s='Simple' mod='clariprint'}</option>
			<option value="multiple" {if $product->options->delivery == 'multiple'}selected{/if}>{l s='Multiple' mod='clariprint'}</option>
			<option value="list" {if $product->options->delivery == 'list'}selected{/if}>{l s='List' mod='clariprint'}</option>
		</select>
	</div>
	<div class="clear"></div>
	<table class="deliveries table" style="width: 100%; margin-bottom:10px;"  productkey="{$product_key}">
		<thead>
			<tr>
				<th>{l s='Active' mod='clariprint'}</th>
				<th>{l s='Destination (postal code)' mod='clariprint'}</th>
				<th>{l s='Address' mod='clariprint'}</th>
				<th>{l s='Quantity' mod='clariprint'}</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=(array)$product->deliveries item=del key=k}
			{$delivery_nodelete=false}
			{$delivery_mode='multiple'}
			{include file='../front/delivery_item.tpl'}
			{/foreach}
		</tbody>
		<tfoot>
			<tr>
				<th colspan="5">
					<a productkey="{$product_key}" class="add_admin_delivery {if $ui_mode == 'bootstrap'}btn btn-primary{else}ui-icon-plus ui-button ui-corner-all{/if}" href="#">{l s='Add' mod='clariprint'}</a>
				</th>
			</tr>
		</tfoot>
	</table>
	
	<div>
		<label id="">{l s='List' mod='clariprint'}</label>
		<textarea name="{$product_key}[options][deliveries]" rows="8" cols="40"></textarea>
	</div>
	
	<div>
		<label for="user_adress">{l s="Use customer address if available" mod='clariprint'}</label>
		<input type="checkbox" name="{$product_key}[options][delivery_address]" value="1" {if $product->options->delivery_address}checked="1"{/if} id="user_adress"/>
	</div>
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][delivery_info_cms]" value="{$product->options->delivery_info_cms}"/>
	
</div>
</div>

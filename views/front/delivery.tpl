{if $product->options->delivery != 'hidden'}
<div class="card clariprint-delivery">
	<h3 class="accordion_header card-header">{l s='Deliveries' mod='clariprint'} :</h3>

	<div id="deliveries" class="deliveries card-block" >
	
	<table class="deliveries table table-bordered"  productkey="{$product_key}" {if $product->options->delivery == 'multiple'}multiple_delivery="true"{/if}>
		<thead>
			<tr>
				<td>{l s='Deliver to' mod='clariprint'}</td>
				{if $product->options->delivery != 'list'}
				<td>{l s='Destination (postal code)' mod='clariprint'}</td>
				<td>{l s='Address' mod='clariprint'}</td>
				{if $product->options->delivery == 'multiple'}
				<td>{l s='Quantity' mod='clariprint'}</td>
				{else}<td></td>
				{/if}
				<td></td>
				{/if}
			</tr>
		</thead>
		<tbody>
			{if $product->options->delivery_address & count($customer_addresses) > 0}
			{foreach from=$customer_addresses item=del}
				{assign var=k value="ca{$del.id_address}"}
			<tr class='delivery'>
				<td>
					<input type="checkbox" class="active" readonly="true" name="{$product_key}[deliveries][{$k}][active]" value="1"
					{if $product->deliveries->$k->active == 1}checked{/if}
					/>
				</td>
				<td>
					<input type="text" class="number" name="{$product_key}[deliveries][{$k}][iso]" value="{deliveryPost2Zone country=$del.country_iso postal=$del.postcode}" readonly="true" />
				</td>
				<td>
					<textarea rows="4" readonly="true" name="{$product_key}[deliveries][{$k}][address]">{$del.address1}
{$del.address2}
{$del.postcode} {$del.city}
{$del.country_iso}</textarea>
				</td>
				<td>
					<input name="{$product_key}[deliveries][{$k}][quantity]" placeholder="{l s="auto" mod="clariprint"|escape:html}" size="6">{$del->quantity}</textarea>
				</td>
			</tr>
			{/foreach}
			{else}
			{assign var=delivery_nodelete value=($product->options->delivery == 'simple' || $product->options->delivery == 'list')}
			{assign var=delivery_mode value=$product->options->delivery}
			{foreach from=(array)$product->deliveries item=del key=k}
				{include file='./delivery_item.tpl'}
			{/foreach}
			{/if}
		</tbody>
		{if $product->options->delivery == 'multiple22'}
		<tfoot>
			<tr>
				<td>
					<select class="cl_wrapping_add_zone">
						{foreach from=$deliveries item=country}
						<optgroup label="{$country->label}">
							{foreach from=$country->parts item=dep}
							<option value="{$dep->iso}">{$dep->iso} - {$dep->label}</option>
							{/foreach}
						</optgroup>
						{/foreach}
					</select>
				</td>
				<td>
					<textarea class="cl_wrapping_add_address" rows="8" cols="40"></textarea>
				</td>
				<td>
					<input type="text"
						placeholder="auto"
						class="cl_wrapping_add_quantity" />
				</td>
				<td>
					<a class="add ui-icon-plus" href="#">{l s='Add' mod='clariprint'}</a>
				</td>
			</tr>
		</tfoot>
		{/if}
		{if $product->options->delivery == 'multiple'}
		<tfoot>
			<tr>
				<td colspan="4">
					<a class="add_delivery btn btn-primary" role="clariprint-add-delivery" productkey="{$product_key}"><span class="glyphicon glyphicon-plus"></span>{l s='Add' mod='clariprint'}</a>
				</td>
			</tr>
		</tfoot>
		{/if}
	</table>
	{if $product->options->delivery_info_cms}
		{displayCMS cms=$product->options->delivery_info_cms}
	{else}
		{displayCMS cms='product-delivery'}
	{/if}
</div>
</div>
{else}
	{if $product->deliveries} 
		{assign var=deliveries value=(array)$product->deliveries}
		{foreach from=$deliveries key=k item=delpt}
		<input type="hidden" name="{$product_key}[deliveries][{$k}][iso]" value="{$delpt->iso}"/>
		<input type="hidden" name="{$product_key}[deliveries][{$k}][address]" value="{$delpt->address}"/>
		<input type="hidden" name="{$product_key}[deliveries][{$k}][quantity]" value="{$delpt->quantity}"/>
		{/foreach}
	{/if}
{/if}
{if $product->options->delivery_address}
	<div id="deliveries" class="deliveries" >
		{foreach from=$address item=ad}
			
		{/foreach}
	</div>
{/if}


<tr class='delivery'>
	{if $delivery_mode == 'multiple'}
	<td>
		<input type="checkbox" readonly="true" name="{$product_key}[deliveries][{$k}][active]" value="1" checked/>
	</td>
	{else}
		<input type="hidden" readonly="true" name="{$product_key}[deliveries][{$k}][active]" value="1" checked/>
	{/if}
	<td>
		<div role="delivery_selector">
		<select class="cl_wrapping_add_zone_country" role="country">
				{foreach from=$deliveries item=country}
					<option value="{$country->iso}" {if isset($del)}{if $del->iso == $country->iso}selected="true"{/if}{elseif $delivery_default == $country->iso}selected{/if}>{$country->label}</option>
				{/foreach}
		</select>
		<select class="cl_wrapping_add_zone" name="{$product_key}[deliveries][{$k}][iso]"  role="zone">
			{foreach from=$deliveries item=country}
				{if count($country->parts) > 0} 
					{foreach from=$country->parts item=dep}
						<option value="{$dep->iso}"
							country="{$country->iso}"
								 {if isset($del)}{if $del->iso == $dep->iso}selected="true"{/if}{elseif $delivery_default == $dep->iso}selected{/if}>{$dep->label}</option>
					{/foreach}
				{else}
					<option country="{$country->iso}" value="{$country->iso}" {if isset($del)}{if $del->iso == $country->iso}selected="true"{/if}{elseif $delivery_default == $country->iso}selected{/if}>{$country->label}</option>
				{/if}
			{/foreach}
		</select>
		</div>

	</td>
	{if $delivery_mode == 'multiple'}
	<td>
		<textarea rows="4" name="{$product_key}[deliveries][{$k}][address]">{if isset($del)}{$del->address}{/if}</textarea></td>
	<td>
		<input type="text" class="number" name="{$product_key}[deliveries][{$k}][quantity]" placeholder="{l s="auto" mod="clariprint"|escape:html}" size="6" value="{if isset($del)}{$del->quanity}{/if}"/>
	</td>
	<td>{if !$delivery_nodelete}
		{if true}
		<a class="delete btn" title="{l s='Delete' mod='clariprint'}">
			<i class="icon-trash"></i>
			{l s='remove' mod='clariprint'}
		</a>
		{else}
		<a class="delete" title="{l s='Delete' mod='clariprint'}">
			<span class="ui-icon ui-icon-trash"></span>
		</a>
		{/if}
		{/if}
	</td>
	{/if}
</tr>

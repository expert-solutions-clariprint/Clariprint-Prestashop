{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*
* ADMIN 
*}
<div class="card expandable">
	<div class="card-header">{l s='Discount' mod='clariprint'}</div>
	<div id="discount" class="discount card-block">
	{l s='Groupe' mod='clariprint'}<br>
	<select name="{$product_key}[discounts_group]">
		<option value=''>-</option>
		{foreach $groups as $grp}
		<option value="{$grp['id_group']}" {if $product->discounts_group == $grp['id_group']}selected{/if}>{$grp['name']}</option>
		{/foreach}
	</select>
	<table class="alternate">
		<thead>
			<tr>
				<td>{l s='Quantity' mod='clariprint'}</td>
				<td>{l s='discount' mod='clariprint'}</td>
				<td>{l s='fixed' mod='clariprint'}</td>
			</tr>
		</thead>
		<tbody>
			{for $m=0 to 10}
			<tr>
				<td><input name="{$product_key}[discounts][{$m}][quantity]" placeholder="{l s='discount ' mod='clariprint'} # {$m}" value="{$product->discounts[$m]->quantity}"/></td>
				<td><input name="{$product_key}[discounts][{$m}][value]" placeholder="" class="CLFloat" value="{$product->discounts[$m]->value}"/></td>
				<td><input name="{$product_key}[discounts][{$m}][fixed]" placeholder="" class="CLFloat" value="{$product->discounts[$m]->fixed}"/></td>
			</tr>
			{/for}
		</tbody>
	</table>
	</div>
</div>
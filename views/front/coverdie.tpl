{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary	
*}
{assign var=coverfolds value=array(1,3,40,5)}
{if $product->options->coverdie_mode != 'hidden'}
<div class="card clariprint-coverdie">
	<h3 class="accordion_header card-header">{l s='Book cover form' mod='clariprint'} :</h3>
	<div class="coverdie card-block">
	<input type="radio" name="{$product_key}[coverdie]"
		style="display: none"
		{if $product->coverdie == 'none'}checked{/if}
		role="clariprint-no-cover"
		value="none" />
	<table>
		<thead>
			<tr>
				{if in_array_silent("F_1",$product->options->coverdie)}
				<td>{l s='Standard' mod='clariprint'}</th>
				{/if}
				{if in_array_silent("F_3",$product->options->coverdie)}
				<td>{l s='With one flap' mod='clariprint'}</th>
				{/if}
				{if in_array_silent("F_40",$product->options->coverdie)}
				<td>{l s='With two flap' mod='clariprint'}</th>
				{/if}
				{if in_array_silent("F_5",$product->options->coverdie)}
				<td>{l s='With one rolled flap' mod='clariprint'}</th>
				{/if}
			</tr>
		</thead>
		<tbody>
			<tr>
				{foreach from=$coverfolds item=fold}
					{assign var=fold_key value="F_$fold"}
					{if in_array_silent($fold_key,$product->options->coverdie)}
				<td><img src="/modules/clariprint/img/folds/{$fold}.png"/></td>
					{/if}
				{/foreach}
			</tr>
			<tr>
				{foreach from=$coverfolds item=fold}
					{assign var=fold_key value="F_$fold"}
					{if in_array_silent($fold_key,$product->options->coverdie)}
					<td><input type="radio" name="{$product_key}[coverdie]" value="F_{$fold}" {if $product->coverdie == "F_$fold"}checked{/if}/></td>
					{/if}
				{/foreach}
			</tr>
		</tbody>
	</table>
	{if $product->options->coverdie_info_cms}
		{displayCMS cms=$product->options->coverdie_info_cms}
	{else}
		{displayCMS cms='product-coverdie'}
	{/if}
	
</div>
</div>
{else}
	<input type="hidden" name="some_name" value="{$product_key}[coverdie]" />
{/if}

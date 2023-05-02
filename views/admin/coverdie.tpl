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
<div class="card expandable">
	<div class="card-header">{l s='Book cover form' mod='clariprint'}</div>
	<div class="card-block coverdie">
	<div class="field">
		<label for="{$product_key}_options_coverdie_mode">{l s='Mode :' mod='clariprint'}</label>
		<select name="{$product_key}[options][coverdie_mode]" id="{$product_key}_options_coverdie_mode">
			<option value="hidden" {if $product->options->coverdie_mode == 'hidden'}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
			<option value="show" {if $product->options->coverdie_mode == 'show'}selected{/if}>{l s='Show' mod='clariprint'}</option>
		</select>
	</div>
	
	
	<table>
		<thead>
		<tbody>
			<tr>
				<th rowspan="4">{l s='Classic' mod='clariprint'}</th>
				<th>{l s='Name' mod='clariprint'}</th>
				<td>{l s='None' mod='clariprint'}</th>
				<td>{l s='Standard' mod='clariprint'}</th>
				<td>{l s='With one flap' mod='clariprint'}</th>
				<td>{l s='With two flap' mod='clariprint'}</th>
				<td>{l s='With one rolled flap' mod='clariprint'}</th>
			</tr>
			<tr>
				<th>{l s='Icon' mod='clariprint'}</th>
				<td><img src="/modules/clariprint/img/folds/none.png"/></td>
				{foreach from=$coverfolds item=fold}
				<td><img src="/modules/clariprint/img/folds/{$fold}.png"/></td>
				{/foreach}
			</tr>
			<tr>
				<th>{l s='Default' mod='clariprint'}</th>
				<td><input type="radio" name="{$product_key}[coverdie]" value="none" {if $product->coverdie == 'none'}checked{/if}/></td>
				{foreach from=$coverfolds item=fold}
					<td><input type="radio" name="{$product_key}[coverdie]" value="F_{$fold}" {if $product->coverdie == "F_$fold"}checked{/if}/></td>
				{/foreach}
			</tr>
			<tr>
				<th>{l s='Available' mod='clariprint'}</th>
				<td><input type="checkbox" name="{$product_key}[options][coverdie][]" value="none" {if in_array_silent('none',$product->options->coverdie)}checked{/if}/></td>
				{foreach from=$coverfolds item=fold}
					{assign var=fold_key value="F_$fold"}
					<td><input type="checkbox" name="{$product_key}[options][coverdie][]" value="{$fold_key}" {if in_array_silent($fold_key,$product->options->coverdie)}checked{/if}/></td>
				{/foreach}
			</tr>
		</tbody>
	</table>
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][coverdie_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->coverdie_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][coverdie_info_cms]" value="{$product->options->coverdie_info_cms}"/>
	
</div>
</div>

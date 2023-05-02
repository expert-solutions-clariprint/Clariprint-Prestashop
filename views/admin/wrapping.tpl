{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
<div class="card expandable">
	<div class="card-header">{if isset($clariprint_card_label)}{$clariprint_card_label}{else}{l s='Wrapping' mod='clariprint'}{/if} :</div>
<div id="wrapping" class="wrapping card-block">

	<select name="{$product_key}[options][wrapping_mode]">
		<option value="0" {if $product->options->wrapping_mode == 0}selected{/if}>{l s='Hide' mod='clariprint'}</option>
		<option value="1" {if $product->options->wrapping_mode == 1}selected{/if}>{l s='Show' mod='clariprint'}</option>
	</select>
	<div class="clear">&nbsp;</div>

	<table class="">
		<thead>
			<tr>
				<td>#</td>
				<td>{l s='kind' m='clariprint'}</td>
				<td>{l s='quantity' m='clariprint'}</td>
				<td>{l s='etiquette' m='clariprint'}</td>
				<td>{l s='quantity' m='clariprint'}</td>
			</tr>
		</thead>
		<tbody>
			{for $i=0 to 2}
				{assign var=wrapn value="wrapping_$i"}
			<tr>
				<td><input type="checkbox" name="{$product_key}[options][wrapping_{$i}][show]" value="1" {if $product->options->$wrapn->show}checked{/if}/></td>
				<td>
					<select name="{$product_key}[wrapping_{$i}][method]">
						<option value="">{l s='none' m='clariprint'}</option>
						{foreach $wrapping as $wrap => $txt }
						<option value="{$wrap}" {if $product->$wrapn->method == $wrap}selected{/if}>{$txt}</option>
						{/foreach}
					</select>
				</td>
				<td>
					<input type="text" name="{$product_key}[wrapping_{$i}][quantity]" value="{$product->$wrapn->quantity}" />
				</td>
				<td>
							<input type="checkbox" class="form-control" name="{$product_key}[wrapping_{$i}][etiket]" value="1" {if $product->$wrapn->etiket}checked{/if} placeholder="{l s='auto' mod='clariprint'}"/>
				</td>
				<td>
					<select name="{$product_key}[options][wrapping_{$i}][available][]" multiple size="5">
						{foreach $wrapping as $wrap => $txt }
						<option value="{$wrap}" {if in_array_silent($wrap,$product->options->$wrapn->available)}selected{/if}>{$txt}</option>
						{/foreach}
					</select>
				</td>
			</tr>
			{/for}
		</tbody>
	</table>
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][wrapping_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->wrapping_info}</textarea>
	<div class="clear"></div>
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][wrapping_info_cms]" value="{$product->options->wrapping_info_cms}"/>
</div>
</div>
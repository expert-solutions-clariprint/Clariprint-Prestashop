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
	<div class="card-header">{if isset($clariprint_card_label)}{$clariprint_card_label}{else}{l s='Extra models' mod='clariprint'}{/if}</div>
	<div id="models" class="models card-block">

	<select name="{$product_key}[options][models_mode]">
		<option value="0" {if $product->options->models_mode == 0}selected{/if}>{l s='Hide' mod='clariprint'}</option>
		<option value="1" {if $product->options->models_mode == 1}selected{/if}>{l s='Show' mod='clariprint'}</option>
	</select>
	<div class="input-group">
		<span class="input-group-addon">{l s='Number of models'} : </span>
		<input class="CLInt model_quantity form-control text-xs-right"  name="{$product_key}[models_qt]"
					value="{$product->models_qt}"/>
			<span class="input-group-addon">{l s='models' mod='clariprint'}</span>
	</div>
	<div class="input-group">
	
	<label>{l s="User can choose different quantities ?"}
	<select name="{$product_key}[options][models_mode_hide_qt]">
		<option value="0" {if $product->options->models_mode_hide_qt == 0}selected{/if}>{l s='Show' mod='clariprint'}</option>
		<option value="1" {if $product->options->models_mode_hide_qt == 1}selected{/if}>{l s='Hide' mod='clariprint'}</option>
	</select>
	</div>
	<div class="form-group">
		<label>{l s="Model quantities list"}</label>
		<textarea class="form-control" name="{$product_key}[options][models_quantites]">{$product->options->models_quantites}</textarea>
	</div>


	<label>{l s="Show models modes ?"}
	<select name="{$product_key}[options][models_hide_qt_mode]">
		<option value="0" {if $product->options->models_hide_qt_mode == 0}selected{/if}>{l s='Show' mod='clariprint'}</option>
		<option value="1" {if $product->options->models_hide_qt_mode == 1}selected{/if}>{l s='Hide' mod='clariprint'}</option>
	</select>

	<div class="input-group">
	
	<label>{l s="Default mode ?"}
	<select name="{$product_key}[models_qt_mode]">
		<option value="same_qt" {if $product->models_qt_mode == "same_qt"}selected{/if}>{l s='Same quantities for each models' mod='clariprint'}</option>
		<option value="same_qt_assembled" {if $product->models_qt_mode == "same_qt_assembled"}selected{/if}>{l s='Same quantities for each models and assembled' mod='clariprint'}</option>
		<option value="different_qt" {if $product->models_qt_mode == "same_qt"}selected{/if}>{l s='Different quantities for each models' mod='clariprint'}</option>
	</select>
	</div>


	<div class="clear">&nbsp;</div>
	
	<table class="alternate">
		<thead>
			<tr>
				<td>{l s='Reference' mod='clariprint'}</td>
				<td>{l s='Quantity' mod='clariprint'}</td>
			</tr>
		</thead>
		<tbody>
			{for $m=0 to 9}
			<tr>
				<td><input name="{$product_key}[models][{$m}][reference]" placeholder="{l s='model ' mod='clariprint'} # {$m}" value="{$product->models[$m]->reference}"/></td>
				<td><input name="{$product_key}[models][{$m}][quantity]" placeholder="" class="CLInt model_quantity" value="{$product->models[$m]->quantity}"/></td>
			</tr>
			{/for}
		</tbody>
		<tfoot>
			<tr>
				<td>{l s='total printed' mod='clariprint'}</td>
				<td class='total_printed number'></td>
			</tr>
		</tfoot>
	</table>
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][models_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->models_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
		<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
		<input type="text" class="form-control"  name="{$product_key}[options][models_info_cms]" value="{$product->options->models_info_cms}"/>
</div>
</div>
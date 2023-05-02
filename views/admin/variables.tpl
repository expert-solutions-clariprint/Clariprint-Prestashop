
<div class="card expandable">
	<div class="card-header">{if isset($clariprint_card_label)}{$clariprint_card_label}{else}{l s='Variables' mod='clariprint'}{/if}</div>
	<div class="card-block variables">
		<label for="{$product_key}[options][show_variables]">{l s='Show' mod='clariprint'}</label><input type="checkbox" name="{$product_key}[options][show_variables]" value="1" {if $product->options->show_variables}checked{/if} id="{$product_key}[options][show_variables]"/>


		<h4>{l s='Recto' mod='clariprint'}
		<div class="form-group">
			<label for="front_black_vars">{l s='back variables' mod="clariprint"}</label>
			<input type="number" class="form-control" id="front_black_vars" name="{$product_key}[front_black_vars]" value="{$product->front_black_vars}" />
		</div>	
		<div class="form-group">
			<label for="front_4colors_vars">{l s='4-color variables' mod="clariprint"}</label>
			<input type="number" class="form-control" id="front_4colors_vars" name="{$product_key}[front_4colors_vars]" value="{$product->front_4colors_vars}" placholder="" />
		</div>	
		<hr>
		<h4>{l s='Verso' mod='clariprint'}
		<div class="form-group">
			<label for="back_black_vars">{l s='back variables' mod="clariprint"}</label>
			<input type="number" class="form-control" id="back_black_vars" name="{$product_key}[back_black_vars]" value="{$product->back_black_vars}" />
		</div>	
		<div class="form-group">
			<label for="back_4colors_vars">{l s='4-color variables' mod="clariprint"}</label>
			<input type="number" class="form-control" id="back_4colors_vars" name="{$product_key}[back_4colors_vars]" value="{$product->back_4colors_vars}" />
		</div>		
		<hr>
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][variables_info]" class="clariprint_rte autoload_rte" rows="8" cols="40">{$product->options->variables_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
		<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
		<input type="text" class="form-control"  name="{$product_key}[options][variables_info_cms]" value="{$product->options->variables_info_cms}"/>
		
</div>
</div>
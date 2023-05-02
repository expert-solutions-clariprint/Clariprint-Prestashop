<div class="card expandable">

	<div class="card-header">{l s='Quantities and models' mod='clariprint'}</div>

	<div class="card-block">

		<div class="form-group">
			<label class="form-control-label" for="{$product_key}_options_quantity">{l s='Mode :' mod='clariprint'}</label>
			<select class="form-control"  name="{$product_key}[options][quantity]" id="{$product_key}_options_quantity">
				<option value="hidden" {if $product->options->quantity == 'hidden'}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
				<option value="combi" {if $product->options->quantity == 'combi'}selected{/if}>{l s='Combination' mod='clariprint'}</option>
				<option value="range" {if $product->options->quantity == 'range'}selected{/if}>{l s='Range' mod='clariprint'}</option>
				<option value="list" {if $product->options->quantity == 'list'}selected{/if}>{l s='List' mod='clariprint'}</option>
				<option value="free" {if $product->options->quantity == 'free'}selected{/if}>{l s='Free' mod='clariprint'}</option>
			</select>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="form-control-label" for="{$product_key}[quantity]">{l s='Default :' }</label>
					<input type="text" name="{$product_key}[quantity]" class="CLInt form-control" value="{$product->quantity}" id="clariprint_quantity"/>
				</div>
			</div>
			<div class="col-md-3">

				<div class="form-group">
					<label for="{$product_key}[options][quantity_from]">{l s='From' mod='clariprint'}</label> : 
					<input type="text" name="{$product_key}[options][quantity_from]" value="{$product->options->quantity_from}" id="{$product_key}[options][quantity_from]" class="CLInt form-control"/>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="form-control-label" for="{$product_key}[options][quantity_to]">{l s='To' mod='clariprint'}</label> : 
					<input type="text" name="{$product_key}[options][quantity_to]" value="{$product->options->quantity_to}" id="{$product_key}[options][quantity_to]" class="CLInt form-control"/>
				</div>
			</div>
			<div class="col-md-3">
		

				<div class="form-group">
					<label class="form-control-label" for="{$product_key}[options][quantity_step]">{l s='Step' mod='clariprint'}</label> : 
					<input type="text" name="{$product_key}[options][quantity_step]" value="{$product->options->quantity_step}" id="{$product_key}[options][quantity_step]" class="CLInt form-control"/>
				</div>
			</div>
		</div>
		
		<div class="form-group">
			<label class="form-control-label" for="{$product_key}[options][quantities]">{l s='List' mod='clariprint'}</label> : 
			<textarea class="form-control" name="{$product_key}[options][quantities]" rows="8" cols="5" id="{$product_key}[options][quantities]">{$product->options->quantities}</textarea>
		</div>
		<div class="clear"></div>
		
		<div class="form-group">
			<label class="form-control-label">{l s='User doc' mod='clariprint'}</label>
			<textarea name="{$product_key}[options][quantities_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->quantities_info|htmlentitiesUTF8}</textarea>
		</div>
		<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
		<input type="text" class="form-control"  name="{$product_key}[options][quantities_info_cms]" value="{$product->options->quantities_info_cms}"/>
		
	</div>
</div>
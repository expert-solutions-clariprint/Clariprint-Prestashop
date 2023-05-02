{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
{if $product->options->size != 'hidden' & $product->options->size != 'fixed' & $product->options->size != 'override'}
	<div class="card clariprint-dimensions">
		<h3 class="card-header">{if isset($clariprint_card_label)}{$clariprint_card_label}{else}{l s='Finished dimensions' mod='clariprint'}{/if} :</h3>
		<div id="sizes" class="sizes card-block">
		{if $product->options->size == 'free'}
			{if $product->kind == 'leaflet' || $product->kind == 'book'  || $product->kind == 'section_leaflet'}
			{* LEAFLET *}
			<script type="text/javascript">
				function clupdatesize2(k,val) {
					var x = val.split(':');
					var dims = x[0].split('x');
					$('#'+ k + '_width').val(dims[0]);
					$('#'+ k + '_height').val(dims[1]);
					return false;
				}
			</script>
			<div class="row">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon"  id="{$product_key}_width_txt">{l s='Width' mod='clariprint'} : </span>
						<input type="text"
							class="form-control CLFloat ClWidth text-xs-right"
							name="{$product_key}[width]"
							id="{$product_key}_width"
							value="{$product->width|default:'21'}" 
							placeholder="{l s='Width in cm' mod='clariprint'}"
							aria-describedby="{$product_key}_width_txt">
						<span class="input-group-addon">{l s='cm' mod='clariprint'}</span>
					</div>
				</div>
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon"  id="{$product_key}_height_txt">{l s='Height' mod='clariprint'} : </span>
						<input type="text"
							class="form-control CLFloat ClHeight text-xs-right"
							name="{$product_key}[height]" id="{$product_key}_height"
							value="{$product->height|default:'29.7'}" 
							placeholder="{l s='Height in cm' mod='clariprint'}"
							aria-describedby="{$product_key}_height_txt">
						<span class="input-group-addon">{l s='cm' mod='clariprint'}</span>
					</div>
				</div>
			</div>
			<div class="clear"></div>
			{if $product->options->sizes}
			<div class="row-fluid">
				<div class="span8">{l s='Proposed values' mod='clariprint'} :
					{assign var=sizes value="\n"|explode:$product->options->sizes} 
					{foreach from=$sizes item=x}
						{if $x != ''}
						<a class="button" onclick="clupdatesize2('{$product_key}','{$x|trim}')">{$x}</a>
						{/if}
					{/foreach}
				</div>
			</div>
			{/if}
			{* FIN LEAFLET *}
		{else}
			{* AUTRE - FORMAT FERME ET OUVERT *}

			<script type="text/javascript">
				function clupdatesizeopen(k,val) {
					var x = val.split(':');
					var dims = x[0].split('x');
					$('#'+ k + '_width').val(dims[0]);
					$('#'+ k + '_height').val(dims[1]);
					dims = x[1].split('x');
					$('#'+ k + '_openwidth').val(dims[0]);
					$('#'+ k + '_openheight').val(dims[1]);
					return false;
				}
			</script>
			<div class="row">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon">{l s='Closed width' mod='clariprint'}</span>
						<input class="CLFloat ClWidth form-control text-xs-right" name="{$product_key}[width]" id="{$product_key}_width" value="{$product->width|default:'21'}" type="text" />
						<span class="input-group-addon">{l s='cm' mod='clariprint'}</span>
					</div>
				</div>
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon">{l s='Height' mod='clariprint'}</span>
						<input class="CLFloat ClHeight form-control text-xs-right"  name="{$product_key}[height]" id="{$product_key}_height" value="{$product->height|default:'29.7'}" type="text" />
						<span class="input-group-addon">{l s='cm' mod='clariprint'}</span>
					</div>
				</div>
			</div>
		</div>
		<h3 class="card-header">{l s='Open dimensions' mod='clariprint'} :</h3>
		<div class="card-block">
			<div class="row">
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon">{l s='Open width' mod='clariprint'}</span>
						<input class="form-control CLFloat ClOpenWidth text-xs-right" name="{$product_key}[openwidth]" id="{$product_key}_openwidth" value="{$product->width|default:'21'}" type="text" />
						<span class="input-group-addon">{l s='cm' mod='clariprint'}</span>
					</div>
				</div>
				<div class="col-md-6">
					<div class="input-group">
						<span class="input-group-addon">{l s='Open Height' mod='clariprint'}</span>
						<input class="form-control CLFloat ClOpenHeight text-xs-right"  name="{$product_key}[openheight]" id="{$product_key}_openheight" value="{$product->height|default:'29.7'}" type="text" />
						<span class="input-group-addon">{l s='cm' mod='clariprint'}</span>
					</div>
				</div>
			</div>
			{if $product->options->sizes}
			<div class="row-fluid">
				<div class="span8">{l s='Proposed values' mod='clariprint'} :
					{assign var=sizes value="\n"|explode:$product->options->sizes} 
					{foreach from=$sizes item=x}
						{if $x != ''}
						<a class="button" onclick="clupdatesizeopen('{$product_key}','{$x|trim}')">{$x}</a>
						{/if}
					{/foreach}
				</div>
			</div>
			{/if}
			{/if}
			
		{elseif $product->options->size == 'list'}
			{assign var=sizes value="\n"|explode:$product->options->sizes} 
			<select name="{$product_key}[size]">
				{foreach from=$sizes item=x}
					{if $x != ''}
				<option value="{$x}" {if $x == $product->size}selected{/if}>{$x}</option>
					{/if}
				{/foreach}
			</select>
		{/if}



		{if $product->options->bleeds}
			<div class="clear"></div>
			{* bleeds *}
			<label for="{$product_key}[with_bleeds]">{l s="include bleeds ?" mod='clariprint'} : </label>
			<input type="checkbox" name="{$product_key}[with_bleeds]" value="1" id="{$product_key}[with_bleeds]" {if ($product->with_bleeds)}checked{/if} />
		{else}
			<input type="hidden" name="{$product_key}[with_bleeds]" id="{$product_key}[with_bleeds]" value="{$product->with_bleeds}" />
		{/if}

		{if $product->options->untrimmed}
			<div class="clear"></div>
			{* bleeds *}
			<label for="{$product_key}_untrimmed">{l s="Raw ?" mod='clariprint'} : </label>
			<input type="checkbox" name="{$product_key}[untrimmed]" value="untrimmed" id="{$product_key}_untrimmed_" {if ($product->untrimmed)}checked{/if} />
		{else}
			<input type="hidden" name="{$product_key}[untrimmed]" id="{$product_key}_untrimmed" value="{$product->untrimmed}" />
		{/if}
		
		
		{if $product->options->sizes_info}
		<div class="alert alert-info" role="alert">{$product->options->sizes_info nofilter}</div>
		{/if}
		{if $product->options->sizes_info_cms}
			{displayCMS cms=$product->options->sizes_info_cms}
		{else}
			{displayCMS cms='product-dimensions'}
		{/if}
		
	</div>
</div>
{elseif $product->options->size == 'override'}
	<input type="hidden" name="{$product_key}[size]" value="override"/>
{else}
	<input type="hidden" name="{$product_key}[height]" value="{$product->height}" class="ClWidth"/>
	<input type="hidden" name="{$product_key}[width]" value="{$product->width}" class="ClHeight"/>
	<input type="hidden" name="{$product_key}[openwidth]" value="{$product->openwidth}" class="ClOpenWidth"/>
	<input type="hidden" name="{$product_key}[openheight]" value="{$product->openheight}" class="ClOpenHeight"/>
	<input type="hidden" name="{$product_key}[with_bleeds]" id="{$product_key}[with_bleeds]" value="{$product->with_bleeds}" />
	<input type="hidden" name="{$product_key}[untrimmed]" id="{$product_key}[untrimmed]" value="{$product->untrimmed}" />
{/if}

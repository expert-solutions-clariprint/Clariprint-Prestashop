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
	<div class="card-header">{l s='Finished dimensions' mod='clariprint'} :</div>
<div class="sizes card-block">
	<div class="field">
		<label for="{$product_key}_options_quantity">{l s='Mode :' mod='clariprint'}</label>
		<select name="{$product_key}[options][size]" id="{$product_key}_options_quantity">
			<option value="override" {if $product->options->size == 'override'}selected{/if}>{l s='Ignorer' mod='clariprint'}</option>
			<option value="hidden" {if $product->options->size == 'hidden'}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
			<option value="fixed" {if $product->options->size == 'fixed'}selected{/if}>{l s='Fixed' mod='clariprint'}</option>
			<option value="list" {if $product->options->size == 'list'}selected{/if}>{l s='List' mod='clariprint'}</option>
			<option value="free" {if $product->options->size == 'free'}selected{/if}>{l s='Free' mod='clariprint'}</option>
		</select>
	</div>
	<div class="clear"></div>
	<label>{l s='Default : '  mod='clariprint'}</label>
	{if $product->kind == 'leaflet' || $product->kind == 'section' || $product->kind == 'book'}
		<div class="input-group">
			<span class="input-group-addon">{l s='Width'} :</span>
			<input type="text" name="{$product_key}[width]" value="{$product->width|default:'21'}" id="{$product_key}[width]" title="{l s='width' mod='clariprint'}" class="form-control CLFloat  ClWidth"/>
			<span class="input-group-addon">x</span>
			<span class="input-group-addon"> height :</span>
			<input type="text" name="{$product_key}[height]" value="{$product->height|default:'29,7'}" id="{$product_key}[height]" class="form-control CLFloat ClHeight" title="{l s='height' mod='clariprint'}"/>
			<span class="input-group-addon">cm</span>
		</div>
	{else}
	<label for="{$product_key}[width]">{l s='Closed : ' mod='clariprint'}</label>
	<input type="text" name="{$product_key}[width]" value="{$product->width|default:'21'}" id="{$product_key}[width]" title="{l s='width' mod='clariprint'}" class="CLFloat ClWidth"/> x <input type="text" name="{$product_key}[height]" value="{$product->height|default:'29,7'}" id="{$product_key}[height]" class="
	Float ClHeight" title="{l s='height' mod='clariprint'}"/>
	<div class="clear"></div>
	
	<label for="{$product_key}[width]">{l s='Open : ' mod='clariprint'}</label>
	<input type="text" name="{$product_key}[openwidth]" value="{$product->openwidth|default:'21'}" id="{$product_key}[openwidth]" title="{l s='width' mod='clariprint'}" class="CLFloat ClOpenWidth"/> x <input type="text" name="{$product_key}[openheight]" value="{$product->openheight|default:'29,7'}" id="{$product_key}[openheight]" class="CLFloat ClOpenHeight" title="{l s='height' mod='clariprint'}"/>
	<div class="clear"></div>
	<label for="{$product_key}[options][open_size_free]">{l s='Client can change open size' mod='clariprint'}</label>
	<input type="checkbox" name="{$product_key}[options][open_size_free]" id="{$product_key}[options][open_size_free]" {if $product->options->open_size_free}checked{/if} />
	{/if}

	<div class="clear"></div>
	<div class="form-group">
		<label class="form-control-label" for="{$product_key}[options][sizes]_">{l s='List of dimensions' mod='clariprint'} :</label>
		<textarea class="form-control"  name="{$product_key}[options][sizes]" id="{$product_key}[options][sizes]_" cols="80" rows="10">{$product->options->sizes|default:''}</textarea>
	</div>
	<p class="description">{l s='[closed width]x[closed height] : [open width]x[open height] : title'  mod='clariprint'}<br>
	{l s='ex : 21x29.7 : 42x29.7 : 4 pages A4 simple fold'  mod='clariprint'}</p>
	<div class="clear"></div>
	{* bleeds *}
	<label for="{$product_key}[options][bleeds]">{l s='Present bleeds options' mod='clariprint'} : </label>
	<input type="checkbox" name="{$product_key}[options][bleeds]" value="1" id="{$product_key}[options][bleeds]" {if ($product->options->bleeds)}checked{/if}/> 
	<div class="clear"></div>
	<label for="{$product_key}[with_bleeds]">{l s='include bleeds ?' mod='clariprint'} : </label>
	<input type="checkbox" name="{$product_key}[with_bleeds]" value="1" id="{$product_key}[with_bleeds]" {if ($product->with_bleeds)}checked{/if} />
	<div class="clear"></div>
	
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][sizes_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->sizes_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
	<div class="alert alert-info"  role="alert">{l s='CMS doc page :' mod='clariprint'} : product-dimensions</div>
	
		{* bleeds *}
	<div class="form-group">
		<label class="form-control-label" for="{$product_key}_options_untrimmed">
		<input class="form-control"  data-toggle="switch" data-inverse="true" type="checkbox" name="{$product_key}[options][untrimmed]" value="1" id="{$product_key}_options_untrimmed" {if ($product->options->untrimmed)}checked{/if}/> {l s='Present untrimmed option' mod='clariprint'}</label>
	</div>
	<div class="form-group">
		<label class="form-control-label" for="{$product_key}_untrimmed"> 
		<input class="form-control"  data-toggle="switch" type="checkbox" name="{$product_key}[untrimmed]" data-inverse="true" value="untrimmed" id="{$product_key}_untrimmed" {if ($product->untrimmed == 'untrimmed')}checked{/if} /> {l s='untrimmed (without final cut) ?' mod='clariprint'}</label>
	</div>
	
	
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][sizes_info_cms]" value="{$product->options->sizes_info_cms}"/>
	
	
</div>
</div>
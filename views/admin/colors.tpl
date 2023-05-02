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
{assign var=primaries value=array('white','cyan','magenta','yellow','black','4-color')}
{assign var=specialtones value=array('pms1','pms2','pms3','pms4')}

<div class="card expandable">
	<div class="card-header">{if isset($clariprint_card_label)}{$clariprint_card_label}{else}{l s='Color & Inks' mod='clariprint'}{/if} :</div>
<div id="inks" class="inks card-block">
	<div class="field">
		<label for="{$product_key}_options_colors">{l s='Mode :' mod='clariprint'}</label>
		<select name="{$product_key}[options][colors]" id="{$product_key}_options_colors">
			<option value="hidden" {if $product->options->colors == 'hidden'}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
			<option value="simple" {if $product->options->colors == 'simple'}selected{/if}>{l s='Simple' mod='clariprint'}</option>
			<option value="list" {if $product->options->colors == 'list'}selected{/if}>{l s='list' mod='clariprint'}</option>
			<option value="onelist" {if $product->options->colors == 'onelist'}selected{/if}>{l s='single list' mod='clariprint'}</option>
			<option value="all" {if $product->options->colors == 'all'}selected{/if}>{l s='Full' mod='clariprint'}</option>
		</select>
	</div>
	<div class="clear"></div>
	<div class="field">
		<label for="noprint">{l s='Show No printing option' mod='clariprint'}</label>
		<input type="checkbox" name="{$product_key}[options][no_colors]" value="show" {if $product->options->no_colors}checked{/if} />
	</div>
	<div class="clear"></div>
	<div class="field">
		<label for="noprint">{l s='No printing' mod='clariprint'}</label>
		<input type="checkbox" name="{$product_key}[no_colors]" value="1" {if $product->no_colors}checked{/if} />
	</div>
	<div class="clear"></div>
	<table class="ink table-sm">
		<thead>
			<tr>
				<th>{l s='Color' mod='clariprint'}</th>
				<th>{l s='Show' mod='clariprint'}</th>
				<th>{l s='Recto' mod='clariprint'}</th>
				<th>{l s='Verso' mod='clariprint'}</th>
				<th>{l s='Code' mod='clariprint'}</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$primaries item=color}
			<tr>
				<th>{l s=$color}</th>
				<td><input type="checkbox" name="{$product_key}[options][colors_available][]" value="{$color}" {if in_array_silent($color,$product->options->colors_available)}checked{/if} />
				</td>
				<td>
					<input type="checkbox" name="{$product_key}[front_colors][]" value="{$color}" {if in_array_silent($color,$product->front_colors)}checked{/if} />
				</td>
				<td>
					<input type="checkbox" name="{$product_key}[back_colors][]" value="{$color}" {if in_array_silent($color,$product->back_colors)}checked{/if} />
				</td>
			</tr>
			{/foreach}
			{foreach from=$specialtones item=color}
			<tr>
				<th>{$color}
					<select name="{$product_key}[{$color}][class]" style="width: 70px;">
						<option value="PMS" {if ($product->$color->class == "PMS")}selected{/if} >{l s='Pantone' mod='clariprint'}</option>
						<option value="Metal" {if ($product->$color->class == "Metal")}selected{/if}>{l s='Metallic' mod='clariprint'}</option>
						<option value="Spot" {if ($product->$color->class == "Spot")}selected{/if}>{l s='Spot' mod='clariprint'}</option>
					</select>
					
				</th>
				<td><input type="checkbox" name="{$product_key}[options][colors_available][]" value="{$color}" {if in_array_silent($color,$product->options->colors_available)}checked{/if} />
				</td>
				<td>
					<input type="checkbox" name="{$product_key}[front_colors][]" value="{$color}" {if in_array_silent($color,$product->front_colors)}checked{/if} />
				</td>
				<td>
					<input type="checkbox" name="{$product_key}[back_colors][]" value="{$color}" {if in_array_silent($color,$product->back_colors)}checked{/if} />
				</td>
				<td>
					<input type="text" class="PMSCode"  name="{$product_key}[{$color}][code]" value="{$product->$color->code}" />
				</td>
			</tr>
			{/foreach}
		</tbody>
	</table>
	
	<h4>{l s='Available colors used with list mode'}<h4>
		<div class="row">
			<div class="col">
				<div class="form-group">
					<label for="form-control-label">{l s='for recto or onelist option'}</label>
					<textarea class="form-control" name="{$product_key}[options][front_colors]" rows="8" cols="40">{$product->options->front_colors}</textarea>
				</div>
			</div>
			<div class="col">
				<div class="form-group">
					<label class="form-control-label">{l s='for back'}</label>
					<textarea class="form-control" name="{$product_key}[options][back_colors]" rows="8" cols="40">{$product->options->back_colors}</textarea>
				</div>
			</div>
			
		</div>
	<div class="info">
		{l s='Colors list samples' mod='clariprint'}
		<ul>
			<li>C:Cyan</li>
			<li>Q:Quadri</li>
			<li>Y:Jaune</li>
			<li>P189:Pantone 189</li>
			<li>Q P189:Quadri + Pantone 189</li>
			<li>N P189:Noir + Pantone 189</li>
		</ul>
	</div>
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][colors_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->colors_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][colors_info_cms]" value="{$product->options->colors_info_cms}"/>
</div>
</div>
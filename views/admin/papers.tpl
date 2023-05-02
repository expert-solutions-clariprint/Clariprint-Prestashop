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
	<div class="card-header">{if isset($clariprint_card_label)}{$clariprint_card_label}{else}{l s='Papers' mod='clariprint'}{/if}</div>
	<div id="papers" class="ClariprintPapers PaperWidget PaperWidgetAdmin card-block">
	<div class="field">
		<label for="{$product_key}_options_papers">{l s='Mode :' mod='clariprint'}</label>
		<select name="{$product_key}[options][papers][mode]" id="{$product_key}_options_papers" class="custom-select" >
			<option value="hidden" {if $product->options->papers->mode == 'hidden'}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
			<option value="simple" {if $product->options->papers->mode == 'simple'}selected{/if}>{l s='Simple' mod='clariprint'}</option>
			<option value="all" {if $product->options->papers->mode == 'all'}selected{/if}>{l s='Full' mod='clariprint'}</option>
			<option value="list" {if $product->options->papers->mode == 'list'}selected{/if}>{l s='List' mod='clariprint'}</option>
			<option value="graphic" {if $product->options->papers->mode == 'graphic'}selected{/if}>{l s='Graphic' mod='clariprint'}</option>
		</select>
	</div>


	<div class="clear"></div>
	<div class="field">
		<label for="$product_key}_option_labels_mode">{l s='Ecologic label' mod='clariprint'} : </label>
		<select name="{$product_key}[options][labels_mode]" id="{$product_key}_labels_mode" class="custom-select" >
			<option value="" {if $product->options->labels_mode == ''}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
			<option value="show" {if $product->options->labels_mode == 'hidden'}selected{/if}>{l s='Show' mod='clariprint'}</option>
		</select>
	</div>
	<div class="clear"></div>
	
	<div class="field">
		<label for="$product_key}_label_pefc">{l s='PEFC' mod='clariprint'}</label>
		<input type="checkbox" name="{$product_key}[label]" value="pefc" id="{$product_key}_label_pefc" class="ClariprintLabel" {if $product->label == 'pefc'}checked{/if}/>
		
	</div>
	
	{if isset($product->papers->of)}{assign var=pkk value='of'}
	{else}{assign var=pkk value='custom'}{/if}
	
	<div class="clear"></div>
	<h4>{l s='Procédés d\'impression' mod='clariprint'}</h4>
	<div class="field">
		<label for="$product_key}_option_papers_process_show">{l s='Affichage' mod='clariprint'} : </label>
		<select name="{$product_key}[options][process_show]" id="{$product_key}_option_papers_process_show">
			<option value="" {if $product->options->process_show == ''}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
			<option value="show" {if $product->options->process_show == 'show'}selected{/if}>{l s='Show' mod='clariprint'}</option>
		</select>
	</div>
	<div class="clear"></div>
	<div class="">
		<div class="field">
			<label>{l s='Select available processes' mod='clariprint'} : </label>
		<table class="table table-striped" id="{$product_key}_processes">
			<thead>
				<tr>
					<th>
						{l s='show' mod='clariprint'}
					</th>
					<th>
						{l s='defaults' mod='clariprint'}
					</th>
					<th>
						{l s='Process' mod='clariprint'}
					</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$processes item=pm}
				{foreach from=$pm item=pt key=pk}
				<tr>
					<td>
						<input type="checkbox" name="{$product_key}[options][papers][processes][]" value="{$pk}" class="AvailablePaperProcess" id="{$product_key}_options_papers_processes_{$pk}" {if in_array_silent($pk,$product->options->papers->processes)}checked{/if}/>
					</td>
					<td>
						<input class="processes DefaultsPaperProcess" type="checkbox" name="{$product_key}[papers][{$pkk}][processes][]"  value="{$pk}" id="{$product_key}_papers_{$pkk}_processes_{$pk}" {if in_array_silent($pk,$product->papers->$pkk->processes)}checked{/if}/>
					</td>
					<td>
						{$pt}
					</td>
				</tr>
				{/foreach}
				{/foreach}
			</tbody>
		</table>
		</div>
		<div class="clear"></div>
		<table class="table table-striped" 
				id="{$product_key}_paper_{$pkk}"
				clariprint_paper_process="{$pkk}"
				clariprint_processes="#{$product_key}_processes"
				>
			<thead>
				<tr>
					<th></th>
					<th>{l s='Quality' mod='clariprint'}</th>
					<th>{l s='Brand' mod='clariprint'}</th>
					<th>{l s='Color' mod='clariprint'}</th>
					<th>{l s='Weight' mod='clariprint'}</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>{l s='Fixed'  mod='clariprint'}</th>
					<td><input type="checkbox" name="{$product_key}[options][papers][{$pkk}][fixed_quality]" {if $product->options->papers->$pkk->fixed_quality}checked{/if}/></td>
					<td><input type="checkbox" name="{$product_key}[options][papers][{$pkk}][fixed_brand]" {if $product->options->papers->$pkk->fixed_brand}checked{/if}/></td>
					<td><input type="checkbox" name="{$product_key}[options][papers][{$pkk}][fixed_color]" {if $product->options->papers->$pkk->fixed_color}checked{/if}/></td>
					<td><input type="checkbox" name="{$product_key}[options][papers][{$pkk}][fixed_weight]" {if $product->options->papers->$pkk->fixed_weight}checked{/if}/></td>
				</tr>
				<tr>
					<th>{l s='Défault'  mod='clariprint'}</th>
					<td>
						<select name="{$product_key}[papers][{$pkk}][quality]" class="quality custom-select c-select">
							{if $product->papers->$pkk->quality} 
							<option value="{$product->papers->$pkk->quality}" selected>{$product->papers->$pkk->quality}</option>
							{/if}
						</select>
						{$product->papers->$pkk->quality}
						{$pkk}
					</td>
					<td>
						<select name="{$product_key}[papers][{$pkk}][brand]" class="brand custom-select c-select">
							<option value=''>-</option>
							{if $product->papers->$pkk->brand}
							<option value="{$product->papers->$pkk->brand}"selected>{l s=$product->papers->$pkk->brand mod='clariprint'}</option>
							{/if}
						</select>
						{$product->papers->$pkk->brand}
					</td>
					<td>
						<select name="{$product_key}[papers][{$pkk}][color]" class="paper-color custom-select c-select">
							<option value=''>-</option>
							{if $product->papers->$pkk->color}
							<option value="{$product->papers->$pkk->color}" selected>{$product->papers->$pkk->color}</option>
							{/if}
						</select>
						{$product->papers->$pkk->color}
					</td>
					<td>
						<select name="{$product_key}[papers][{$pkk}][weight]" class="weight custom-select c-select">
							{if  $product->papers->$pkk->weight}
							<option value="{$product->papers->$pkk->weight}" selected>{$product->papers->$pkk->weight}</option>
							{/if}
						</select>
						{$product->papers->$pkk->weight}
					</td>
					<td>
						<input type="button" name="" value="{l s='reset' mod='clariprint'}" class="clariprin_reset_paper"/>
					</td>
				</tr>
				<tr>
					<th>{l s='Filter' mod='clariprint'}</th>
					
					<td><select name="{$product_key}[options][papers][{$pkk}][qualities][]" multiple="multiple" size="20" class="PaperQualitiesFilter custom-select" length="20">
							{if $product->options->papers->$pkk->qualities}
							{foreach from=$product->options->papers->$pkk->qualities item="item"}
							<option value="{$item}" selected="selected">{$item}</option>
							{/foreach}
							{/if}
						</select></td>
					<td><select name="{$product_key}[options][papers][{$pkk}][brands][]" multiple="multiple" size="20" class="PaperBrandsFilter custom-select">
						{foreach from=$product->options->papers->$pkk->brands item="item"}
							<option value="{$item}" selected="selected">{$item}</option>
						{/foreach}
						</select></td>
					<td><select name="{$product_key}[options][papers][{$pkk}][colors][]" multiple="multiple" size="20" class="PaperColorsFilter custom-select">
						{foreach from=$product->options->papers->$pkk->colors item="item"}
							<option value="{$item}" selected="selected">{$item}</option>
							{/foreach}
						</select></td>
					<td><select name="{$product_key}[options][papers][{$pkk}][weights][]" multiple="multiple" style="width: 50px; text-align: right" size="20" class="PaperWeightsFilter custom-select">
						{foreach from=$product->options->papers->$pkk->weights item="item"}
							<option value="{$item}" selected="selected">{$item}</option>
						{/foreach}
						</select>
					</td>
				</tr>
				<tr>
					<th>
						{l s='Groupe' mod='clariprint'}<br>
						<select name="{$product_key}[options][papers2][{$pkk}][group]" class="custom-select">
							<option value=''>-</option>
							{foreach $groups as $grp}
							<option value="{$grp['id_group']}" {if $product->options->papers2->$pkk->group == $grp['id_group']}selected{/if}>{$grp['name']}</option>
							{/foreach}
						</select>
					</th>
					<td>
						<input type="checkbox" name="{$product_key}[options][papers2][{$pkk}][fixed_quality]" {if $product->options->papers2->$pkk->fixed_quality}checked{/if}/>
						<select name="{$product_key}[options][papers2][{$pkk}][qualities][]" multiple="multiple" size="10" class="GroupPaperQualitiesFilter custom-select" >
							{if $product->options->papers2->$pkk->qualities}
							{foreach from= $product->options->papers2->$pkk->qualities item="item"}
							<option value="{$item}" selected="selected">{$item}</option>
							{/foreach}
							{/if}
						</select>
					</td>
					<td>
						<input type="checkbox" name="{$product_key}[options][papers2][{$pkk}][fixed_brand]" {if $product->options->papers2->$pkk->fixed_brand}checked{/if}/>
						<select name="{$product_key}[options][papers2][{$pkk}][brands][]" multiple="multiple" size="10" class="GroupPaperBrandsFilter custom-select" >
							{if $product->options->papers2->$pkk->brands}
							{foreach from=$product->options->papers2->$pkk->brands item="item"}
							<option value="{$item}" selected="selected">{$item}</option>
							{/foreach}
							{/if}
						</select>
					</td>
					<td><input type="checkbox" name="{$product_key}[options][papers2][{$pkk}][fixed_color]" {if $product->options->papers2->$pkk->fixed_color}checked{/if}/>
						<select name="{$product_key}[options][papers2][{$pkk}][colors][]" multiple="multiple" size="10" class="GroupPaperColorsFilter custom-select" >
							{if $product->options->papers2->$pkk->colors}
							{foreach from=$product->options->papers2->$pkk->colors item="item"}
							<option value="{$item}" selected="selected">{$item}</option>
							{/foreach}
							{/if}
						</select>
					</td>
					<td><input type="checkbox" name="{$product_key}[options][papers2][{$pkk}][fixed_weight]" {if $product->options->papers2->$pkk->fixed_weight}checked{/if}/>
						<select name="{$product_key}[options][papers2][{$pkk}][weights][]" multiple="multiple" size="10" class="GroupPaperWeightsFilter custom-select" >
							{if $product->options->papers2->$pkk->weights}
							{foreach from=$product->options->papers2->$pkk->weights item="item"}
							<option value="{$item}" selected="selected">{$item}</option>
							{/foreach}
							{/if}
						</select>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="paperlists">
	{l s='List (used on list mode)' mod='clariprint'}<br/>
		<textarea name="{$product_key}[options][paper_list]" role="paper-list-area" rows="8" class="form-control" cols="100" >{$product->options	->paper_list}</textarea>
		<button class="btn" role="button-paper-add" for="">{l s="getCurrentPaper" mod='clariprint'}</button>
		<script>
			
		jQuery(function(){
			$("button[role=button-paper-add]").click(function(evt){
				evt.stopPropagation();
				evt.preventDefault();
				var card = $(evt.currentTarget).closest(".card");
				var txt = card.find("select.quality").val() + ";" + card.find("select.brand").val() + ";" + card.find("select.paper-color").val() + ";" + card.find("select.weight").val() + ";your desciption\n";
				var area = card.find("[role=paper-list-area]");
				area.val(area.val() + txt);
			});
				
		});
		</script>
	</div>
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][papers_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->papers_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][papers_info_cms]" value="{$product->options->papers_info_cms}"/>
	
</div>	
</div>
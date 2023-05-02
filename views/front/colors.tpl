{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*
* FRONT 
*}


{if $product->options->colors != 'hidden'}
<div class="card ClariprintColorWidget clariprint-colors">
		<h3 class="card-header">{if isset($clariprint_card_label)}{$clariprint_card_label}{else}{l s='Color & Inks' mod='clariprint'}{/if} :</h3>
		<div class="card-block">
	{if $product->options->colors == 'fixed'} 
		{foreach from=$product->front_colors item=c}
			<input type="hidden" name="{$product_key}[front_colors][]" value="{$c}" />
		{/foreach}
		{foreach from=$product->back_colors item=c}
			<input type="hidden" name="{$product_key}[back_colors][]" value="{$c}" />
		{/foreach}
	<ul>
		<li>{l s='Front colors:' mod='clariprint'}</li>
		<li>{l s='Back colors:' mod='clariprint'}</li>
	</ul>

	{elseif $product->options->colors == 'onelist'}

		{assign var=rcolors value="\n"|explode:$product->options->front_colors}

		<select name="{$product_key}[colors]" class="form-control">
			{foreach from=$rcolors item=c}
			{assign var=col value=':'|explode:$c:2}
			<option value="{$col[0]}">{l s=$col[1] mod='clariprint'}</option>
			{/foreach}
		</select>
	
	{elseif $product->options->colors == 'list'} 

		{assign var=rcolors value="\n"|explode:$product->options->front_colors}
		<label for="{$product_key}_front_colors">{l s='Front colors' mod='clariprint'}
		<select name="{$product_key}[front_colors][]" id="{$product_key}_front_colors" class="">
			{foreach from=$rcolors item=c}
			{assign var=col value=':'|explode:$c:2}
			<option value="{$col[0]}">{l s=$col[1] mod='clariprint'}</option>
			{/foreach}
		</select>
		</label>
		
		{if $product->options->back_colors}
		{assign var=bcolors value="\n"|explode:$product->options->back_colors}
		<label for="{$product_key}_back_colors">{l s='Back colors' mod='clariprint'}
		<select name="{$product_key}[back_colors][]" id="{$product_key}_back_colors" class="">
			{foreach from=$bcolors item=c}
			{assign var=col value=':'|explode:$c:2}
			<option value="{$col[0]}">{l s=$col[1] mod='clariprint'}</option>
			{/foreach}
		</select>
		</label>
		{/if}

	{elseif $product->options->colors == 'simple'} 
		<label for="{$product_key}_colors">{l s='Front printed colors:'  mod='clariprint'}</label>
		<select name="{$product_key}[front_colors][]" id="clt">
			<option value="black" {if in_array_silent('black',$product->front_colors)}selected{/if}>{l s='black' mod='clariprint'}</option>
			<option value="4color" {if in_array_silent('4color',$product->front_colors) || in_array_silent('4-color',$product->front_colors)}selected="selected"{/if}>{l s='4 color' mod='clariprint'}</option>
		</select>
		<div class="clear"></div>
		{if isset($product->back_colors)}
		<label for="{$product_key}_colors">{l s='Back printed colors:'  mod='clariprint'}</label>
		<select name="{$product_key}[back_colors][]">
			<option value="">{l s='none' mod='clariprint'}</option>
			<option value="black" {if in_array_silent('black',$product->back_colors)}selected{/if}>{l s='black' mod='clariprint'}</option>
			<option value="4color" {if in_array_silent('4color',$product->back_colors) || in_array_silent('4-color',$product->back_colors)}selected="selected"{/if}>{l s='4 color' mod='clariprint'}</option>
		</select>
		{/if}
	{else}
		{if $product->options->no_colors}
		
		<div class="form-check">
			<label class="form-check-label">
				<input type="checkbox" class="form-check-input" role="no-colors" value="1" name="">
				{l s='Withtout printing' mod='clariprint'}
			</label>
		</div>
		{else}
		{/if}
		{assign var=primaries value=array('4-color','cyan','magenta','yellow','black','white')}
		{assign var=specialtones value=array('pms1','pms2','pms3','pms4')}

		{for $i=1 to 4}	
			{assign var=color value="pms$i"}
			{if (in_array_silent($color,$product->options->colors_available))}
				{assign var=countspecials value=$countspecials +  1 }
			{/if}
		{/for}


		<table id='{$product_key}_colors_table' class="table table-striped">
			<thead>
				<tr>
					<th>{l s='Kind' mod='clariprint'}</th>
					<th>{l s='Recto' mod='clariprint'}</th>
					<th>{l s='Verso' mod='clariprint'}</th>
					<th>{l s='Code' mod='clariprint'}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$primaries item=c}
					{if (in_array_silent($c,$product->options->colors_available))}
				<tr>
					<th>{l s=$c mod='clariprint'}</th>
					<td><input type="checkbox" class="CLColor frontcolor primary" name="{$product_key}[front_colors][]" value="{$c}" {if in_array_silent($c,$product->front_colors)}checked{/if} /></td>
					<td><input type="checkbox" class="CLColor backcolor primary" name="{$product_key}[back_colors][]" value="{$c}" {if in_array_silent($c,$product->back_colors)}checked{/if} /></td>
					<td>
						{if $c == '4-color'}
						<div style="background-image: url(/modules/clariprint/img/quadri2.png) ; background-size: 100% ; width: 2em ; width: 2em; height: 2em " />
						{else}
						<div style="background-color: {$c}; width: 2em ; width: 2em; height: 2em " />
						{/if}
					</td>
				</tr>
					{/if}
				{/foreach}
				{foreach from=$specialtones item=color}
					{if (in_array_silent($color,$product->options->colors_available))}
				<tr>
					<th>
						{assign var=pms_id value=uniqid('pms')}
						<select name="{$product_key}[{$color}][class]" id="{$pms_id}" class="custom-select">
							<option value="PMS" {if ($product->$color->class == "PMS")}selected{/if} >{l s='Pantone' mod='clariprint'}</option>
							<option value="Metal" {if ($product->$color->class == "Metal")}selected{/if}>{l s='Metallic' mod='clariprint'}</option>
							<option value="Spot" {if ($product->$color->class == "Spot")}selected{/if}>{l s='Spot' mod='clariprint'}</option>
						</select>
					</th>
					<td>
						<input type="checkbox" class="CLColor frontcolor" name="{$product_key}[{$color}][front]" value="1" 
								{if $product->$color->front}checked{/if} /></td>
					<td>
						<input type="checkbox" class="CLColor backcolor" name="{$product_key}[{$color}][back]" value="1" 
								{if $product->$color->back}checked{/if} /></td>
					<td>
						<input type="text" size="10" class="form-control clariprint_pms" kind-selector="#{$pms_id}"  
								name="{$product_key}[{$color}][code]"
								color_item="#{$product_key}_{$color}"
								value="{$product->$color->code}" placeholder="{l s="type here pantone color code"}"/>
					</td>
				</tr>
					{/if}
				{/foreach}
			</tbody>
		</table>
	{/if}
	{if $product->options->pms}
	<label>{l s='Special colors' mod='clariprint'}</label>
	<table id='{$product_key}_colors_table'>
		<thead>
			<tr>
				<th>{l s='colors' mod='clariprint'}</th>
				{foreach from=$sepcial_colors item=pms}
				{}
				<th>
					<select name="{$product_key}[{$pms}][class]" id="{$product_key}[{$pms}]_class" style="width: 70px;">
						<option value="PMS" {if ($product->$pms->class == "PMS")}selected{/if} >{l s='Pantone' mod='clariprint'}</option>
						<option value="Metal" {if ($product->$pms->class == "Metal")}selected{/if}>{l s='Metallic' mod='clariprint'}</option>
						<option value="Spot" {if ($product->$pms->class == "Spot")}selected{/if}>{l s='Spot' mod='clariprint'}</option>
					</select>
					<input size="3" name="{$product_key}[{$pms}][code]" value="{$product->$pms->code|Default:'{$pms}'}" type="text"></td>
				</th>
				{/foreach}
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>{l s='Front colors'}</th>
				{foreach from=array('pms1','pms2','pms3','pms4') item=pms}
				<td><input name="{$product_key}[{$pms}][recto]" value="1" type="checkbox" {if ($product->$pms->recto)}checked{/if}/></td>
				{/foreach}
			</tr>
			
			<tr>
				<th>{l s='Back colors'}</th>
				{foreach from=array('pms1','pms2','pms3','pms4') item=pms}
				<td><input name="{$product_key}[{$pms}][verso]" value="1" type="checkbox" {if ($product->$pms->verso)}checked{/if}/></td>
				{/foreach}
			</tr>
		</tbody>	
	</table>
	{/if}
	{if $product->options->colors_info}
	<div class="alert alert-info" role="alert">{$product->options->colors_info nofilter}</div>
	{/if}
	{if $product->options->colors_info_cms}
		{displayCMS cms=$product->options->colors_info_cms}
	{else}
		{displayCMS cms='product-colors'}
	{/if}
	
</div>
</div>
{else}
	{foreach from=$product->front_colors item=color}
		<input type="hidden" name="{$product_key}[front_colors][]" value="{$color}"/>
	{/foreach}
	{foreach from=$product->back_colors item=color}
		<input type="hidden" name="{$product_key}[back_colors][]" value="{$color}"/>
	{/foreach}
	{for $i=1 to 4}
		{assign var=color value="pms$i"}
		{if $product->$color}
			<input type="hidden" name="{$product_key}[{$color}][code]" value="{$product->$color->code}"/>
			<input type="hidden" name="{$product_key}[{$color}][back]" value="{$product->$color->back}"/>
			<input type="hidden" name="{$product_key}[{$color}][front]" value="{$product->$color->front}"/>
		{/if}
	{/for}
{/if}

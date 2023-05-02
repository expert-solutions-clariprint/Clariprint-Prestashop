{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
<fieldset id="colors" class="colors">
	<legend>{l s='Color & Inks' mod='clariprint'} :</legend>
	{if $product->options->colors == 'fixed'} 
	{foreach from=$product->front_colors item=c}
	<input type="hidden" name="{$product_key}[front_colors][]" value="{$c}" />
	{/foreach}
	{foreach from=$product->back_colors item=c}
	<input type="hidden" name="{$product_key}[back_colors][]" value="{$c}" />
	{/foreach}
	
	{elseif $product->options->colors == 'simple'} 
	<label for="{$product_key}_colors">{l s='Front printed colors:'  mod='clariprint'}</label>
	<select name="{$product_key}[front_colors][]">
		<option value="">{l s='none' mod='Clarprint'}</option>
		<option value="black" {if in_array_silent('black',$product->front_colors)}checked{/if}>{l s='black' mod='Clarprint'}</option>
		<option value="4color" {if in_array_silent('4color',$product->front_colors)}checked{/if}>{l s='4-color (cmyk)' mod='Clarprint'}</option>
	</select>
	<div class="clear"></div>
	<label for="{$product_key}_colors">{l s='Back printed colors:'  mod='clariprint'}</label>
	<select name="{$product_key}[back_colors][]">
		<option value="">{l s='none' mod='Clarprint'}</option>
		<option value="black" {if in_array_silent('black',$product->front_colors)}checked{/if}>{l s='black' mod='Clarprint'}</option>
		<option value="4color" {if in_array_silent('4color',$product->front_colors)}checked{/if}>{l s='4-color (cmyk)' mod='Clarprint'}</option>
	</select>
	{elseif $product->options->colors == 'all'}
	<table id='{$product_key}_colors_table'>
	<thead>
		<tr>
			<th>{l s='colors' mod='clariprint'}</th>
			<th>{l s='cyan' mod='clariprint'}</th>
			<th>{l s='magenta' mod='clariprint'}</th>
			<th>{l s='yellow' mod='clariprint'}</th>
			<th>{l s='black' mod='clariprint'}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<th>{l s='Front colors'}</th>
			{foreach from=$primary_colors item=c}
			<td><input type="checkbox" class="CLColor frontcolor" id='bob' name="{$product_key}[front_colors][]" value="{$c}" {if in_array_silent($c,$product->front_colors)}checked{/if} /></td>
			{/foreach}
			<td><a onclick="jQuery(this).parents('tr').find('input').attr('checked',true); return false;">{l s='4 color' c='clariprint'}</td>
		</tr>

		<tr id='bob2'>
			<th>{l s='Back colors'}</th>
			{foreach from=$primary_colors item=c}
			<td><input type="checkbox" class="CLColor backcolor" name="{$product_key}[back_colors][]" value="{$c}" {if in_array_silent($c,$product->back_colors)}checked{/if} /></td>
			{/foreach}
			<td><a onclick="jQuery(this).parents('tr').find('input').attr('checked',true); return false;" id='bob'>{l s='4 color' c='clariprint'}</td>
		</tr>
	<table>
	{/if}
	{if $product->options->pms}
	<label>{l s='Special colors' mod='clariprint'}</label>
	<table id='{$product_key}_colors_table'>
	<thead>
		<tr>
			<th>{l s='colors' mod='clariprint'}</th>
			{foreach from=$sepcial_colors item=pms}
			<th>
				<select name="{$product_key}[{$pms}][class]" style="width: 70px;">
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
			<td><input name="{$product_key}[{$pms}][verso]" value="1" type="checkbox" {if ($product->$pms->recto)}checked{/if}/></td>
			{/foreach}
		</tr>
	<table>
	{/if}

</fieldset>


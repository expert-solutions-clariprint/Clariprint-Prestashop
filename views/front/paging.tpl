
{assign var=filepages value=array(4,8,12,16,20,24,32,40,48)}
<div class="card">
	<h3 class="card-header">{l s='Pages' mod='clariprint'} :</h3>
	<div class="pages card-block">
	{if $product->options->pages == 'free'}
		<div class="row">
			<div class="col-md-6">
				<div class="input-group">
					<span class="input-group-addon">{l s='Pages : ' mod='clariprint'}</span>
					<input class="form-control CLFloat ClOpenWidth" 
								type="number" 
								name="{$product_key}[pages]"
								id="{$product_key}_pages"
								value="{$product->pages}"
								step="2"/>
				</div>
			</div>
		</div>
	{elseif $product->options->pages == 'fixed'}
		<label for="{$product_key}[pages]">
			<input type="text" readonly name="{$product_key}[pages]" class="CLInt  span4" value="{$product->pages}" 			id="clariprint_pages"/>{l s='Pages' mode='clariprint'}
		</label>
	{elseif $product->options->pages == 'buttons'}
		{assign var=paginations value="/[\s, ;]+/"|preg_split:$product->options->pages_list}
	<div class="row" controller="radio-buttons">
		<input type="hidden" name="{$product_key}[pages]" value="{(int)$product->pages}"/>
		{foreach from=$paginations item=page}
			{assign var="tempid" value=uniqid('radio')}
			<button class="btn {if (int)$product->pages == $page} btn-success{/if}"
					title="{l s='%d pages' sprintf=[$page]}"
				 type="radio"
				 value="{$page}">{$page}</button>
		{/foreach}
	</div>
	{else}
		{assign var=paginations value="/[\s, ;]+/"|preg_split:$product->options->pages_list}
		<select name="{$product_key}[pages]" class="form-control">
			{foreach from=$paginations item=page}
				{if (int)$page != 0}
				<option value="{(int)$page}" {if $page == (int)$product->pages}selected{/if}>{$page}</option>
				{/if}
			{/foreach}
		</select>
	{/if}
	{if $product->optons->show_decomposition}

	<h2 class="accordion_header">{l s='Manual setup' mod='clariprint'}</h2>

	<table class="pages">
		<thead>
			<tr>
				<th>{l s='Pages' mod='clariprint'}</th>
				{foreach from=$filepages item=pages}
				<th>{$pages}</th>
				{/foreach}
			</tr>
		</thead>
		<tbody>
			<tr>
				<th>{l s='Allowed' mod='clariprint'}</th>
				{foreach from=$filepages item=pages}
				<td><input type="checkbox" name="{$product_key}[allowed_parts][{$pages}]" value="1" {if $product->allowed_parts->$pages}checked="1"{/if}/></td>
				{/foreach}
			</tr>
			<tr>
				<th>{l s='Num. (let empty for automatic)' mod='clariprint'}</th>
				{foreach from=$filepages item=pages}
				<td><input type="text" class="CLInt" style="width: 20px" name="{$product_key}[allowed_parts_number][{$pages}]" value="{$product->allowed_parts_number->$pages}"/></td>
				{/foreach}
			</tr>
		</tbody>
	</table>

	{else}
		{foreach from=$filepages item=pages}
			{if $product->allowed_parts->$pages}
			<input type="hidden" name="{$product_key}[allowed_parts][{$pages}]" value="1"/>
			<input type="hidden" name="{$product_key}[allowed_parts_number][{$pages}]" value="{$product->allowed_parts_number->$pages}"/>
			{/if}
		{/foreach}
	{/if}
	{if $product->options->paging_info}
	<div class="alert alert-info" role="alert">{$product->options->paging_info nofilter}</div>
	{/if}
	{if $product->options->paging_info_cms}
		{displayCMS cms=$product->options->paging_info_cms}
	{else}
		{displayCMS cms='product-pages'}
	{/if}
</div>
</div>

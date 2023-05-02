
{assign var=filepages value=array(4,8,12,16,20,24,32,40,48)}

<div class="card expandable">
	<div class="card-header">{l s='Pages' mod='clariprint'}</div>
	<div class="card-block pages">
	<label for="{$product_key}[pages]">{l s='Default :' }</label>
	<input type="text" name="{$product_key}[pages]" class="CLInt" value="{$product->pages}" id="clariprint_pages"/>

	<div class="clear"></div>					
	<input type="radio" name="{$product_key}[options][pages]" value="free" id="{$product_key}[options][pages]_free" {if ($product->options->pages == 'free' ) }checked{/if} />
	<label for="{$product_key}[options][pages]_free">{l s='Free' mod='clariprint'}</label><br/>
	<div class="clear"></div>					
	<input type="radio" name="{$product_key}[options][pages]" value="fixed" id="{$product_key}[options][pages]_fixed" {if ($product->options->pages == 'fixed') }checked{/if}>
	<label for="{$product_key}[options][pages]_fixed">{l s='Fixed' mod='clariprint'}</label><br/>
	<div class="clear"></div>					
	<input type="radio" name="{$product_key}[options][pages]" value="list" id="{$product_key}[options][pages]_list" {if ($product->options->pages == 'list') }checked{/if}>
	<label for="{$product_key}[options][pages]_list">{l s='List' mod='clariprint'}</label><br/>
	<div class="clear"></div>
	<input type="radio" name="{$product_key}[options][pages]" value="buttons" id="{$product_key}[options][pages]_buttons" {if ($product->options->pages == 'buttons') }checked{/if}>
	<label for="{$product_key}[options][pages]_buttons">{l s='Buttons' mod='clariprint'}</label><br/>
	<div class="clear"></div>
	<textarea name="{$product_key}[options][pages_list]" rows="8" cols="40">{$product->options->pages_list}</textarea>
	<h2 class="accordion_header">{l s='Manual setup' mod='clariprint'}</h2>
	
	<label for="{$product_key}[options][show_decomposition]">{l s='Show' mod='clariprint'}</label><input type="checkbox" name="{$product_key}[options][show_decomposition]" value="1" {if $product->option->show_decomposition}checked{/if} id="{$product_key}[options][show_decomposition]"/>
	
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
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][paging_info]" class="clariprint_rte autoload_rte" rows="8" cols="40">{$product->options->paging_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
		<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
		<input type="text" class="form-control"  name="{$product_key}[options][paging_info_cms]" value="{$product->options->paging_info_cms}"/>
	
</div>
</div>
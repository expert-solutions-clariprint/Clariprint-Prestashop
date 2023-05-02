<div class="card expandable">
	<div class="card-header">{l s='Holes' mod='clariprint'}</div>
	<div class="card-block">
		{l s='Show' mod='clariprint'} : <input type="checkbox" value="holes" title='{l s='Show option' mod='clariprint'}' name="{$product_key}[options][makeready][]" id="" {if in_array_silent('holes',$product->options->makeready)}checked{/if}/>
		<div class="clear"></div>
		<ul>
			<li><input type="radio" name="{$product_key}[holes]" value="0" {if $product->holes == 0}checked{/if}/>{l s='No punch(es)' mod='clariprint'}</li>
			<li><input type="radio" name="{$product_key}[holes]" value="1" {if $product->holes == 1}checked{/if}/>{l s='1 file hole' mod='clariprint'}</li>
			<li><input type="radio" name="{$product_key}[holes]" value="2" {if $product->holes == 2}checked{/if}/>{l s='2 file hole' mod='clariprint'}</li>
			<li><input type="radio" name="{$product_key}[holes]" value="4" {if $product->holes == 4}checked{/if}/>{l s='4 file hole' mod='clariprint'}</li>
		</ul>
		<div class="clear"></div>
		{l s='User doc' mod='clariprint'}<br/>
		<textarea name="{$product_key}[options][holes_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->holes_info|htmlentitiesUTF8}</textarea>
		<div class="clear"></div>
		<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
		<input type="text" class="form-control"  name="{$product_key}[options][holes_info_cms]" value="{$product->options->holes_info_cms}"/>
	</div>
</div>
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
	<div class="card-header">{l s='Folder cutting die' mod='clariprint'}</div>
	<div id="folding" class="folding card-block">
	<div class="field">
		<label for="{$product_key}_options_folderdie">{l s='Mode :' mod='clariprint'}</label>
		<select name="{$product_key}[options][folderdie]" id="{$product_key}_options_folding">
			<option value="hidden" {if $product->options->folderdie == 'hidden'}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
			<option value="show" {if $product->options->folderdie != 'hidden'}selected{/if}>{l s='Show' mod='clariprint'}</option>
		</select>
	</div>

	<div class="clear"></div>
	<table class="table">
		<thead>
			<tr>
				<th>{l s='Show' mod='clariprint'}
<br/>
					<a href="#" onclick="">{l s='(un)check'}</a>
</th>
				
				<th>{l s='Default' mod='clariprint'}</th>
				<th>{l s='Icon' mod='clariprint'}</th>
				<th>{l s='Label' mod='clariprint'}</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$folderdie item=cut}
			<tr>
				<td><input type="checkbox" value="{$cut['index']}" name="{$product_key}[options][folder_cuttingdie][]" {if in_array_silent($cut['index'],$product->options->folder_cuttingdie)}checked="1"{/if}/></td>
				<td><input type="radio" value="{$cut['index']}" name="{$product_key}[cuttingdie]" {if $product->cuttingdie == $cut['index']}checked="1"{/if}/></td>
				<td><img src="/modules/clariprint/img/cutting/{$cut['index']}.png"/></td>
				<td>{l s=$cut['title'] mod='clariprint'} ???</td>
				<td>{l s='folder with 1 flap without leg of joining' mod='clariprint'} ???</td>
			</tr>
		{/foreach}
		</tbody>
	</table>
	<div class="clear"></div>
	<label  for="{$product_key}_cuttingdie_exists">{l s='Is the die-cut shape already made (if not a new die-cut shape will be created) ?' mod='clariprint'} :</label>
	<input type="checkbox" name="{$product_key}[cuttingdie_exists]" value="1" id="{$product_key}_cuttingdie_exists" {if ($product->cuttingdie_exists)}checked{/if}/> 
	<div class="clear"></div>
	<label>{l s='Show' mod='clariprint'} ? :</label>
	<input type="checkbox" name="{$product_key}[options][folder_die_shape]" value="1" id="{$product_key}_folder_die_shape" {if ($product->options->folder_die_shape)}checked{/if}/> 
	<div class="clear"></div>
	<label for="{$product_key}[flat_delivery]"> {l s='Delivered unfolded (if not: hand folding will be charged) ?' mod='clariprint'} :</label>
	<input type="checkbox" name="{$product_key}[flat_delivery]" value="1" id="{$product_key}[flat_delivery]" {if ($product->flat_delivery)}checked{/if}/>
	<div class="clear"></div>
	<label>{l s='Show' mod='clariprint'} ? :</label>
	<input type="checkbox" name="{$product_key}[options][folder_die_folded]" value="1" id="{$product_key}_folder_die_folded" {if ($product->options->folder_die_folded)}checked{/if}/> 
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][folderdie_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->folderdie_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][folderdie_info_cms]" value="{$product->options->folderdie_info_cms}"/>
	
</div>
</div>
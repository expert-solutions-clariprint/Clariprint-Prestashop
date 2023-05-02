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
	<div class="card-header">{if isset($clariprint_card_label)}{$clariprint_card_label}{else}{l s='Varnish & finishing' mod='clariprint'}{/if} :</div>
<div id="finishing" class="finishing card-block">
	<div class="field">
		<label for="{$product_key}_options_finishing">{l s='Mode :' mod='clariprint'}</label>
		<select name="{$product_key}[options][finishing]" id="{$product_key}_options_finishing">
			<option value="hidden" {if $product->options->finishing == 'hidden'}selected{/if}>{l s='Hidden' mod='clariprint'}</option>
			<option value="simpleR" {if $product->options->finishing == 'simpleR'}selected{/if}>{l s='Simple Recto' mod='clariprint'}</option>
			<option value="simpleRV" {if $product->options->finishing == 'simpleRV'}selected{/if}>{l s='Simple Recto/verso' mod='clariprint'}</option>
			<option value="unique" {if $product->options->finishing == 'unique'}selected{/if}>{l s='Unique Recto/verso' mod='clariprint'}</option>
			<option value="show" {if $product->options->finishing == 'show'}selected{/if}>{l s='Show' mod='clariprint'}</option>
		</select>
	</div>
	<div class="clear"></div>
	<label>{l s='Default - front' mod='clariprint'} :</label>
	<select name="{$product_key}[finishing_front]">
		<option value="">{l s='none' mod='clariprint'}</option>
		<optgroup label="{l s='inline varnishing' mod='clariprint'}">
			{foreach $vernis_en_lignes as $kvel => $vel}
			<option value="{$kvel}" {if $kvel == $product->finishing_front}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
		<optgroup label="{l s='offline varnishing' mod='clariprint'}">
			{foreach $vernis_en_reprise as $kvel => $vel}
			<option value="{$kvel}" {if $kvel == $product->finishing_front}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
		<optgroup label="{l s='varnish combination' mod='clariprint'}">
			{foreach $vernis_combines as $kvel => $vel}
			<option value="{$kvel}" {if $kvel == $product->finishing_front}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
	</select>
	<div class="clear"></div>
	<label>{l s='Available options - front' mod='clariprint'} :</label>
	<select name="{$product_key}[options][finishing_front][]" multiple size='10'>
		<option value="all" {if in_array_silent('all',$product->options->finishing_front)}selected{/if}>{l s='All' mod='clariprint'}</option>
		<optgroup label="{l s='inline varnishing' mod='clariprint'}">
			{foreach $vernis_en_lignes as $kvel => $vel}
			<option value="{$kvel}" {if in_array_silent($kvel,$product->options->finishing_front)}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
		<optgroup label="{l s='offline varnishing' mod='clariprint'}">
			{foreach $vernis_en_reprise as $kvel => $vel}
			<option value="{$kvel}" {if in_array_silent($kvel,$product->options->finishing_front)}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
		<optgroup label="{l s='varnish combination' mod='clariprint'}">
			{foreach $vernis_combines as $kvel => $vel}
			<option value="{$kvel}" {if in_array_silent($kvel,$product->options->finishing_front)}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
	</select>


	<div class="clear"></div>
	<label>{l s='Default - back' mod='clariprint'} :</label>
	<select name="{$product_key}[finishing_back]">
		<option value="">{l s='none' mod='clariprint'}</option>
		<optgroup label="{l s='inline varnishing' mod='clariprint'}">
			{foreach $vernis_en_lignes as $kvel => $vel}
			<option value="{$kvel}" {if $kvel == $product->finishing_back}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
		<optgroup label="{l s='offline varnishing' mod='clariprint'}">
			{foreach $vernis_en_reprise as $kvel => $vel}
			<option value="{$kvel}" {if $kvel == $product->finishing_back}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
		<optgroup label="{l s='varnish combination' mod='clariprint'}">
			{foreach $vernis_combines as $kvel => $vel}
			<option value="{$kvel}" {if $kvel == $product->finishing_back}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
	</select>
	<div class="clear"></div>
	<label>{l s='Available options - back' mod='clariprint'} :</label>
	<select name="{$product_key}[options][finishing_back][]" multiple size='10'>
		<option value="all" {if in_array_silent('all',$product->options->finishing_back)}selected{/if}>{l s='All' mod='clariprint'}</option>
		<optgroup label="{l s='inline varnishing' mod='clariprint'}">
			{foreach $vernis_en_lignes as $kvel => $vel}
			<option value="{$kvel}" {if in_array_silent($kvel,$product->options->finishing_back)}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
		<optgroup label="{l s='offline varnishing' mod='clariprint'}">
			{foreach $vernis_en_reprise as $kvel => $vel}
			<option value="{$kvel}" {if in_array_silent($kvel,$product->options->finishing_back)}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
		<optgroup label="{l s='varnish combination' mod='clariprint'}">
			{foreach $vernis_combines as $kvel => $vel}
			<option value="{$kvel}" {if in_array_silent($kvel,$product->options->finishing_back)}selected{/if}>{l s=$kvel|infoFinishing mod='clariprint'}</option>
			{/foreach}
		</optgroup>
	</select>
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][finishing_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->finishing_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][finishing_info_cms]" value="{$product->options->finishing_info_cms}"/>
	
</div>
</div>
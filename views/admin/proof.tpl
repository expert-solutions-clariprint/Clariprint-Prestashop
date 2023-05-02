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
	<div class="card-header">{l s='Proof' mod='clariprint'}</div>
	<div id="proof" class="proof card-block">
		<select name="{$product_key}[options][proof]">
			<option value="0" {if $product->options->proof == 0}selected{/if}>{l s='Hide' mod='clariprint'}</option>
			<option value="1" {if $product->options->proof == 1}selected{/if}>{l s='Show' mod='clariprint'}</option>
		</select>
		<div class="clear">&nbsp;</div>
		<input type="radio" id="numeric_proof" name="{$product_key}[proofing]" value="soft" {if $product->proofing != 'hard'}checked{/if}/>
			<label for="soft_proof">{l s='Soft proof' mod='clariprint'}</label>
		<div class="clear">&nbsp;</div>
		<input type="radio" id="hard_proof" name="{$product_key}[proofing]" value="hard" {if $product->proofing == 'hard'}checked{/if}/>
			<label for="hard_proof">{l s='Hard proof' mod='clariprint'} : {$harproof_cost}</label>
	</div>
</div>
<div class="card expandable">
	<div class="card-header">{l s='Justificative' mod='clariprint'}</div>
	<div id="justificative" class="justificative card-block">
		<select name="{$product_key}[options][justificative]">
			<option value="0" {if $product->options->justificative == 0}selected{/if}>{l s='Hide' mod='clariprint'}</option>
			<option value="1" {if $product->options->justificative == 1}selected{/if}>{l s='Show' mod='clariprint'}</option>
		</select>
	
		<textarea rows="5" name="quantities"></textarea>
	
	
		<div class="clear">&nbsp;</div>
		<ul>
			<li>
				<input type="radio" name="{$product_key}[extra][justificative][mode]" value="" {if !$product->extra->justificative->mode}checked{/if} for="justificative_none">
				<label for="justificative_none">{l s='without' mod='clariprint'}</label>
				<br/>
			</li>
			<li>
				<input type="radio" name="{$product_key}[extra][justificative][mode]" value="delivery" ,
	 for="justificative_none" {if $product->extra->justificative->mode == 'delivery'}checked{/if}/>
				<label for="justificative_none">{l s='send to delivery address' mod='clariprint'}</label>
				<input type="text" name="{$product_key}[extra][justificative][delivery_cost]" value="{$product->extra->justificative->delivery_cost}" />
			</li>
			<li>
				<input type="radio" name="{$product_key}[extra][justificative][mode]" value="invoice" ,
	 for="justificative_none" {if $product->extra->justificative->mode == 'invoice'}checked{/if}/>
				<label for="justificative_none">{l s='send to invoice address' mod='clariprint'}</label>
				<input type="text" name="{$product_key}[extra][justificative][invoice_cost]" value="{$product->extra->justificative->invoice_cost}" />
			</li>
			<li>
				<input type="radio" name="{$product_key}[extra][justificative][mode]" value="other" ,
	 for="justificative_none" {if $product->extra->justificative->mode == 'other'}checked{/if}/>
				<label for="justificative_none">{l s='send to other address' mod='clariprint'}</label>
				<input type="text" name="{$product_key}[extra][justificative][other_cost]" value="{$product->extra->justificative->other_cost}" />
			</li>
			<li>
				<input type="radio" name="{$product_key}[extra][justificative][mode]" value="plant" ,
	 for="justificative_none" {if $product->extra->justificative->mode == 'plant'}checked{/if}/>
				<label for="justificative_none">{l s='send to other address' mod='clariprint'}</label>
				<input type="text" name="{$product_key}[extra][justificative][other_cost]" value="{$product->extra->justificative->other_cost}" />
			</li>
		</ul>
		<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
		<input type="text" class="form-control"  name="{$product_key}[options][proof_info_cms]" value="{$product->options->proof_info_cms}"/>
	</div>
</div>
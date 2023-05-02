{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
{if $product->options->proof}
<div class="card">
	<h3 class="accordion_header card-header">{l s='Proof' mod='clariprint'} :</h3>
<div id="proof" class="proof card-block">
	<input type="radio" id="soft_proof" name="{$product_key}[proofing]" value="soft" {if $product->proofing != 'paper'}checked{/if} /><label for="soft_proof">{l s='Soft proof' mod='clariprint'}</label>
	<div class="clear">&nbsp;</div>
	<input type="radio" id="hard_proof" name="{$product_key}[proofing]" value="hard" {if $product->proofing == 'paper'}checked{/if}/><label for="hard_proof">{l s='Hard proof' mod='clariprint'} : {$harproof_cost}</label>
	<BR>
	{displayCMS cms='product-proof'}

</div>
</div>
{else}
<input type="hidden" name="{$product_key}[proofing]" value="{$product->proofing}"/>
{/if}

{if $product->options->justificative}
<div class="card">
	<h3 class="accordion_header card-header">{l s='Justificative' mod='clariprint'} :</h3>
	<div id="justificative" class="justificative card-block">
	<ul>
		<li>
			<input type="radio" name="{$product_key}[extra][justificative][mode]" value="" {if !$product->extra->justificative->mode}checked{/if} for="justificative_none">
			<label for="justificative_none">{l s='without' mod='clariprint'}</label>
			<br/>
		</li>
{*		<li>
			<input type="radio" name="{$product_key}[extra][justificative][mode]" value="delivery"  for="justificative_none" {if $product->extra->justificative->mode == 'delivery'}checked{/if}/>
			<label for="justificative_none">{l s='send to delivery address' mod='clariprint'} ({if $product->extra->justificative->delivery_cost}{displayPrice price=$product->extra->justificative->delivery_cost}{else}{l s='free' mod='clariprint'}){/if}</label>
			<input type="hidden" name="{$product_key}[extra][justificative][delivery_cost]" value="{$product->extra->justificative->delivery_cost}" />
		</li>
		<li>
			<input type="radio" name="{$product_key}[extra][justificative][mode]" value="invoice" ,
 for="justificative_none" {if $product->extra->justificative->mode == 'invoice'}checked{/if}/>
			<label for="justificative_none">{l s='send to invoice address' mod='clariprint'} ({if $product->extra->justificative->invoice_cost}{displayPrice price=$product->extra->justificative->invoice_cost}{else}{l s='free' mod='clariprint'}){/if}</label>
			<input type="hidden" name="{$product_key}[extra][justificative][invoice_cost]" value="{$product->extra-justificative->>invoice_cost}"/>
		</li>
		<li>
			<input type="radio" name="{$product_key}[extra][justificative][mode]" value="other" ,
 for="justificative_none" {if $product->extra->justificative->mode == 'other'}checked{/if}/>
			<label for="justificative_none">{l s='send to other address' mod='clariprint'} ({if $product->extra->justificative->other_cost}{displayPrice price=$product->extra->justificative->other_cost}{else}{l s='free' mod='clariprint'}){/if}</label><br/>
			<input type="hidden" name="{$product_key}[extra][justificative][other_cost]" value="{$product->extra->justificative->other_cost}" />
			<textarea name="{$product_key}[extra][justificative][address]">{$product->extra->justificative->address}</textarea>
		</li> *}
	</ul>
</div>
</div>
{else}
	<input type="hidden" name="{$product_key}[extra][justificative][mode]" value="{$product->extra->justificative->mode}"  for="justificative_none">
	<input type="hidden" name="{$product_key}[extra][justificative][delivery_cost]" value="{$product->extra->justificative->address_cost}" />
	<input type="hidden" name="{$product_key}[extra][justificative][invoice_cost]" value="{$product->extra->justificative->invoice_cost}"/>
	<input type="hidden" name="{$product_key}[extra][justificative][other_cost]" value="{$product->extra->justificative->other_cost}"/>
	<input type="hidden" name="{$product_key}[extra][justificative][address]" value="{$product->extra->justificative->address}"/>
{/if}

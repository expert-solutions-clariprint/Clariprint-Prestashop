{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*
* ADMIN 
*}

<input type="hidden" name="{$product_key}[discounts_group]" value="{$product->discounts_group}"/>
{for $m=0 to 5}
	{if $product->discounts[$m]->quantity}
		<input type="hidden" name="{$product_key}[discounts][{$m}][quantity]" value="{$product->discounts[$m]->quantity}"/>
		{if $product->discounts[$m]->value}
		<input type="hidden" name="{$product_key}[discounts][{$m}][value]" value="{$product->discounts[$m]->value}"/>
		{/if}
		{if $product->discounts[$m]->fixed}
		<input type="hidden" name="{$product_key}[discounts][{$m}][fixed]" value="{$product->discounts[$m]->fixed}"/>
		{/if}
	{/if}
{/for}

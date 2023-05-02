{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary	
*}
{if $product->options->folderdie != 'hidden'}
<div class="card clariprint-finishing">
	<h3 class="accordion_header card-header">{l s='Folder cutting die' mod='clariprint'} :</h3>
<div class="coverdie card-block">
	{if $ui_mode == 'bootstrap'}
	<script type="text/javascript">
		jQuery.widget('clariprint.clthumbnails', {
			target:null,
			_create:function() {
				this.target = $(this.element.attr('target'));
				this.element.find('div.cuttingdie').click($.proxy(this.click,this));	
			},
			click: function(evt) {
//				console.log('hoho');
				var elem = $(evt.target).closest('div');
				this.element.find('div.cuttingdie').removeClass('btn-primary');
				elem.addClass('btn-primary');
				this.target.val(elem.attr('die-index'));
				console.log(this.target.val());
			}
		});
		jQuery(function(){
			console.log('iciic');
			$('.cuttingdies').clthumbnails();
		});
	</script>
	<input type="hidden" name="{$product_key}[cuttingdie]" value="{$product->cuttingdie}" id="{$product_key}_cuttingdie"/>
	<ul class="thumbnails cuttingdies" target="#{$product_key}_cuttingdie">
		{foreach from=$folderdie item=cut}
			{if in_array_silent($cut['index'],$product->options->folder_cuttingdie)}
			<li class="span2">
				<div class="thumbnail btn {if $cut['index'] == $product->cuttingdie}btn-primary{/if} cuttingdie"  die-index="{$cut['index']}" style="height: 150px">
					<img src="/modules/clariprint/img/cutting/{$cut['index']}.png"/>
					{l s=$cut['title'] mod='clariprint'}
				</div>
			</li>
			{/if}
		{/foreach}
    </ul>
	{else}
	<table class="table table-striped table-hover">
		<thead>
			<tr>
				<th></th>
				<th>{l s='Icon' mod='clariprint'}</th>
				<th>{l s='Label' mod='clariprint'}</th>
			</tr>
		</thead>
		<tbody>
		{foreach from=$folderdie item=cut}
			{if in_array_silent($cut['index'],$product->options->folder_cuttingdie)}
			<tr>
				<td><input type="radio" value="{$cut['index']}" name="{$product_key}[cuttingdie]" {if $product->cuttingdie == $cut['index']}checked="1"{/if} id="{$product_key}_cuttingdie_{$cut.index}"/></td>
				<td><label for="{$product_key}_cuttingdie_{$cut.index}"><img src="/modules/clariprint/img/cutting/{$cut['index']}.png"/></label></td>
				<td>{l s=$cut['title'] mod='clariprint'}</td>
				<td>{l s='folder with 1 flap without leg of joining' mod='clariprint'}</td>
			</tr>
			{/if}
		{/foreach}
		</tbody>
	</table>
	{/if}
	
	{if $product->options->folder_die_shape}
		<div class="clear"></div>
		{if $ui_mode == 'bootstrap'}
			<input type="hidden" name="{$product_key}[cuttingdie_exists]" id="{$product_key}_die_cut_shape_exists" value="{(int)$product->cuttingdie_exists}" class="hole" />
			<div class="btn-group xl-radio" target="#{$product_key}_die_cut_shape_exists">
				<button class="btn" value="0">{l s='Create the die-cut shape' mod='clariprint'}</button>
				<button class="btn" value="1">{l s='The die-cut shape already exists' mod='clariprint'}</button>
			</div>
		{else}
		<label>{l s='Is the die-cut shape already made (if not a new die-cut shape will be created) ?' mod='clariprint'} :</label>
		<input type="checkbox" name="{$product_key}[cuttingdie_exists]" value="1" id="{$product_key}[cuttingdie_exists]" {if $product->cuttingdie_exists}checked{/if}/> 
		{/if}
	{else}
		<input type="hidden" name="{$product_key}[cuttingdie_exists]" value="{$product->cuttingdie_exists}" id="{$product_key}[cuttingdie_exists]"/>
	{/if}
	{if $product->options->folder_die_folded}
		<div class="clear"></div>
		<br/>
		<div class="clear"></div>
		{if $ui_mode == 'bootstrap'}
			<input type="hidden" name="{$product_key}[flat_delivery]" id="{$product_key}_flat_delivery" value="{$product->flat_delivery}" class="hole" />
			<div class="btn-group xl-radio" target="#{$product_key}_flat_delivery">
				<button class="btn" value="">{l s='Delivered folded' mod='clariprint'}</button>
				<button class="btn" value="1">{l s='Delivered <b>un</b>folded (flat)' mod='clariprint'}</button>
			</div>
		{else}
		<label>{l s='Delivered unfolded (if not: hand folding will be charged) ?' mod='clariprint'} :</label>
		<input type="checkbox" name="{$product_key}[flat_delivery]" value="1" id="{$product_key}[flat_delivery]" {if $product->flat_delivery}checked{/if}/>
		{/if}

	{else}
		<input type="hidden" name="{$product_key}[flat_delivery]" value="{$product->flat_delivery}" id="{$product_key}[flat_delivery]"/>
	{/if}
	{if $product->options->folderdie_info_cms}
		{displayCMS cms=$product->options->folderdie_info_cms}
	{else}
		{displayCMS cms='product-folderdie'}
	{/if}
	
</div>
</div>
{else}
	<input type="hidden" name="{$product_key}[cuttingdie]" value="{$product->cuttingdie}" />
	<input type="hidden" name="{$product_key}[cuttingdie_exists]" value="{$product->cuttingdie_exists}" />
	<input type="hidden" name="{$product_key}[flat_delivery]" value="{$product->flat_delivery}" />
{/if}
{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}

{assign var=bindings value=['PerfectBinding']}
{if $product->options->binding_mode}
<div class="card clariprint-binding">

	<h3 class="accordion_header card-header">{l s='Binding' mod='clariprint'} :</h3>
	<div id="binding" class="binding card-block">
		<div class="">
			{if in_array_silent('PerfectBinding',$product->options->bindings)}
			<div class="card">
				<div class="card-block">
					<input type="radio" value="PerfectBinding" name="{$product_key}[binding]" {if 'PerfectBinding' == $product->binding}checked="1"{/if}/>
					<img src="/modules/clariprint/img/bindings/perfect_binding.png"/>
					{l s='Perfect binding' mod='clariprint'}
					<p class='info'>{l s='Hot melt gluing of the cover on the fold of inside sections' mod='clariprint'}</p>
				</div>
			</div>
			{/if}
			{if in_array_silent('PerfectBindingPUR',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="PerfectBindingPUR" name="{$product_key}[binding]" {if 'PerfectBindingPUR' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/perfect_binding.png"/>
				{l s='Perfect binding PUR' mod='clariprint'}
					<p class='info'>{l s='It\'s the same as perfect binding with a glue that gives the same resisitence and finishing as sewn binding' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('SewnBinding',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="SewnBinding" name="{$product_key}[binding]" {if 'SewnBinding' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/sewn_binding.png"/>
				{l s='Sewn binding' mod='clariprint'}
					<p class='info'>{l s='The inside sections are sewn on the fold before the gluing of the cover. This type of binding is stronger than the perfect binding' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('Stitching2',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="Stitching2" name="{$product_key}[binding]" {if 'Stitching2' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/stitching_2.png"/>
				{l s='2 stitching' mod='clariprint'}
					<p class='info'>{l s='The stitches are put on the fold of the sections' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('Stitching3',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="Stitching3" name="{$product_key}[binding]" {if 'Stitching3' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/stitching_3.png"/>
				{l s='3 stitching' mod='clariprint'}
					<p class='info'>{l s='The stitches are put on the fold of the sections' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('Stitching4',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="Stitching4" name="{$product_key}[binding]" {if 'Stitching4' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/stitching_4.png"/>
				{l s='4 stitching' mod='clariprint'}
					<p class='info'>{l s='The stitches are put on the fold of the sections' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('OpenEyesStitching2',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="OpenEyesStitching2" name="{$product_key}[binding]" {if 'OpenEyesStitching2' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/openeyes_stitching_2.png"/>
				{l s='2 openeyes stitching' mod='clariprint'}
					<p class='info'>{l s='External stitches are put on the fold of the sections' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('OpenEyesStitching3',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="OpenEyesStitching3" name="{$product_key}[binding]" {if 'OpenEyesStitching3' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/openeyes_stitching_3.png"/>
				{l s='3 openeyes stitching' mod='clariprint'}
					<p class='info'>{l s='External stitches are put on the fold of the sections' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('OpenEyesStitching4',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="OpenEyesStitching4" name="{$product_key}[binding]" {if 'OpenEyesStitching4' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/openeyes_stitching_4.png"/>
				{l s='4 openeyes stitching' mod='clariprint'}
					<p class='info'>{l s='External stitches are put on the fold of the sections' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('WireO',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="WireO" name="{$product_key}[binding]" {if 'WireO' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/wireo.png"/>
				{l s='Wire\'O' mod='clariprint'}
					<p class='info'>{l s='Sheets are bound with a metal wire' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('InlineStiching',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="InlineStiching" name="{$product_key}[binding]" {if 'InlineStiching' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/stitching_2.png"/>
				{l s='Inline stitching' mod='clariprint'}
					<p class='info'>{l s='2 stitches on the fold of an inside section on web offset output' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('GluedOnFold',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="GluedOnFold" name="{$product_key}[binding]" {if 'GluedOnFold' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/gluing_fold.png"/>
				{l s='Gluing on fold' mod='clariprint'}
					<p class='info'>{l s='Glue on the fold of an inside section on web offset output' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('FreeBinding',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="FreeBinding" name="{$product_key}[binding]" {if 'FreeBinding' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/free.png"/>
				{l s='Free (without binding)' mod='clariprint'}
					<p class='info'>{l s='Sections are juste folded' mod='clariprint'}</p></div>
			</div>
			{/if}
			{if in_array_silent('Folded',$product->options->bindings)}
			<div class="card">
				<div class="card-block"><input type="radio" value="Folded" name="{$product_key}[binding]" {if 'Folded' == $product->binding}checked="1"{/if}/>
				<img src="/modules/clariprint/img/bindings/folded.png"/>
				{l s='Folded without binding' mod='clariprint'}
					<p class='info'>{l s='Folded without binding' mod='clariprint'}</p></div>
			</div>
			{/if}
		{if $product->options->binding_orientation}
		<div class="clear"></div>
		<label>{l s='Orientation'  mod='clariprint'}</label>
			<select name="{$product_key}[binding_orientation]">
				<option value="portrait" {if $product->binding_orientation == 'portrait'}selected{/if}>{l s='portrait' mod='clariprint'}</value>
				<option value="landscape" {if $product->binding_orientation == 'landscape'}selected{/if}>{l s='landscape' mod='clariprint'}</value>
			</select>
		</label>
		{else}
			<input type="hidden" name="{$product_key}[binding_orientation]" value="{$product->binding_orientation}"/>
		{/if}
	</div>
		{if $product->options->binding_info}
		<div class="alert alert-info" role="alert">{$product->options->binding_info nofilter}</div>
		{/if}
		{if $product->options->binding_info_cms}
			{displayCMS cms=$product->options->binding_info_cms}
		{else}
			{displayCMS cms='product-binding'}
		{/if}
	</div>
</div>
{else}
<input type="hidden" name="{$product_key}[binding]" value="{$product->binding}"/>
{/if}
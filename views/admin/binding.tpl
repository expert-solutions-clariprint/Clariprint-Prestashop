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
	<div class="card-header">{l s='Binding' mod='clariprint'}</div>
	<div id="binding" class="binding card-block">
		<select name="{$product_key}[options][binding_mode]">
			<option value="0" {if $product->options->binding_mode == 0}selected{/if}>{l s='Hide' mod='clariprint'}</option>
			<option value="1" {if $product->options->binding_mode == 1}selected{/if}>{l s='Show' mod='clariprint'}</option>
		</select>
		<div class="clear">&nbsp;</div>
	
		
		<table>
			<thead>
				<tr>
					<td>Default</td>
					<td>Available</td>
					<td>Icon</td>
					<td>{l s='name' mod='clariprint'}</td>
					<td>{l s='description' mod='clariprint'}</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><input type="radio" value="PerfectBinding" name="{$product_key}[binding]" {if 'PerfectBinding' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="PerfectBinding" name="{$product_key}[options][bindings][]" {if in_array_silent('PerfectBinding',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/perfect_binding.png"/></td>
					<td>{l s='Perfect binding' mod='clariprint'}</td>
					<td>{l s='Hot melt gluing of the cover on the fold of inside sections' mod='clariprint'}</td>
				</tr>
				<tr>
					<td><input type="radio" value="PerfectBindingPUR" name="{$product_key}[binding]" {if 'PerfectBindingPUR' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="PerfectBindingPUR" name="{$product_key}[options][bindings][]" {if in_array_silent('PerfectBindingPUR',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/perfect_binding.png"/></td>
					<td>{l s='Perfect binding PUR' mod='clariprint'}</td>
					<td><p class='info'>{l s='It\'s the same as perfect binding with a glue that gives the same resisitence and finishing as sewn binding' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="SewnBinding" name="{$product_key}[binding]" {if 'SewnBinding' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="SewnBinding" name="{$product_key}[options][bindings][]" {if in_array_silent('SewnBinding',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/sewn_binding.png"/></td>
					<td>{l s='Sewn binding' mod='clariprint'}</td>
					<td><p class='info'>{l s='The inside sections are sewn on the fold before the gluing of the cover. This type of binding is stronger than the perfect binding' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="Stitching2" name="{$product_key}[binding]" {if 'Stitching2' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="Stitching2" name="{$product_key}[options][bindings][]" {if in_array_silent('Stitching2',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/stitching_2.png"/></td>
					<td>{l s='2 stitching' mod='clariprint'}</td>
					<td><p class='info'>{l s='The stitches are put on the fold of the sections' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="Stitching3" name="{$product_key}[binding]" {if 'Stitching3' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="Stitching3" name="{$product_key}[options][bindings][]" {if in_array_silent('Stitching3',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/stitching_3.png"/></td>
					<td>{l s='3 stitching' mod='clariprint'}</td>
					<td><p class='info'>{l s='The stitches are put on the fold of the sections' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="Stitching4" name="{$product_key}[binding]" {if 'Stitching4' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="Stitching4" name="{$product_key}[options][bindings][]" {if in_array_silent('Stitching4',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/stitching_4.png"/></td>
					<td>{l s='4 stitching' mod='clariprint'}</td>
					<td><p class='info'>{l s='The stitches are put on the fold of the sections' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="OpenEyesStitching2" name="{$product_key}[binding]" {if 'OpenEyesStitching2' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="OpenEyesStitching2" name="{$product_key}[options][bindings][]" {if in_array_silent('OpenEyesStitching2',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/openeyes_stitching_2.png"/></td>
					<td>{l s='2 openeyes stitching' mod='clariprint'}</td>
					<td><p class='info'>{l s='External stitches are put on the fold of the sections' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="OpenEyesStitching3" name="{$product_key}[binding]" {if 'OpenEyesStitching3' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="OpenEyesStitching3" name="{$product_key}[options][bindings][]" {if in_array_silent('OpenEyesStitching3',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/openeyes_stitching_3.png"/></td>
					<td>{l s='3 openeyes stitching' mod='clariprint'}</td>
					<td><p class='info'>{l s='External stitches are put on the fold of the sections' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="OpenEyesStitching4" name="{$product_key}[binding]" {if 'OpenEyesStitching4' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="OpenEyesStitching4" name="{$product_key}[options][bindings][]" {if in_array_silent('OpenEyesStitching4',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/openeyes_stitching_4.png"/></td>
					<td>{l s='4 openeyes stitching' mod='clariprint'}</td>
					<td><p class='info'>{l s='External stitches are put on the fold of the sections' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="WireO" name="{$product_key}[binding]" {if 'WireO' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="WireO" name="{$product_key}[options][bindings][]" {if in_array_silent('WireO',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/wireo.png"/></td>
					<td>{l s='Wire\'O' mod='clariprint'}</td>
					<td><p class='info'>{l s='Sheets are bound with a metal wire' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="InlineStiching" name="{$product_key}[binding]" {if 'InlineStiching' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="WireO" name="{$product_key}[options][bindings][]" {if in_array_silent('InlineStiching',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/stitching_2.png"/></td>
					<td>{l s='Inline stitching' mod='clariprint'}</td>
					<td><p class='info'>{l s='2 stitches on the fold of an inside section on web offset output' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="GluedOnFold" name="{$product_key}[binding]" {if 'GluedOnFold' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="GluedOnFold" name="{$product_key}[options][bindings][]" {if in_array_silent('GluedOnFold',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/gluing_fold.png"/></td>
					<td>{l s='Gluing on fold' mod='clariprint'}</td>
					<td><p class='info'>{l s='Glue on the fold of an inside section on web offset output' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="FreeBinding" name="{$product_key}[binding]" {if 'FreeBinding' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="FreeBinding" name="{$product_key}[options][bindings][]" {if in_array_silent('FreeBinding',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/free.png"/></td>
					<td>{l s='Free (without binding)' mod='clariprint'}</td>
					<td><p class='info'>{l s='Sections are juste folded' mod='clariprint'}<p/></td>
				</tr>
				<tr>
					<td><input type="radio" value="Folded" name="{$product_key}[binding]" {if 'Folded' == $product->binding}checked="1"{/if}/></td>
					<td><input type="checkbox" value="Folded" name="{$product_key}[options][bindings][]" {if in_array_silent('Folded',$product->options->bindings)}checked="1"{/if}/></td>
					<td><img src="/modules/clariprint/img/bindings/folded.png"/></td>
					<td>{l s='Folded without binding' mod='clariprint'}</td>
					<td><p class='info'>{l s='Folded without binding' mod='clariprint'}<p/></td>
				</tr>
			</tbody>
		</table>
		<div class="clear"></div>
		<label for="{$product_key}[options][binding_orientation]">{l s='Show orientation options' mod='clariprint'} : </label>
		<input type="checkbox" name="{$product_key}[options][binding_orientation]" value="1" id="{$product_key}[options][binding_orientation]" {if ($product->options->binding_orientation)}checked{/if}/> 
		<div class="clear"></div>
		<label>{l s='Orientation'  mod='clariprint'}</label>
			<select name="{$product_key}[binding_orientation]">
				<option value="auto">{l s='auto' mod='clariprint'}</value>
				<option value="portrait" {if $product->binding_orientation == 'portrait'}selected{/if}>{l s='portrait' mod='clariprint'}</value>
				<option value="landscape" {if $product->binding_orientation == 'landscape'}selected{/if}>{l s='landscape' mod='clariprint'}</value>
			</select>
		</label>
		<div class="clear"></div>
		{l s='User doc' mod='clariprint'}<br/>
		<textarea name="{$product_key}[options][binding_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->binding_info|htmlentitiesUTF8}</textarea>

		<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
		<input type="text" class="form-control"  name="{$product_key}[options][binding_info_cms]" value="{$product->options->binding_info_cms}"/>
		
		<div class="clear"></div>
	</div>
</div>

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
	<div class="card-header">{l s='Scoring' mod='clariprint'}</div>
	<div class="card-block">
		{l s='Show' mod='clariprint'} : <input type="checkbox" value="scoring" name="{$product_key}[options][makeready][]" {if 		in_array_silent('scoring',$product->options->makeready)}checked{/if} />
		<div class="clear"></div>
		<ul>
			<li><input type="radio" name="{$product_key}[creasing]" value="0" {if $product->creasing == 0}checked{/if}/>{l s='Automatic' mod='clariprint'}</li>
			<li><input type="radio" name="{$product_key}[creasing]" value="1" {if $product->creasing == 1}checked{/if}/>{l s='Creasing (inline OR on typo press)' mod='clariprint'}</li>
			<li><input type="radio" name="{$product_key}[creasing]" value="2" {if $product->creasing == 2}checked{/if}/>{l s='Creasing on typo press' mod='clariprint'}</li>
		</ul>
		<p class="info">{l s='Automatic creasing : creasing will depend on each suppliers setup' mod='clariprint'}</p>
		<div class="clear"></div>
		{l s='User doc' mod='clariprint'}<br/>
		<textarea name="{$product_key}[options][scoring_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->scoring_info|htmlentitiesUTF8}</textarea>
		<div class="clear"></div>
		<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
		<input type="text" class="form-control"  name="{$product_key}[options][scoring_info_cms]" value="{$product->options->scoring_info_cms}"/>
	</div>
</div>
<div class="card expandable">
	<div class="card-header">{l s='Embossing' mod='clariprint'}</div>
	<div class="card-block">
		<div class="form-check">
			<label class="form-check-label">
				<input class="form-check-input" type="checkbox" value="embossing" name="{$product_key}[options][makeready][]" {if in_array_silent('embossing',$product->options->makeready)}checked{/if} aria-label="{l s='Show' mod='clariprint'}" />
				{l s='Show' mod='clariprint'}
		</label>
		</div>
		

		<div class="form-check">
			<label class="form-check-label">
				{l s='Show embossing dimensions' mod='clariprint'}
				<input type="checkbox" name="{$product_key}[options][embossing_dimensions]" value="show" id="{$product_key}_options_embossing_dimensions"
				{if $product->options->embossing_dimensions == "show"}checked{/if} />
			</label>
		</div>
	
	<div class="row">
				{foreach from=['recto','verso'] item=side}
		<div class="col-lg-6">
			<div class="input-group">
				<span class="input-group-addon">
					<input type="checkbox" class="form-check-input" name="{$product_key}[embossing][{$side}][side]" value="recto" {if $product->embossing->$side->side}checked{/if}{if $product->embossing_side == 'recto'}checked{/if} />
				</span>
				<span class="input-group-addon">{l s=$side mod='clariprint'}</span>
			</div>
-			<h4>{l s='Position' mod='clariprint'}</h4>
			
			<div class="input-group">
				<span class="input-group-addon">{l s='From top' mod='clariprint'}</span>
				<input class="form-control" name="{$product_key}[embossing][{$side}][top]" type="text" value="{$product->embossing->$side->top}" placeholder="{l s='default : full size'}"/>
				<span class="input-group-addon">cm</span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">{l s='From left' mod='clariprint'}</span>
				<input class="form-control" name="{$product_key}[embossing][{$side}][left]" type="text" value="{$product->embossing->$side->left}" placeholder="{l s='default : full size'}"/>
				<span class="input-group-addon">cm</span>
			</div>
			<h4>{l s='Size' mod='clariprint'}</h4>
			<div class="input-group">
				<span class="input-group-addon">{l s='width' mod='clariprint'}</span>
				<input class="form-control" name="{$product_key}[embossing][{$side}][width]" type="text" value="{$product->embossing->$side->top}" placeholder="{l s='default : full size'}"/>
				<span class="input-group-addon">cm</span>
			</div>
			<div class="input-group">
				<span class="input-group-addon">{l s='height' mod='clariprint'}</span>
				<input class="form-control" name="{$product_key}[embossing][{$side}][height]" type="text" value="{$product->embossing->$side->left}" placeholder="{l s='default : full size'}"/>
				<span class="input-group-addon">cm</span>
			</div>
					
		</div>
				{/foreach}
	</div>
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][embossing_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->embossing_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][embossing_info_cms]" value="{$product->options->embossing_info_cms}"/>
</div>
</div>
<div class="card expandable">
	<div class="card-header">{l s='Cutting die' mod='clariprint'}</div>
	<div class="card-block">
	{l s='Show' mod='clariprint'} : <input type="checkbox" value="cutting" name="{$product_key}[options][makeready][]"  {if in_array_silent('cutting',$product->options->makeready)}checked{/if}/>
	<div class="clear"></div>
	<ul>
		<li>{l s='Is the die cutting form already available ?' mod='clariprint'}
		<select name="{$product_key}[cutting_die_exists]">
			<option value="0">{l s='no (a new one will be made)' mod='clariprint'}</option>
			<option value="1" {if $product->cutting_die_exists}selected{/if}>{l s='yes' mod='clariprint'}</option>
		</select></li>
		<li>{l s='Form factor' mod='clariprint'} :
			<ul>
				<li><input type="radio" name="{$product_key}[cutting_die]" value="0" {if $product->cutting_die == 0}checked{/if}/>{l s='no cutting die' mod='clariprint'}</li>
				<li><input type="radio" name="{$product_key}[cutting_die]" value="23" {if $product->cutting_die == 23}checked{/if}/>{l s='Single sheets / folder with  window die cut' mod='clariprint'} forme libre simple (affiche, chevalet, totem, découpe droite)</li>
				<li><input type="radio" name="{$product_key}[cutting_die]" value="24" {if $product->cutting_die == 24}checked{/if}/>{l s='Single sheets / folder with rounded die cut' mod='clariprint'} forme libre normale (meuble simple) </li>
				<li><input type="radio" name="{$product_key}[cutting_die]" value="25" {if $product->cutting_die == 25}checked{/if}/>{l s='Single sheets / folder with rounded corners' mod='clariprint'} forme complexe (meuble complexe, chemises, amalgame)</li>
				<li><input type="radio" name="{$product_key}[cutting_die]" value="26" {if $product->cutting_die == 26}checked{/if}/>{l s='Single sheets / folder with slots' mod='clariprint'} forme spéciale (porte échantillons, multiposes)</li>

				<li><input type="radio" name="{$product_key}[cutting_die]" value="27" {if $product->cutting_die == 27}checked{/if}/>{l s='numerique' mod='clariprint'} forme libre simple (affiche, chevalet, totem, découpe droite)</li>
				<li><input type="radio" name="{$product_key}[cutting_die]" value="28" {if $product->cutting_die == 28}checked{/if}/>{l s='numerique' mod='clariprint'} forme libre normale (meuble simple) </li>
				<li><input type="radio" name="{$product_key}[cutting_die]" value="29" {if $product->cutting_die == 29}checked{/if}/>{l s='numerique' mod='clariprint'} forme complexe (meuble complexe, chemises, amalgame)</li>
				<li><input type="radio" name="{$product_key}[cutting_die]" value="30" {if $product->cutting_die == 30}checked{/if}/>{l s='numerique' mod='clariprint'} forme spéciale (porte échantillons, multiposes</li>
				
				
				
			</ul>
		</li>
	</ul>		
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][cutting_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->cutting_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][cutting_info_cms]" value="{$product->options->cutting_info_cms}"/>
</div>
</div>
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
</div>
</div>
<div class="card expandable">
	<div class="card-header">{l s='Perforation / numbering' mod='clariprint'}</div>
	<div class="card-block numbering">
	{l s='Show' mod='clariprint'} : <input type="checkbox" value="perfo" name="{$product_key}[options][makeready][]"  {if in_array_silent('perfo',$product->options->makeready)}checked{/if} />
	<div class="clear"></div>
	<ul>
		<li><input type="checkbox" name="{$product_key}[linear_perforating]" value="1" {if $product->linear_perforating == 1}checked{/if}/>{l s='Linear perforating' mod='clariprint'}</li>
		<li><input type="checkbox" name="{$product_key}[numbering]" value="1" {if $product->numbering == 1}checked{/if}/>{l s='numbering' mod='clariprint'}</li>
	</ul>
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][perforating_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->perforating_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][perforating_info_cms]" value="{$product->options->perforating_info_cms}"/>
</div>
</div>
{***************************************** GILDING *********************************************************}

<div class="card expandable">
	<div class="card-header">{l s='Gilding' mod='clariprint'}</div>
	<div class="gilding card-block">
		<label>{l s='Show' mod='clariprint'} : <input type="checkbox" value="gilding" name="{$product_key}[options][makeready][]" {if in_array_silent('gilding',$product->options->makeready)}checked{/if} /></label>
	<div class="clear"></div>

	<label type="checkbox">
		{l s='Show gilding dimensions' mod='clariprint'}
		<input type="checkbox" name="{$product_key}[options][gilding_dimensions]" value="show" id="{$product_key}_options_gilding_dimensions"
			{if $product->options->gilding_dimensions == "show"}checked{/if} />
	</label>
	<div class="clear"></div>
	<label>{l s='Show 3D' mod=''}
		<input name="{$product_key}[options][gilding3d]" value="show" type="checkbox" {if ($product->options->gilding3d == 'show')}checked{/if}/> 
	</label>
	<div class="clear"></div>
	<label type="checkbox">
		{l s='Available Materials' mod='clariprint'} : 
		<select name="{$product_key}[options][gilding_materials][]" multiple>
			{foreach from=$gilding_materials item=name key=code}
			<option value="{$code}" {if in_array_silent($code,$product->options->gilding_materials)} selected{/if}>{$name}</option>
			{/foreach}
		</select>
	</label>
	<div class="clear"></div>
	
	<h4>{l s='Default values' mod='clariprint'}</h4>
	<div class="row">
		{foreach from=['recto','verso'] item=side}
		<div class="col-lg-6">
			<div class="card">
				<div class="card-block">


					<div class="form-check">
					 	<label class="form-check-label">
							<input type="checkbox" class="form-check-input"
									name="{$product_key}[gilding][{$side}][side]"
									value="{$side}"
									{if $product->gilding->$side->side}checked{/if}{if $product->gilding_side == $side}checked{/if} />
									{l s=$side mod='clariprint'}
						</label>
					</div>
					<div class="form-check">
						<select name="{$product_key}[gilding][{$side}][available]">
							<option value="available">{l s="available"}</option>
							<option value="no" {if $product->gilding->$side->available == 'no'}selected{/if}>{l s="not available"}</option>
						</select>
					</div>
					<div class="clear"></div>
					<label>{l s='Material' mod='clariprint'}
							<select name="{$product_key}[gilding][{$side}][material]">
								<option value="">{l s='None' mod='clariprint'}</option>
								{foreach from=$gilding_materials item=name key=code}
								<option value="{$code}" {if $code == $product->gilding->$side->material}selected{/if}>{$name}</option>
								{/foreach}
							</select>
					</label>
					<div class="clear"></div>
					<label class="checkbox">{l s='3D' mod='clariprint'}
						<input type="checkbox" name="{$product_key}[gilding][{$side}][gilding3d]" value="1" {if $product->gilding->$side->gilding3d}checked{/if}/>
					</label>
					
					<h4>{l s='Position' mod='clariprint'}</h4>
					<div class="input-group">
						<span class="input-group-addon">{l s='From top' mod='clariprint'}</span>
						<input class="form-control" name="{$product_key}[gilding][{$side}][top]" type="text" value="{$product->gilding->$side->top}" placeholder="{l s='default : full size'}"/>
						<span class="input-group-addon">cm</span>
					</div>
					<div class="input-group">
						<span class="input-group-addon">{l s='From left' mod='clariprint'}</span>
						<input class="form-control" name="{$product_key}[gilding][{$side}][left]" type="text" value="{$product->gilding->$side->left}" placeholder="{l s='default : full size'}"/>
						<span class="input-group-addon">cm</span>
					</div>
					<h4>{l s='Size' mod='clariprint'}</h4>
					<div class="input-group">
						<span class="input-group-addon">{l s='width' mod='clariprint'}</span>
						<input class="form-control" name="{$product_key}[gilding][{$side}][width]" type="text" value="{$product->gilding->$side->top}" placeholder="{l s='default : full size'}"/>
						<span class="input-group-addon">cm</span>
					</div>
					<div class="input-group">
						<span class="input-group-addon">{l s='height' mod='clariprint'}</span>
						<input class="form-control" name="{$product_key}[gilding][{$side}][height]" type="text" value="{$product->gilding->$side->left}" placeholder="{l s='default : full size'}"/>
						<span class="input-group-addon">cm</span>
					</div>
				</div>
			</div>
		</div>		
		{/foreach}
	</div>
	<div class="clear"></div>
	{l s='User doc' mod='clariprint'}<br/>
	<textarea name="{$product_key}[options][gilding_info]" class='clariprint_rte' rows="8" cols="40">{$product->options->gilding_info|htmlentitiesUTF8}</textarea>
	<div class="clear"></div>
	<label class="form-control-label" for="">{l s='Aide CMS' mod="clariprint"}</label>
	<input type="text" class="form-control"  name="{$product_key}[options][gilding_info_cms]" value="{$product->options->gilding_info_cms}"/>
</div>
</div>

{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}	
{if true}
	{if in_array_silent('scoring',$product->options->makeready)}
	<div class="card">
		<h3 class="card-header">{l s='Scoring' mod='clariprint'}</h3>
		<div class="scoring card-block">
			<div class="form-check">
				<label class="form-check-label">
					<input type="radio" class="form-check-input" name="{$product_key}[creasing]" id="{$product_key}_creasing" value="0" {if !$product->creasing}checked{/if}/>
					{l s='Automatic' mod='clariprint'}
				</label>
			</div>
			<div class="form-check">
				<label class="form-check-label">
					<input type="radio" class="form-check-input" name="{$product_key}[creasing]" id="{$product_key}_creasing" value="1" {if (int)$product->creasing == 1}checked{/if}/>
					{l s='Creasing (inline OR on typo press)' mod='clariprint'}
				</label>
			</div>
			<div class="form-check">
				<label class="form-check-label">
					<input type="radio" class="form-check-input" name="{$product_key}[creasing]" id="{$product_key}_creasing" value="2" {if (int)$product->creasing == 2}checked{/if}/>
					{l s='Creasing on typo press' mod='clariprint'}
				</label>
			</div>
			{if $product->options->scoring_info}
			<div class="alert alert-info" role="alert">{$product->options->scoring_info nofilter}</div>
			{/if}
			{displayCMS cms='product-scoring'}
		</div>
	</div>
	{else}
		<input type="hidden" name="{$product_key}[creasing]" value="{$product->creasing}" />
	{/if}

	{if in_array_silent('embossing',$product->options->makeready)}
	<div class="card">
		<h3 class="card-header">{l s='Embossing' mod='clariprint'}</h3>		
		<div class="embossing container card-block">
			<div class="row">
				{foreach from=['recto','verso'] item=side}
				{$lkey=uniqid('embossing')}
				<div class="col-md-6">
					<div class="form-check">
						<label class="form-check-label">
							<input type="checkbox" class="form-check-input" visibility-control="#{$lkey}_opt"  name="{$product_key}[embossing][{$side}][side]" value="recto" {if $product->embossing->$side->side == $side}checked{/if} {if $product->embossing_side == $side}checked{/if} />
							{l s=$side mod="clariprint"}
						</label>
					</div>
					{if $product->options->embossing_dimensions == 'show'}
					<div class="dimensions" id="{$lkey}_opt">
						<h4>{l s="Position" mod="clariprint"}</h4>

						<div class="input-group">
							<span class="input-group-addon">{l s='From top' mod='clariprint'}</span>
							<input class="form-control" id="appendedPrependedInput" name="{$product_key}[embossing][{$side}][top]" type="text" value="{$product->embossing->$side->top}" placeholder="{l s='default : full size'}"/>
							<span class="input-group-addon">cm</span>
						</div>
						<div class="input-group">
							<span class="input-group-addon">{l s='From left' mod='clariprint'}</span>
							<input class="form-control" id="appendedPrependedInput" name="{$product_key}[embossing][{$side}][left]" type="text" value="{$product->embossing->$side->left}" placeholder="{l s='default : full size'}"/>
							<span class="input-group-addon">cm</span>
						</div>
						<h4>{l s="Size" mod="clariprint"}</h4>
						<div class="input-group">
							<span class="input-group-addon">{l s='width' mod='clariprint'}</span>
							<input class="form-control" id="appendedPrependedInput" name="{$product_key}[embossing][{$side}][width]" type="text" value="{$product->embossing->$side->top}" placeholder="{l s='default : full size'}"/>
							<span class="input-group-addon">cm</span>
						</div>
						<div class="input-group">
							<span class="input-group-addon">{l s='height' mod='clariprint'}</span>
							<input class="form-control" id="appendedPrependedInput" name="{$product_key}[embossing][{$side}][height]" type="text" value="{$product->embossing->$side->left}" placeholder="{l s='default : full size'}"/>
							<span class="input-group-addon">cm</span>
						</div>
					</div>
					{elseif $product->embossing->$side->side == $side}
						<input type="hidden" name="{$product_key}[embossing][{$side}][side]" value="{$product->embossing->$side->side}" />
						<input type="hidden" name="{$product_key}[embossing][{$side}][top]" value="{$product->embossing->$side->top}" />
						<input type="hidden" name="{$product_key}[embossing][{$side}][left]" value="{$product->embossing->$side->left}" />
						<input type="hidden" name="{$product_key}[embossing][{$side}][height]" value="{$product->embossing->$side->height}" />
						<input type="hidden" name="{$product_key}[embossing][{$side}][width]" value="{$product->embossing->$side->width}" />
					{/if}
				</div>
			{/foreach}
			</div>
			{if $product->options->embossing_info}
			<div class="alert alert-info" role="alert">{$product->options->embossing_info nofilter}</div>
			{/if}
			{if $product->options->embossing_info_cms}
				{displayCMS cms=$product->options->embossing_info_cms}
			{else}
				{displayCMS cms='product-embossing'}
			{/if}
		</div>	
	</div>
	{else}
		{if is_object($product->embossing)}
			{foreach from=(array)$product->embossing item=emb key=key}
			<input type="hidden" name="{$product_key}[embossing][$key][side]" value="{$emb->side}" />
			<input type="hidden" name="{$product_key}[embossing][$key][left]" value="{$emb->left}" />
			<input type="hidden" name="{$product_key}[embossing][$key][top]" value="{$emb->top}" />
			<input type="hidden" name="{$product_key}[embossing][$key][width]" value="{$emb->width}" />
			<input type="hidden" name="{$product_key}[embossing][$key][height]" value="{$emb->height}" />
			{/foreach}
		{else}
			<input type="hidden" name="{$product_key}[embossing]" value="{$product->embossing}" />
			<input type="hidden" name="{$product_key}[embossing_side]" value="{$product->embossing_side}" />
			<input type="hidden" name="{$product_key}[embossing_top]" value="{$product->embossing_top}" />
			<input type="hidden" name="{$product_key}[embossing_left]" value="{$product->embossing_left}" />
			<input type="hidden" name="{$product_key}[embossing_height]" value="{$product->embossing_height}" />
			<input type="hidden" name="{$product_key}[embossing_width]" value="{$product->embossing_width}" />
		{/if}
	{/if}
	
	{if in_array_silent('gilding',$product->options->makeready)}
	<div class="card">
		<h3 class="card-header">{l s='Gilding' mod='clariprint'}</h3>
		<div class="gliding card-block">
			<div class="row">
				{foreach from=['recto','verso'] item=side}
					{if ($product->gilding->$side->available != 'no')}
					{$lkey=uniqid('gilding')}
					<code style="display:none">
						{print_r($product->gilding->$side)}
					</code>

					<div class="col-md-6">
						<div class="form-check">
							<label class="form-check-label">
								<input class="form-check-input"
										visibility-control="#{$lkey}_opt"
										type="checkbox"
										name="{$product_key}[gilding][{$side}][side]"
										value="{$side}"
										{if $product->gilding->$side->side}checked{/if}
										{if $product->gilding_side == $side}checked{/if} />
								{l s=$side mod='clariprint'}
							</label>
						</div>
						<div id="{$lkey}_opt">
						
						{if isset($product->options->gilding_materials)}
						<div class="form-group">
							<label for="{$product_key}[gilding][{$side}][material]">{l s='Material' mod='clariprint'}</label>
							<select name="{$product_key}[gilding][{$side}][material]" id="{$product_key}[gilding][{$side}][material]" class="form-control">
								{foreach from=$product->options->gilding_materials item=mat}
								<option value="{$mat}" {if isset($product->gilding->$side->material)}{if $product->gilding->$side->material == $mat}selected{/if}{/if}>{l s=$mat mod='clariprint'}</option>
								{/foreach}
							</select>
						</div>
						{else}
							<input type="hidden" name="{$product_key}[gilding][{$side}][material]" value="{if isset($product->gilding->$side->material)}{$product->gilding->$side->material}{/if}"/>
						{/if}
					
					{if ($product->options->gilding3d == 'show')}
						<div class="form-check">
							<label class="form-check-label">
								<input type="checkbox" class="form-check-input" name="{$product_key}[gilding][{$side}][g3D]" value="1" {if $product->gilding->$side->g3D}checked{/if} />
								{l s='3D Gilding' mod='clariprint'}
							</label>
						</div>
					{else}
						<input name="{$product_key}[gilding][{$side}][gilding3d]" value="{$product->gilding->$side->g3D}" type="hidden"/>
					{/if}

					{if $product->options->gilding_dimensions == 'show'}
					<h4>{l s="Position" mod="clariprint"}</h4>
					<div class="input-prepend input-append">
						<span class="add-on">{l s='From top' mod='clariprint'}</span>
						<input class="span3" id="appendedPrependedInput" name="{$product_key}[gilding][{$side}][top]" type="text" value="{$product->gilding->$side->top}" placeholder="{l s='default : full size'}"/>
						<span class="add-on">cm</span>
					</div>
					<div class="input-prepend input-append">
						<span class="add-on">{l s='From left' mod='clariprint'}</span>
						<input class="span3" id="appendedPrependedInput" name="{$product_key}[gilding][{$side}][left]" type="text" value="{$product->gilding->$side->left}" placeholder="{l s='default : full size'}"/>
						<span class="add-on">cm</span>
					</div>
					<h4>{l s="Size" mod="clariprint"}</h4>
					<div class="input-prepend input-append">
						<span class="add-on">{l s='width' mod='clariprint'}</span>
						<input class="span3" id="appendedPrependedInput" name="{$product_key}[gilding][{$side}][width]" type="text" value="{$product->gilding->$side->top}" placeholder="{l s='default : full size'}"/>
						<span class="add-on">cm</span>
					</div>
					<div class="input-prepend input-append">
						<span class="add-on">{l s='height' mod='clariprint'}</span>
						<input class="span3" id="appendedPrependedInput" name="{$product_key}[gilding][{$side}][height]" type="text" value="{$product->gilding->$side->left}" placeholder="{l s='default : full size'}"/>
						<span class="add-on">cm</span>
					</div>
					{/if}
					</div>
					
				</div>
				{/if}
				{/foreach}
			</div>
			{if $product->options->gilding_info}
			<div class="alert alert-info" role="alert">{$product->options->gilding_info nofilter}</div>
			{/if}
			{if $product->options->gilding_info_cms}
				{displayCMS cms=$product->options->gilding_info_cms}
			{else}
				{displayCMS cms='product-gilding'}
			{/if}
		</div>
	</div>
	{else}
		{if is_object($product->gilding)}
			{foreach from=(array)$product->gilding item=gilding key=side}
		<input type="hidden" name="{$product_key}[gilding][{$side}][side]" value="{$gilding->size}" />
		<input type="hidden" name="{$product_key}[gilding][{$side}][gilding3d]" value="{$gilding->gilding3d}" />
		<input type="hidden" name="{$product_key}[gilding][{$side}][material]" value="{$gilding->material}" />
		<input type="hidden" name="{$product_key}[gilding][{$side}][top]" value="{$gilding->top}" />
		<input type="hidden" name="{$product_key}[gilding][{$side}][left]" value="{$gilding->left}" />
		<input type="hidden" name="{$product_key}[gilding][{$side}][height]" value="{$gilding->height}" />
		<input type="hidden" name="{$product_key}[gilding][{$side}][width]" value="{$gilding->width}" />
			{/foreach}
		{else}
		<input type="hidden" name="{$product_key}[gilding]" value="{$product->gilding}" />
		<input type="hidden" name="{$product_key}[gilding_side]" value="{$product->gilding_side}" />
		<input type="hidden" name="{$product_key}[gilding_top]" value="{$product->gilding_top}" />
		<input type="hidden" name="{$product_key}[gilding_left]" value="{$product->gilding_left}" />
		<input type="hidden" name="{$product_key}[gilding_height]" value="{$product->gilding_height}" />
		<input type="hidden" name="{$product_key}[gilding_width]" value="{$product->gilding_width}" />
		<input type="hidden" name="{$product_key}[gilding3d]" value="{$product->gilding3d}" />
		{/if}
	{/if}

	{if in_array_silent('cutting',$product->options->makeready)}
	<div class="card">
		<h3 class="card-header">{l s='Cutting die' mod='clariprint'}</h3>
		<div class="cuttingdie card-block">
			<input type="hidden" name="__{$product_key}[cutting_die]" id="{$product_key}_cutting_die" value="{(int)$product->cutting_die}"/>
			<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input"
						 visibility-control="#pop" 
						name="{$product_key}[cutting_die]"
						type="radio" value="" {if !(int)$product->cutting_die}checked{/if}/>
					{l s='Without cutting die' mod='clariprint'}
				</label>
			</div>
			<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input"
						 visibility-control="#pop" 
						name="{$product_key}[cutting_die]"
						type="radio" value="23" {if (int)$product->cutting_die == 23}checked{/if}/>
					{l s='Use a cutting die' mod='clariprint'}
				</label>
			</div>
			<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input"
						 visibility-control="#pop" 
						name="{$product_key}[cutting_die]"
						type="radio" value="24" {if (int)$product->cutting_die == 234}checked{/if}/>
					{l s='Use a cutting die with round cutout' mod='clariprint'}
				</label>
			</div>
			<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input"
						 visibility-control="#pop" 
						name="{$product_key}[cutting_die]"
						type="radio" value="25" {if (int)$product->cutting_die == 25}checked{/if}/>
					{l s='Use a cutting die with round corner' mod='clariprint'}
				</label>
			</div>
			
			<div id="pop">
				<div class="form-check">
					<label class="form-check-label">
						<input class="form-check-input"
							name="{$product_key}[cutting_die_exists]"
							type="radio" value="0" {if (int)$product->cutting_die_exists == 0}checked{/if}/>
						{l s='The die cutting do not exists, we need to built it' mod='clariprint'}
					</label>
				</div>
				<div class="form-check">
					<label class="form-check-label">
						<input class="form-check-input"
							name="{$product_key}[cutting_die_exists]"
							type="radio" value="1" {if (int)$product->cutting_die_exists == 1}checked{/if}/>
						{l s='The die cutting form already available' mod='clariprint'}
					</label>
				</div>
			</div>
			{if $product->options->cutting_info}
			<div class="alert alert-info" role="alert">{$product->options->cutting_info nofilter}</div>
			{/if}
			{if $product->options->cutting_info_cms}
				{displayCMS cms=$product->options->cutting_info_cms}
			{else}
				{displayCMS cms='product-cutting'}
			{/if}
		</div>
	</div>
	{else}
		<input type="hidden" name="{$product_key}[cutting_die_exists]" value="{$product->cutting_die_exists}" />
		<input type="hidden" name="{$product_key}[cutting_die]"  value="{$product->cutting_die}" />
	{/if}
	{if in_array_silent('holes',$product->options->makeready)}
	<div class="card">
		<h3 class="card-header">{l s='Holes' mod='clariprint'}</h3>
		<div class="holes card-block">
			{foreach from=[0,1,2,4] item=n}
				<div class="form-check">
					<label class="form-check-label">
						<input class="form-check-input"
							name="{$product_key}[holes]"
							type="radio" value="{$n}" {if (int)$product->holes == $n}checked{/if}/>
							{if $n == 0}{l s='No punch(es)' mod='clariprint'}
							{elseif $n == 1}{l s='1 file hole' mod='clariprint'}
							{elseif $n == 2}{l s='2 file hole' mod='clariprint'}
							{elseif $n == 4}{l s='4 file hole' mod='clariprint'}
							{/if}
					</label>
				</div>
			{/foreach}
			{if $product->options->holes_info}
			<div class="alert alert-info" role="alert">{$product->options->holes_info nofilter}</div>
			{/if}		
			{if $product->options->holes_info_cms}
				{displayCMS cms=$product->options->holes_info_cms}
			{else}
				{displayCMS cms='product-holes'}
			{/if}
		</div>
	</div>
	{else}
		<input type="hidden" name="{$product_key}[holes]" value="{$product->holes}" />
	{/if}
	{if in_array_silent('perfo',$product->options->makeready)}
	<div class="card">
		<h3 class="card-header">{l s='Perforation / numbering' mod='clariprint'}</h3>
		<div class="perforating card-block">
			<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input"
						name="{$product_key}[linear_perforating]"
						type="checkbox" value="1" {if (int)$product->linear_perforating == 1}checked{/if}/>
						{l s='Linear perforating' mod='clariprint'}
				</label>
			</div>
			<div class="form-check">
				<label class="form-check-label">
					<input class="form-check-input"
						name="{$product_key}[numbering]"
						type="checkbox" value="1" {if (int)$product->numbering == 1}checked{/if}/>
						{l s='numbering' mod='clariprint'}
				</label>
			</div>
			{if $product->options->perforating_info}
			<div class="alert alert-info" role="alert">{$product->options->perforating_info nofilter}</div>
			{/if}
			{if $product->options->perforating_info_cms}
				{displayCMS cms=$product->options->perforating_info_cms}
			{else}
				{displayCMS cms='product-perforation'}
			{/if}
		</div>
	</div>
	{else}
		<input type="hidden" name="{$product_key}[linear_perforating]" value="{$product->linear_perforating}" />
		<input type="hidden" name="{$product_key}[numbering]" value="{$product->numbering}" />
	{/if}

{else}
		<input type="hidden" name="{$product_key}[holes]" value="{$product->holes}" />
		<input type="hidden" name="{$product_key}[linear_perforating]" value="{$product->linear_perforating}" />
		<input type="hidden" name="{$product_key}[numbering]" value="{$product->numbering}" />
		{if $product->embossing == 'recto' || $product->embossing == 'verso'}
			<input type="hidden" name="{$product_key}[embossing]" value="{$product->embossing}" />
			<input type="hidden" name="{$product_key}[embossing_top]" value="{$product->embossing_top}" />
			<input type="hidden" name="{$product_key}[embossing_left]" value="{$product->embossing_left}" />
			<input type="hidden" name="{$product_key}[embossing_height]" value="{$product->embossing_height}" />
			<input type="hidden" name="{$product_key}[embossing_width]" value="{$product->embossing_width}" />
		{else}
			{foreach from=$product->embossing item=emb key=k}
				{if $emb->side}
			<input type="hidden" name="{$product_key}[embossing][$k][side]" value="{$emb->side}" />
			<input type="hidden" name="{$product_key}[embossing[$k][top]" value="{$emb->top}" />
			<input type="hidden" name="{$product_key}[embossing[$k][left]" value="{$emb->left}" />
			<input type="hidden" name="{$product_key}[embossing[$k][height]" value="{$emb->height}" />
			<input type="hidden" name="{$product_key}[embossing[$k][width]" value="{$emb->width}" />
				{/if}
			{/foreach}
		{/if}
		<input type="hidden" name="{$product_key}[gilding]" value="{$product->gilding}" />
		<input type="hidden" name="{$product_key}[gilding_top]" value="{$product->gilding_top}" />
		<input type="hidden" name="{$product_key}[gilding_left]" value="{$product->gilding_left}" />
		<input type="hidden" name="{$product_key}[gilding_height]" value="{$product->gilding_height}" />
		<input type="hidden" name="{$product_key}[gilding_width]" value="{$product->gilding_width}" />
		<input type="hidden" name="{$product_key}[gilding3d]" value="{$product->gilding3d}" />
		<input type="hidden" name="{$product_key}[cutting_die_exists]" value="{$product->cutting_die_exists}" />
		<input type="hidden" name="{$product_key}[cutting_die]"  value="{$product->cutting_die}" />
{/if}

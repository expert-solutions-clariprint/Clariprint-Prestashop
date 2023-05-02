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
{if $product->options->models_mode}
{assign var=model_key value=uniqid('model')}
<div class="card">
	<h3 class="card-header">{l s='Extra models' mod='clariprint'} :</h3>
	<div id="models" class="models card-block">
		<div class="input-group">
			<span class="input-group-addon">{l s='Number of models' mod='clariprint'} : </span>	
			{if isset($product->options->models_quantites) & trim($product->options->models_quantites) != ''}

			<select name="{$product_key}[models_qt]" class="form-control">
				{foreach from=(array)explode("\n",$product->options->models_quantites) item=m}
				<option value="{$m}" {if ($m == $product->models_qt)}selected{/if}>{$m}</option>
				{/foreach}
			</select>
			{else}
			<input name="{$product_key}[models_qt]"
				onchange="updateModels_{$model_key}()"
				 placeholder="" 
				 type="number"
				 min="1"
				 id="mqt_{$model_key}"
				 class="CLInt model_quantity form-control text-xs-right" 
				 value="{$product->models_qt|default:1}"/>
			{/if}
			<span class="input-group-addon">{l s='models' mod='clariprint'}</span>
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
		<br/>
		<div class="row">
			{if !isset($product->options->models_hide_qt_mode) || !$product->options->models_hide_qt_mode}
			<div class="col-lg-6 col-sm-12">
				<div class="input-group">
					<span class="input-group-addon">
						<input type="radio"
							name="{$product_key}[models_qt_mode]"
							id="{$model_key}_models_qt_mode_same"
							value="same_qt"
							onchange="updateModels_{$model_key}()"
							{if $product->models_qt_mode != 'different_qt' }checked{/if}
							aria-label="">
					<label for="{$model_key}_models_qt_mode_same">{l s='Same quantity for each models' mod='clariprint'}</label>
					</span>
				</div>
			</div>
			{else}
				<input type="hidden" name="{$product_key}[models_qt_mode]" value="{$product->models_qt_mode}"/>
			{/if}
			{if !$product->options->models_mode_hide_qt}
			<div class="col-lg-6 col-sm-12">
				<div class="input-group">
					<span class="input-group-addon">
						<input type="radio"
							name="{$product_key}[models_qt_mode]"
							id="{$model_key}_models_qt_mode_assembled"
							value="same_qt_assembled"
							onchange="updateModels_{$model_key}()"
							{if $product->models_qt_mode == 'same_qt_assembled' }checked{/if}
							aria-label="">
					<label for="{$model_key}_models_qt_mode_same">{l s='Same quantity for each models and assembled' mod='clariprint'}</label>
					</span>
				</div>
			</div>
			<div class="col-lg-12 col-sm-12">
				<div class="input-group">
					<span class="input-group-addon">
						<input type="radio"
							onchange="updateModels_{$model_key}()"
							name="{$product_key}[models_qt_mode]"
							id="{$model_key}_models_qt_mode_diff"
							value="different_qt"
							{if $product->models_qt_mode == 'different_qt' }checked{/if}
							aria-label="">
					<label for="{$model_key}_models_qt_mode_diff">{l s='different quantities for each models' mod='clariprint'}</label>
					</span>
					
				</div>
			</div>
			{/if}
		</div>
		<div class="clear"></div>
		<div class="clear"></div><br/>
		

		{if !$product->options->models_mode_hide_qt}

		<script type="text/javascript">
		var model_string = '{l s='model ' mod='clariprint'}';

		function updateModels_{$model_key}()
		{
			var n = parseInt($('#mqt_{$model_key}').val()) - 1;
			if (n < 0) n = 0;
			var $div = $('#{$model_key}');
			var $current = $div.find('div.input-group');
			var mode = $('#{$model_key}_models_qt_mode_diff').last().is(':checked');
			console.log('#{$model_key}_models_qt_mode_diff');
			if (mode) $div.show(); else $div.hide();
			
			if ($current.length > n)
			{
				while ($div.find('div.input-group').length > n)
					$div.find('div.input-group').last().remove();
			} else {
				while ($div.find('div.input-group').length < n)
					addModels_{$model_key}('#{$model_key}');
			}
		}
		
		function addModels_{$model_key}(k) {
			var $div = $(k);
			var id = ( $div.find('div.input-group').length > 0 ? parseInt($div.find('div.input-group').last().attr('modelidx')) + 1 : 0);
			var m = id + 2;
			
			$div.append(`<div class="input-group" modelidx="` + id + `">
				<span class="input-group-addon">{l s='Reference' mod='clariprint'}  ` + m + `: </span>
				<input class="form-control"  name="{$product_key}[models][` + id + `][reference]" placeholder="{l s='model ' mod='clariprint'} # `+ m +`" value="{$product->models[$m]->reference}"/>
				<span class="input-group-addon">{l s='quantity' mod='clariprint'} : </span>
				<input name="{$product_key}[models][` + id + `][quantity]" placeholder="" class="CLInt model_quantity form-control text-xs-right" value="{$product->models[$m]->quantity}"/>
				<span class="input-group-addon">{l s='ex.' mod='clariprint'}</span>
			</div>`);
		}
		</script>
	
		{assign var=n_models value=0}
		<div id="{$model_key}">
		{for $m=0 to $n_models - 1}
			<div class="input-group" modelidx="{$m + 2}">
				<span class="input-group-addon">{l s='Reference %d'  sprintf=[$m + 2] mod='clariprint'} : </span>
				<input class="form-control"  name="{$product_key}[models][{$m}][reference]" placeholder="{l s='model ' mod='clariprint'} # {$m + 2}" value="{$product->models[$m]->reference}"/>
				<span class="input-group-addon">{l s='quantity' mod='clariprint'} : </span>
				<input name="{$product_key}[models][{$m}][quantity]" placeholder="" class="CLInt model_quantity form-control text-xs-right" value="{$product->models[$m]->quantity}"/>
				<span class="input-group-addon">{l s='ex.' mod='clariprint'}</span>
			</div>
		{/for}
		</div>
		<div class="col-md-4" style="display: none">{l s='total printed' mod='clariprint'} : <div class='total_printed number'></div></div>
		{/if}
		{if $product->options->models_info}
		<div class="alert alert-info" role="alert">{$product->options->models_info nofilter}</div>
		{/if}

		{if $product->options->models_info_cms}
			{displayCMS cms=$product->options->models_info_cms}
		{else}
			{displayCMS cms='product-models'}
		{/if}
	</div>
</div>
{else}
	<input type="hidden" name="{$product_key}[models_qt_mode]" value="{$product->models_qt_mode|default:'same_qt'}"/>
	<input type="hidden" name="{$product_key}[models_qt]" value="{$product->models_qt}"/>
	{foreach from=(array)$product->models item=mod key=key}
		{if $mod->quantity}
			<input type="hidden" name="{$product_key}[models][{$key}][reference]" value="{$mod->reference}"/>
			<input type="hidden"  name="{$product_key}[models][{$key}][quantity]" value="{$mod->quantity}"/>
		{/if}
	{/foreach}
{/if}

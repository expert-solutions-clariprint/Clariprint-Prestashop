<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.14.0/ace.js" integrity="sha512-WYlXqL7GPpZL2ImDErTX0RMKy5hR17vGW5yY04p9Z+YhYFJcUUFRT31N29euNB4sLNNf/s0XQXZfzg3uKSoOdA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/gl-matrix@3.0.0/gl-matrix-min.js"></script>
<script src="{$link->getAdminLink("AdminClariprintParametrics")}&ajax=1&action=SamplerJS"></script>
<form method="post" id="qyp_post" enctype="multipart/form-data">
	<input type="hidden" name="id_clariprint_asset" value="{$asset->id}">
	<div class="panel" id="qyp_editor">
		<h3 class="panel-header"> <i class="icon-tag"></i> {l s='Clariprint Parametrics Asset' d='Module.Clariprint.Admin'}</h3>
		<div class="panel-body" style="height: 800px;" >
			<div class="row">
				<div class="col-md-9">
					<div class="row">
						<div class="col-md-3"><label>Name :</label></div>
						<div class="col-md-9"><input type="text" class="form-control" name="name" value="{$asset->name}"></div>
					</div>
					<div class="row">
						<div class="col-md-3"><label>Type :</label></div>
						<div class="col-md-9"><select name="kind" id="kind" class="form-control">
							{foreach from=$kinds item=kind key=k}
							<option value="{$k}" {if $k == $asset->kind}selected{/if}>{$kind}</option>
							{/foreach}
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-3"><label>uuid :</label></div>
						<div class="col-md-9">{$asset->uuid}</div>
					</div>
					<div class="form-group" id="file_group">
						<label>file</label>
						<input type="file" class="form-control" name="asset_file" value="">
					</div>
					<div class="form-group">
						<label>JSON</label>
					</div>
					<pre id="editor" class="hv-100" style="height: 500px; margin-bottom: 3px">{$asset->json}</pre>
					<input type="hidden" name="json" id="json">
				</div>
				<div class="col-md-3">
					<div class="input-group">
						<input type="search" class="form-control" id="searchassets">
						<div class="input-group-btn">
							<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Types ..</button>
							<ul class="dropdown-menu">
								{foreach from=$kinds item=kind key=k}
								<li class=""
									role="kind-select" value="{$k}"><a href="#">{$kind}</a></li>
								{/foreach}
							</ul>
						</div>

					</div>
					<div class="list-group w-100" id="assets" style="overflow-y: scroll; max-height: 300px;"  >
						{foreach from=$assets item=ass}
						<div class="list-group-item asset" 
							iri="{if $ass.kind == "FILE"}/img/clariprint_assets/{$ass.id_clariprint_asset}/{$ass.json}{else}{$ass.name}|{$ass.kind}{/if}"
							uuid="{$ass.uuid}" kind="{$ass.kind}">
							{$ass.name}
							<span class="badge">{$ass.kind}</span>
							<a class="badge" href="{$link->getAdminLink('AdminClariprintParametrics')}&updateclariprint_asset=&id_clariprint_asset={$ass.id_clariprint_asset}">go</a>
						</div>
						{/foreach}
					</div>
				</div>
			</div>
		</div>
		<div class="panel-footer d-flex justify-content-between">
				<div class="form-check">
					<input type="checkbox" class="form-check-input" name="qyp_debug" value="1" id="mode_debug">
					<label class="form-check-label" for="mode_debug">Debug</label>
				</div>

			<button class="btn btn-primary" role="call_api" api="GetSVG_WF" type="button">Test SVG</button>
			<button class="btn btn-primary" role="call_api" api="GetMetrix" type="button">Metrix</button>
			<button class="btn btn-primary" role="call_api" api="GetWebGl" type="button">Test 3D</button>
			<button class="btn btn-primary" role="call_api" api="GetBusinessTree" type="button">Business Objects</button>
			<button class="btn btn-primary" type="submit" id="SaveAsset" name="action" value="SaveAsset">Enregistrer</button>
		</div>
	</div>
	<div class="panel">
		<h3 class="panel-header"> <i class="icon-tag"></i> {l s='Clariprint Parametrics Asset' d='Module.Clariprint.Admin'}</h3>
		<div class="panel-body">
			<div class="row">
				<div class="col-6 row">
				{if isset($json) && isset($json->parameters)}

					{foreach from=$json->parameters item=param}
						{if (isset($param->type))}
							<div class="col-12">
							{if $param->type == "box"}
								{assign var=product value=$param}
								{assign var=product_key value="params[{$param->name}]"}
								{* include file="product.tpl" *}
							{else}unknwon type {$param->type}
							{/if}
							</div>
						{else}
							<div class="form-group col-3">
								<label>{if isset($param->label)}{$param->label}{/if}</label>
								<input class="form-control" type="text" class="text-right" name="params[{$param->name}]" value="{if is_string($param->default)}{$param->default}{else}{json_encode($param->default)|escape}{/if}">
							</div>
						{/if}
						{/foreach}
				{/if}
				</div>
				{*  *}
				<div class="col-md-6" >
					{if isset($json) && isset($json->actions)}
					{foreach from=$json->actions item=param}
					<div class="form-check">
						<input type="checkbox" class="form-check-input" name="actions[]" value="{$param->name}" id="a_{$param->name}">
						<label class="form-check-label" for="a_{$param->name}">{$param->name} : {$param->description}</label>
					</div>
					{/foreach}
					{/if}
					<div id="qyp_output"></div>
				</div>
			</div>
		</div>
	</div>
</form>



<script type="text/javascript">
	jQuery(function(){
		var editor = ace.edit("editor");
		editor.setTheme("ace/theme/textmate");
		editor.session.setMode("ace/mode/json");
		$( "#qyp_editor" ).on( "resize", function( event, ui ) { editor.resize() } );
		$.widget('qyp.search_asset',{ 
			$keyfilter:null,
			$assets:null,
			$kindsmenus:null,
			_init:function()
			{
				console.log('init search_asset');
				this.$keyfilter = $('#searchassets');
				this.$keyfilter.keyup($.proxy(this.filter,this));
				this.$elems = $(this.element).find(".asset");
				this.$kinds = $('[role=kind-select]');
				this.$kinds.click($.proxy(this.selectKind,this));
				console.log('kinds',this.$kinds);
				console.log('***********************************',this.$kinds);
				console.log( this, this.$keyfilter, this.$elems );
			},
			selectKind:function(evt)
			{
				if ($(evt.currentTarget).hasClass('active'))
					$(evt.currentTarget).removeClass('active');
				else	
					$(evt.currentTarget).addClass('active');
				this.filter();

			},

			filter:function()
			{
				console.log('filter...');
				var kinds = this.$kinds.filter('.active').map(function(idx,elem){ 

								return $(elem).attr('value'); }).toArray();
				console.log('kinds', kinds);
				var key = this.$keyfilter.val();
				const re = new RegExp(key, 'i');
				
				if (true)
				{
					console.log('elems',this.$elems);
					
					this.$elems.each(function(idx,e){
							var $e = $(e);
						console.log(kinds.includes('po'));
						if (kinds.length && !(kinds.includes($e.attr('kind')))) {

							$e.hide();
							return;
						} else {
							console.log($e.attr('kind'),"in",kinds);
						}
						if (key)
						{
							console.log("search",idx,$e);
							if ($e.text().match(re))
								$e.show();
							else $e.hide();							
						} else $e.show();
					});
					/*for (var i = 0; i >= $elems.length; i++) {
						var $e = $($elems[i]);

						if ($e.text().match(re))
							$e.show();
						else $e.hide();
					}*/
				} else {
					console.log('no key',key);
					this.$elems.show();
				}
			}

		});
		$('#assets').search_asset();
		$('#kind').change(function(evt) {
			var kind = $('#kind').val();
			if (kind == 'FILE')
			{
				$('#file_group').show();
				$('#editor').hide();
			} else {
				$('#file_group').hide();
				$('#editor').show();

			}
		});
		$('#kind').change();

		$('#SaveAsset').click(function(evt){
			evt.stopPropagation();
			$('#json').val(editor.getValue());
		});
		$('[role=call_api]').click(function(evt)
		{
			$('#qyp_output').html('..');
			var api = $(this).attr('api');

			$.ajax({	
				type:'post',
				url: '{$link->getAdminLink('AdminClariprintParametrics')}&action=CallApi&api=' + api +'&ajax=1&id={$asset->id}',
				data: $('#qyp_post').serialize(),
				dataType: "json",
				success:function(data,info,xhr){
					console.log('success');
					console.log(data);
					if (typeof data == 'object' )
					{
						if (Array.isArray(data.response))
						{
							console.log('Array...');
							for (const val of data.response) {
								$('#qyp_output').append(val+"<br/>");
							}
						}
						else if (typeof data.response == 'object' )
						{
								{literal}
							console.log('xhr',xhr);
							var txt = JSON.stringify(data, null, 2);
							var $pre = $('#qyp_output').append(`<pre style="height: 300px" id="textrep">${txt}</pre>`);
							var outeditor = ace.edit("textrep");
							outeditor.setTheme("ace/theme/textmate");
							outeditor.session.setMode("ace/mode/json");
							/*
							$('#qyp_output').append(`<div class="row">`);
							for (const [key, value] of Object.entries(data.response)) {
								$('#qyp_output').append(`<div class="form-group col-3"><label>${key}</label><input class="form-control text-right" type="text" value="${value}"></div>`);
								console.log(`${key}: ${value}`);
							}
							*/
								{/literal}
							// $('#qyp_output').append(`</div>`);
						}
						else
							$('#qyp_output').html($(data.response));
					} else $('#qyp_output').html(data);
				},
				error:function(req,status,data)
				{
					console.log("error",req,status,data);
					$('#qyp_output').html(req.responseText);

				},
				done:function()
				{
					console.log('done');
				}
			});
		});
		$('.asset').click(function(evt){
			var $this = $(this);
			var iri = $this.attr('iri');
			editor.session.insert(editor.getCursorPosition(),'"' + iri + '"' );
		});
	});
</script>


<style type="text/css">
	canvas {
		border: 1px solid grey;
		background-color: #F0F0F0;
	}
</style>

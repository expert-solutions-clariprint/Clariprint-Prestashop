{extends file='page.tpl'}
{block name='page_content'}
<div class="section">
	<header class="page-header">
		<h1>{l s='My configurations' mod='clariprint'}</h1>
	</header>
	<div>
		<table class="table">
			<thead>
				<tr>
					<th>{l s='Date' mod='clariprint'}</th>
					<th>{l s='#' mod='clariprint'}</th>
					<th>{l s='Project Name' mod='clariprint'}</th>
					<th>{l s='Cost (w.Tax)' mod='clariprint'}</th>
					<th>{l s='Resume' mod='clariprint'}</th>
					<th colspan="2">{l s='Actions' mod='clariprint'}</th>
				</tr>
			</thead>
			<tbody>
				{foreach from=$projects item=$project}
				<tr>
					<td>{$project.date_add}</td>
					<td>{$project.id}</td>
					<td>{$project.name}</td>
					<td class="text-xs-right">{Tools::displayPrice($project.price_wt)}</td>
					<td><button class='btn' data-toggle="modal" data-target="#detail_{$project.id}">{l s='detail'}</button>
						<div class="modal" tabindex="-1" role="dialog" id="detail_{$project.id}">
							<div class="modal-dialog" role="document" >
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title">{$project.name}</h5>
									</div>
									<div class="modal-body">
										<p>{$project.resume nofilter}</p>
									</div>
									<div class="modal-footer">
										<a href="{$link->getModuleLink('clariprint','projects',['action' => 'AddToCart','ajax' => 1,'id_customization' => $project.id])}" class="btn">
											<i class="material-icons shopping-cart"></i>{l s='Add to cart' mod='clariprint'}
										</a>
									</div>
								</div>
							</div>
						</div>
					</td>
					<td>{if (strtotime($project.date_add) > strtotime('- 15 days'))}
						<a target="_blank" href="?pdf=1&id_customisation={$project.id}" class='btn'>{l s='Pdf' mod='clariprint'}</a>{/if}</td>
					<td>{if (strtotime($project.date_add) > strtotime('- 15 days'))}
						<a href="{$link->getModuleLink('clariprint','projects',['action' => 'AddToCart','ajax' => 1,'id_customization' => $project.id])}" class="btn">
						<i class="material-icons shopping-cart"></i>
						{l s='Add to cart' mod='clariprint'}
						</a>{/if}</td>
				</tr>
				{/foreach}
			</tbody>
			<tfoot>
			</tfoot>
		</table>
	</div>
</div>
{/block}

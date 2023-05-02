{*
* 2007-2013 EXPERT SOLUTIONS
*
* NOTICE OF LICENSE
* ALL RIGHT RESERVED TO EXPERT SOLUTIONS SARL

*  @author Expert Solutions SARL <contact@expert-solutions.fr>
*  @copyright  2013 EXPERT SOLUTIONS SARL
*  @license    proprietary
*}
{if $product->solving_server}
<input type="hidden" id="clariprint_solver_id" name="{$product_key}[solving_server]" value="{$product->solving_server}"/>
{/if}
{if $product->options->solving == 'ondemand'}
	<div class="card">
		<h3 class="card-header">{l s='Pricing' mod='clariprint'}</h3>
		<div id='solve' class="solve card-block">
			<div class="row">
				<div class="col-xs-12 align-center">
					<button class='btn start_solve'>{l s='Get my rate' mod='clariprint'}</button>
				</div>
			</div>
			<div class="card result" style="display:none">
				<h3 class="card-header price"></h3>
				<div class="card-block description"></div>
			</div>
			<div class="alert alert-danger" role="alert"  style="display:none">{l s="An error as occured. Try another product setup or contact us for more information" mod='clariprint}'}</div>
			<div class="alert alert-warning" role="alert"  style="display:none">{l s="Data had changed, press thebutton to get a new rate" mod='clariprint}'}</div>
			<progress class="progress progress-striped progress-animated" style="display:none" value="100" max="100"></progress>
		</div>
	</div>
{else}
	<div class="card" id="clariprint-save-customization" style="display: none">
		<h3 class="card-header">{l s='Pricing' mod='clariprint'}</h3>
		<div id='solve' class="solve card-block">			
			<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#clariprintsavecustomization">
			  {l s='Save configuration' mod='clariprint'}
			</button>
		</div>
	</div>
{/if}
{$product->options->marketplace}
{if $product->options->marketplace}
	<input type="hidden" id="id_solving_marketplace" name="id_solving_marketplace" value="{$product->solving_marketplace}"/>
	<h2 class="accordion_header">{l s='Marketplace' mod='clariprint'}</h2>
<div id='marketplace' class="marketplace">

	<div class="span3">
		<button class='btn start_marketplace'>{l s='Get Marketplace offer' mod='clariprint'}</button>
	</div>
	<style>
		.ui-progressbar {
			position: relative;
		}
		.mp_progress_txt {
			position: absolute;
			left: 50%;
			top: 4px;
			font-weight: bold;
			text-shadow: 1px 1px 0 #fff;
		}
	</style>
	<div style="clear:both;"/>
	
	<div class="mp_progressbar"><div class="mp_progress_txt">loading....</div></div>
	<table class="results">
		<thead>
			<tr>
				<th>{l s='Printer' mod='clariprint'}</th>
				<th>{l s='Offer' mod='clariprint'}</th>
				<th>{l s='1000+' mod='clariprint'}</th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>
{/if}



<div id="clariprint_wait_message" style="display: none"><img src="/modules/clariprint/img/calculate_spin.svg" />
							<p>{l s='Instant quote in process, please wait.' mod='clariprint'}</p></div>

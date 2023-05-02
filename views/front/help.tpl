{$uid=uniqid('help')}

<div class="btn btn-primary" data-toggle="collapse" data-target="#{$uid}" aria-expanded="false" aria-controls="collapseExample">
	<i class="fas fa-info-circle"></i>
	{l s="help" mod='clariprint'}
</div>
<div class="collapse" id="{$uid}">
	<div class="alert alert-info" role="alert">{$content nofilter}</div>
</div>

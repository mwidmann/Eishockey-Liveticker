{if $match->head1 ne '' }
	{* only show if the head1 is set *}
	<div class="referee">
		<h3>Schiedsrichter</h3>

		<div class="head">
			<h4>Head</h4>
			{$match->head1}
			{if $match->head2 ne ''}
				<br/>{$match->head2}
			{/if}
		</div>
		<div class="linesman">
			<h4>Linesman</h4>
			{$match->linesman1}<br/>
			{$match->linesman2}
		</div>
	</div>
{/if}
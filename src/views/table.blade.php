{{-- Data Table --}}

<table class="table{{ HTML::dynamicArea(isset($table['class']) && $table['class'] != "", $table['class'], true) }}">
	<thead>
		<tr>
			@foreach ($columns as $column)
				@if (!$column['developer'] || Session::get('developer'))

					<th{!! HTML::dynamicArea(isset($column['headerClass']) && $column['headerClass'] != "", $column['headerClass']) . $column['sortAttribute'] !!}>

						{{ $column['label'] }}

					</th>

				@endif
			@endforeach
		</tr>
	</thead>

	<tbody>

		@include('elemental::partials.table_body')

	</tbody>

	@if ($footer)

		<tfoot>
			<tr>
				@foreach ($columns as $column)

					<td>&nbsp;</td>

				@endforeach
			</tr>
		</tfoot>

	@endif
</table>
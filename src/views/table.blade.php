{{-- Data Table --}}
<table class="table{{ Elemental::dynamicArea(isset($table['class']) && $table['class'] != "", $table['class'], true) }}">
	<thead>
		<tr>
			@foreach ($columns as $column)
				<th{{ Elemental::dynamicArea(isset($column['headerClass']) && $column['headerClass'] != "", $column['headerClass']) }}>{{ $column['label'] }}</th>
			@endforeach
		</tr>
	</thead>
	<tbody>
		@foreach ($data as $dataRow)
			<tr>
				<?php $dataRowArray = $dataRow->toArray(); ?>
				@foreach ($columns as $column)

					@if (isset($column['attribute']))
						@foreach ($dataRowArray as $dataCol => $dataCell)
							@if ($dataCol == $column['attribute'])

								<td>{{ Elemental::formatTableCellData($dataCell, $column['type']) }}</td>

							@endif
						@endforeach
					@elseif (isset($column['method']))

						<td>{{ $dataRow->$column['method']() }}</td>

					@else
						<td>
							@if (isset($column['elements']) && !empty($column['elements']))
								@foreach ($column['elements'] as $element)

									{{ Elemental::createElement($element, $dataRowArray) }}

								@endforeach
							@else
								&nbsp;
							@endif
						</td>
					@endif
				@endforeach
			</tr>
		@endforeach
	</tbody>
	@if ($footer)
		<tfoot>
			<tr>

			</tr>
		</tfoot>
	@endif
</table>
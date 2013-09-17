{{-- Data Table --}}
<table class="table{{ Elemental::dynamicArea(isset($table['class']) && $table['class'] != "", $table['class'], true) }}">
	<thead>
		<tr>
			@foreach ($columns as $column)
				@if (!$column['developer'] || Session::get('developer'))
					<th{{ Elemental::dynamicArea(isset($column['headerClass']) && $column['headerClass'] != "", $column['headerClass']) }}>{{ $column['label'] }}</th>
				@endif
			@endforeach
		</tr>
	</thead>
	<tbody>
		@if (count($data))
			@foreach ($data as $dataRow)
				<tr id="{{ $rows['idPrefix'].$dataRow->id }}"{{ Elemental::getTableRowClass($dataRow, $rows) }}>
					<?php $dataRowArray = $dataRow->toArray(); ?>
					@foreach ($columns as $column)
						@if (!$column['developer'] || Session::get('developer'))
							@if (isset($column['method']))

								@if (isset($column['attribute']) && $column['type'] == "list")
									<td>{{ Format::objListToStr($dataRow->$column['method'], $column['attribute']) }}</td>
								@else
									<td>{{ $dataRow->$column['method']() }}</td>
								@endif

							@elseif (isset($column['attribute']))
								@foreach ($dataRowArray as $dataCol => $dataCell)
									@if ($dataCol == $column['attribute'])

										<td>{{ Elemental::formatTableCellData($dataCell, $column['type']) }}</td>

									@endif
								@endforeach
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
						@endif
					@endforeach
				</tr>
			@endforeach
		@else
			<tr>
				<td colspan="{{ count($columns) }}" class="no-data">
					{{ isset($table['noDataMessage']) ? $table['noDataMessage'] : '' }}
				</td>
			</tr>
		@endif
	</tbody>
	@if ($footer)
		<tfoot>
			<tr>

			</tr>
		</tfoot>
	@endif
</table>
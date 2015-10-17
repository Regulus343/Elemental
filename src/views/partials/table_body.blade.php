{{-- Data Table - Body --}}

@if (count($data))

	@foreach ($data as $row)

		<tr id="{{ $rows['idPrefix'].$row->id }}"{{ HTML::getTableRowClass($row, $rows) }}>

			<?php $rowArray = $row->toArray(); ?>

			@foreach ($columns as $column)

				@if (!$column['developer'] || Session::get('developer'))

					@if (isset($column['method']))

						@if (isset($column['attribute']) && $column['type'] == "list")

							<td{!! HTML::getTableColumnClass($column) !!}>
								{!! Format::arrayToStringList(Format::objectItemsToArray($row->{Format::getMethodNameFromString($column['method'])}, $column['attribute'])) !!}
							</td>

						@else

							<td{!! HTML::getTableColumnClass($column) !!}>
								{!! HTML::formatTableCellData(HTML::getMethodResult($row, $column['method']), $column['type'], (isset($column['typeDetails']) ? $column['typeDetails'] : false)) !!}
							</td>

						@endif

					@elseif (isset($column['attribute']))

						@foreach ($rowArray as $dataCol => $dataCell)

							@if ($dataCol == $column['attribute'])

								<td{!! HTML::getTableColumnClass($column) !!}>
									{!! HTML::formatTableCellData($dataCell, $column['type'], (isset($column['typeDetails']) ? $column['typeDetails'] : false)) !!}
								</td>

							@endif

						@endforeach

					@else

						<td{!! HTML::getTableColumnClass($column) !!}>
							@if (isset($column['elements']) && !empty($column['elements']))
								@foreach ($column['elements'] as $element)

									{!! HTML::createElement($element, $row) !!}

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
			{{ (isset($table['noDataMessage']) ? $table['noDataMessage'] : '') }}
		</td>
	</tr>

@endif
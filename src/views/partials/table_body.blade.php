{{-- Data Table - Body --}}
@if (count($data))
	@foreach ($data as $row)
		<tr id="{{ $rows['idPrefix'].$row->id }}"{{ Elemental::getTableRowClass($row, $rows) }}>

			<?php $rowArray = $row->toArray(); ?>

			@foreach ($columns as $column)
				@if (!$column['developer'] || Session::get('developer'))

					@if (isset($column['method']))

						@if (isset($column['attribute']) && $column['type'] == "list")
							<td{{ Elemental::getTableColumnClass($column) }}>
								{{ Format::arrayToStringList(Format::objectItemsToArray($row->{Format::getMethodNameFromString($column['method'])}, $column['attribute'])) }}
							</td>
						@else
							<td{{ Elemental::getTableColumnClass($column) }}>
								{{ Elemental::formatTableCellData(Elemental::getMethodResult($row, $column['method']), $column['type'], (isset($column['typeDetails']) ? $column['typeDetails'] : false)) }}
							</td>
						@endif

					@elseif (isset($column['attribute']))
						@foreach ($rowArray as $dataCol => $dataCell)
							@if ($dataCol == $column['attribute'])

								<td{{ Elemental::getTableColumnClass($column) }}>
									{{ Elemental::formatTableCellData($dataCell, $column['type'], (isset($column['typeDetails']) ? $column['typeDetails'] : false)) }}
								</td>

							@endif
						@endforeach
					@else
						<td{{ Elemental::getTableColumnClass($column) }}>
							@if (isset($column['elements']) && !empty($column['elements']))
								@foreach ($column['elements'] as $element)

									{{ Elemental::createElement($element, $row) }}

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
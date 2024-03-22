jQuery(document).ready(function ($) {
	// Select categories to apply to.
	$('input[name="_size_guide_category"]').on('change', function () {
		var $input = $(this);

		if ('custom' === $input.val()) {
			$input.closest('.inside').children('.taxonomydiv').show();
		} else {
			$input.closest('.inside').children('.taxonomydiv').hide();
		}
	}).filter(':checked').trigger('change');

	// Render tabs.
	var $tabs = $( '#minimog-size-guide-tabs' ),
		$tabsNav = $( '.minimog-size-guide-tabs--tabs', $tabs ),
		templates = {
			panel: wp.template( 'minimog-size-guide-panel' )
		};

	if ( ! _.isUndefined( minimogSizeGuideTables ) ) {
		for ( var i = 0; i < minimogSizeGuideTables.tables.length; i++ ) {
			$tabs.append( templates.panel( {
				index: i,
				name: minimogSizeGuideTables.names[i],
				description: minimogSizeGuideTables.descriptions[i],
				table: minimogSizeGuideTables.tables[i],
				information: minimogSizeGuideTables.information[i]
			} ) );

			$tabs.find( '.minimog-size-guide-table-editor[data-tab='+ i +']' ).find( '.minimog-size-guide-table' ).minimogEditTable();

			if ( 0 === i ) {
				$tabsNav.children( ':eq(0)' ).addClass( 'active' );
				$tabs.find( '.minimog-size-guide-table-editor[data-tab='+ i +']' ).addClass( 'active' );
			}
		}
	}
});

// Edit Table plugin
(function ($, window, i) {

	'use strict';

	$.fn.minimogEditTable = function (options) {

		// Settings
		var settings = $.extend({
				data: [ ['']],
				tableClass: 'minimog-table-edit',
				jsonData: false,
				headerCols: false,
				maxRows: 999,
				first_row: true,
				row_template: false,
				field_templates: false,
				validate_field: function (col_id, value, col_type, $element) {
					return true;
				}
			}, options),
			$el = $(this),
			defaultTableContent = '<thead><tr></tr></thead><tbody></tbody>',
			$table = $('<table/>', {
				class: settings.tableClass + ((settings.first_row) ? ' wh' : ''),
				html: defaultTableContent
			}),
			defaultth = '<th><a class="addcol icon-button" href="#">+</a> <a class="delcol icon-button" href="#">-</a></th>',
			colnumber,
			rownumber,
			reset,
			is_validated = true;

		// Increment for IDs
		i = i + 1;

		// Build cell
		function buildCell(content, type) {
			content = (content === 0) ? "0" : (content || '');
			// Custom type
			if (type && 'text' !== type) {
				var field = settings.field_templates[type];
				return '<td>' + field.setValue(field.html, content)[0].outerHTML + '</td>';
			}
			// Default
			return '<td><input type="text" value="' + content.toString().replace(/"/g, "&quot;") + '" /></td>';
		}

		// Build row
		function buildRow(data, len) {

			var rowcontent = '',
				b;

			data = data || '';

			if (!settings.row_template) {
				// Without row template
				for (b = 0; b < (len || data.length); b += 1) {
					rowcontent += buildCell(data[b]);
				}
			} else {
				// With row template
				for (b = 0; b < settings.row_template.length; b += 1) {
					// For each field in the row
					rowcontent += buildCell(data[b], settings.row_template[b]);
				}
			}

			return $('<tr/>', {
				html: rowcontent + '<td><a class="addrow icon-button" href="#">+</a> <a class="delrow icon-button" href="#">-</a></td>'
			});

		}

		// Check button status (enable/disabled)
		function checkButtons() {
			if (colnumber < 2) {
				$table.find('.delcol').addClass('disabled');
			}
			if (rownumber < 2) {
				$table.find('.delrow').addClass('disabled');
			}
			if (settings.maxRows && rownumber === settings.maxRows) {
				$table.find('.addrow').addClass('disabled');
			}
		}

		// Fill table with data
		function fillTableData(data) {

			var a, crow = Math.min(settings.maxRows, data.length);

			// Clear table
			$table.html(defaultTableContent);

			// If headers or row_template are set
			if (settings.headerCols || settings.row_template) {

				// Fixed columns
				var col = settings.headerCols || settings.row_template;

				// Table headers
				for (a = 0; a < col.length; a += 1) {
					var col_title = settings.headerCols[a] || '';
					$table.find('thead tr').append('<th>' + col_title + '</th>');
				}

				// Table content
				for (a = 0; a < crow; a += 1) {
					// For each row in data
					buildRow(data[a], col.length).appendTo($table.find('tbody'));
				}

			} else if (data[0]) {

				// Variable columns
				for (a = 0; a < data[0].length; a += 1) {
					$table.find('thead tr').append(defaultth);
				}

				for (a = 0; a < crow; a += 1) {
					buildRow(data[a]).appendTo($table.find('tbody'));
				}

			}

			// Append missing th
			$table.find('thead tr').append('<th></th>');

			// Count rows and columns
			colnumber = $table.find('thead th').length - 1;
			rownumber = $table.find('tbody tr').length;

			checkButtons();
		}

		// Export data
		function exportData() {
			var row = 0,
				data = [],
				value;

			is_validated = true;

			$table.find('tbody tr').each(function () {

				row += 1;
				data[row] = [];

				$(this).find('td:not(:last-child)').each(function (i, v) {
					if (settings.row_template && 'text' !== settings.row_template[i]) {
						var field = settings.field_templates[settings.row_template[i]],
							el = $(this).find($(field.html).prop('tagName'));

						value = field.getValue(el);
						if (!settings.validate_field(i, value, settings.row_template[i], el)) {
							is_validated = false;
						}
						data[row].push(value);
					} else {
						value = $(this).find('input[type="text"]').val();
						if (!settings.validate_field(i, value, 'text', v)) {
							is_validated = false;
						}
						data[row].push(value);
					}
				});

			});

			// Remove undefined
			data.splice(0, 1);

			return data;
		}

		// Update element
		function updateEl() {
			$el.val( JSON.stringify(exportData()) );
		}

		// Fill the table with data from textarea or given properties
		if ($el.is('textarea')) {

			try {
				reset = JSON.parse($el.val());
			} catch (e) {
				reset = settings.data;
			}

			$el.after($table);

			// If inside a form set the textarea content on submit
			if ($table.parents('form').length > 0) {
				$table.parents('form').submit(function () {
					$el.val(JSON.stringify(exportData()));
				});
			}

		} else {
			reset = (JSON.parse(settings.jsonData) || settings.data);
			$el.append($table);
		}

		fillTableData(reset);

		// Add column
		$table.on('click', '.addcol', function ( event ) {
			event.preventDefault();

			var colid = parseInt($(this).closest('tr').children().index($(this).parent('th')), 10);

			colnumber += 1;

			$table.find('thead tr').find('th:eq(' + colid + ')').after(defaultth);

			$table.find('tbody tr').each(function () {
				$(this).find('td:eq(' + colid + ')').after(buildCell());
			});

			$table.find('.delcol').removeClass('disabled');

			updateEl();
		});

		// Remove column
		$table.on('click', '.delcol', function ( event ) {
			event.preventDefault();

			if ($(this).hasClass('disabled')) {
				return false;
			}

			var colid = parseInt($(this).closest('tr').children().index($(this).parent('th')), 10);

			colnumber -= 1;

			checkButtons();

			$(this).parent('th').remove();

			$table.find('tbody tr').each(function () {
				$(this).find('td:eq(' + colid + ')').remove();
			});

			updateEl();
		});

		// Add row
		$table.on('click', '.addrow', function ( event ) {
			event.preventDefault();

			if ($(this).hasClass('disabled')) {
				return false;
			}

			rownumber += 1;

			$(this).closest('tr').after(buildRow(0, colnumber));

			$table.find('.delrow').removeClass('disabled');

			checkButtons();
			updateEl();
		});

		// Delete row
		$table.on('click', '.delrow', function ( event ) {
			event.preventDefault();

			if ($(this).hasClass('disabled')) {
				return false;
			}

			rownumber -= 1;

			checkButtons();

			$(this).closest('tr').remove();

			$table.find('.addrow').removeClass('disabled');

			updateEl();
		});

		// Select all content on click
		$table.on('click', 'input', function () {
			$(this).select();
		});

		$table.on( 'input', 'input', function() {
			updateEl();
		} );

		// Return functions
		return {
			// Get an array of data
			getData: function () {
				return exportData();
			},
			// Get the JSON rappresentation of data
			getJsonData: function () {
				return JSON.stringify(exportData());
			},
			// Load an array of data
			loadData: function (data) {
				fillTableData(data);
			},
			// Load a JSON rappresentation of data
			loadJsonData: function (data) {
				fillTableData(JSON.parse(data));
			},
			// Reset data to the first instance
			reset: function () {
				fillTableData(reset);
			},
			isValidated: function () {
				return is_validated;
			}
		};
	};

})(jQuery, this, 0);
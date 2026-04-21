$(document).ready(function() {
    // Sidebar Toggle
    var currentUrl = window.location.href;
    
    // Select2
    // $('.select2').select2();

    // DataTables
    $('table.data_table, table.data-table').each(function() {
        var $table = $(this);
        var stateSave =  $table.data('ajax-data') ?? false;

        var isReportsTable = $table.data('reports') === true;

        var options = {
            order: [],
            pageLength: isReportsTable ? 50 : 25,
            lengthChange: !isReportsTable,
            searching: !isReportsTable,
            ordering: !isReportsTable,
            stateSave: stateSave,
            autoWidth: false,
            aoColumnDefs: [
                {
                    'bSortable' : false,
                    'aTargets' : [ 'no-sort' ]
                }
            ]
        };
        
        if ($table.data('ajax-url')) {
            options.processing = true;
            options.serverSide = true;
            options.ajax = {
                url: $table.data('ajax-url'),
                type: "POST",
                data: function(d) {
                    if ($table.data('ajax-data')) {
                        $.each(window[$table.data('ajax-data')](), function(key, value) {
                            d[key] = value;
                        });
                    }
                    d._token = $('meta[name="csrf-token"]').attr('content');
                }
            };
            options.createdRow = function (row, data, dataIndex) {
                var $this = $(row);
                if ($this.data('href')) {
                    if ($this.data('exclude_columns')) {
                        $.each($('td', row), function (colIndex) {
                            var exclude_columns = $this.data('exclude_columns');
                            if (!$.isArray(exclude_columns)) {
                                exclude_columns = [exclude_columns];
                            }
                            if ($.inArray(colIndex, exclude_columns) === -1) {
                                $(this).attr('data-href', $this.data('href'));
                                if ($this.data('target')) {
                                    $(this).attr('data-target', $this.data('target'));
                                }
                            }
                        });
                    } else {
                        $(row).attr('data-href', $this.data('href'));
                        if ($this.data('target')) {
                            $(row).attr('data-target', $this.data('target'));
                        }
                    }
                }
            };
        }
        
        $table.DataTable(options);
    });
});
function getValue(id) {
    return $('#' + id).val();
}

function drawTable(id, url, columns, filters = '') {

    columns.forEach(function (row, value) {
        if (row['defaultContent'] == undefined)
            row.defaultContent = 'N/A';

        if (row['data'] == 'statusID') {
            row['data'] = function (row) {
                return getStatus(row.statusID);
            }
        }
    })

    var table = $(id).DataTable({
        oLanguage: {
            sProcessing: "<img src='" + base_url('assets/img/loader.gif') + "'>"
        },
        "ordering": false,
        "processing": true,
        "serverSide": true,
        "searching": true,
        //searchDelay: 1000,
        // "deferLoading": 0, // here
        // "aaSorting": [],
        "ajax": {
            url: base_url(url), data: function (data, table) {
                data.columns = {};
                var xfilters = {
                    from_date: getValue('date-start'),
                    to_date: getValue('date-end'),
                    sectionID: getValue('sectionID'),
                    empID: getValue('empID'),
                    type: getValue('letterType'),
                }
                var filtersList = $('#' + table.sTableId + '_filter input,select');
                if (filtersList.length >= 2) {
                    for (var i = 0; i < filtersList.length; i++) {
                        var id = $(filtersList[i]).attr('id');
                        var value = getValue(id);
                        var newobject = JSON.parse('{"' + id + '":"' + value + '"}');
                        if (typeof id != 'undefined') {
                            Object.assign(xfilters, newobject)
                        }
                    }
                }
                data.xfilter = xfilters;
                return data;
            }
        },
        "columns": columns,
        // "columnDefs":function(col){
        //
        //    // console.log(col)
        //
        // },
        // initComplete: function () {

        //     $('#example thead').append('<tr class="filters"></tr>');
        //     this.api().columns().every(function (i) {

        //         var column = this;
        //         var filterName = '';
        //         if (columns[i].hasOwnProperty('filter')) {
        //             filterName = columns[i].filter;
        //         }

        //         if (filterName) {
        //             var select = $('<th><select name="' + filterName + '"><option value="">--Select--</option></select></th>')
        //                 .appendTo('.filters')
        //                 .on('change', function () {
        //                     var val = $.fn.dataTable.util.escapeRegex(
        //                         $(this).val()
        //                     );
        //                     column
        //                         .search(val ? '^' + val + '$' : '', true, false)
        //                         .draw();
        //                 });
        //             column.data().unique().sort().each(function (v, j) {
        //                 $(select).children('select').append('<option value="' + v + '">' + v + '</option>')
        //             });
        //         } else {
        //             $('<th style="border:0px"></th>').appendTo('.filters')
        //         }
        //     })
        // },
        //dom: 'Bfrtip',
        buttons: [
            //'excelHtml5',
            // 'pdfHtml5'
        ],
        "iDisplayLength": 50,
        "createdRow": function (row, data, index) {
            var info = table.page.info();    
            $('td', row).eq(0).html(index + 1 + info.page * info.length);
        },
    })
    $('input[type=search]').parent().remove();
    // var searching = `<label>Search:<input type="search" class="form-control form-control-sm" placeholder="" aria-controls="bank" id="searching" name="searching"></label>`;
    // $(id+'_filter').append(searching);
    // $('#searching').change(function () {
    //     table.draw();
    // });
    if (filters.length != 0) {
        filters.forEach(function (field) {
            if (field.type == 'text') {
                var nameFilter = `<label class="ml-2">${field.title}:<input type="text" name="${field.name}" id="${field.name}" class="form-control form-control-sm" placeholder="" aria-controls="bank"></label> `;
                $(id + '_filter').prepend(nameFilter);
                $('#' + field.name).change(function () {
                    table.draw();
                });
            }
            if (field.type == 'dropdown') {
                var nameFilter = `<label class="ml-2">${field.title}:${field.value}</label>`;
                $(id + '_filter').prepend(nameFilter);
                $('#' + field.name).change(function () {
                    table.draw();
                });
            }
            if (field.type == 'button') {
                var nameFilter = `<label>${field.title}:${field.value}</label>`;
                $(id + '_filter').prepend(nameFilter);
                $('#' + field.name).change(function () {
                    table.draw();
                });
            }

        });
    }

}


function drawGrouping(id, url, columns, filters = '') {
    $(document).ready(function () {
      var table =   $(id).DataTable({
            oLanguage: {
                sProcessing: "<img src='" + base_url('assets/img/loader.gif') + "'>"
            },
            "ordering": false,
            "processing": true,
            "serverSide": true,
            "searching": true,
            "columns": columns,            
            drawCallback: function (settings) {
                var api = this.api();
                var rows = api.rows({ page: 'current' }).nodes();
                var last = null;

                api.column(5, { page: 'current' }).data().each(function (group, i) {                   
                    if (last != group) {
                        $(rows).eq(i).before(
                            '<tr class="group" '+i+'><td colspan="9" style="BACKGROUND-COLOR:rgb(237, 208, 0);font-weight:700;color:#006232;">' + group + '|' + api.row().data().year + '</td></tr>'
                        );
                        last = group;
                    }
                });
            },
            "ajax": {
                url: base_url(url), data: function (data, table) {
                    data.columns = {};
                    var xfilters = {
                        from_date: getValue('date-start'),
                        to_date: getValue('date-end'),
                        sectionID: getValue('sectionID'),
                        empID: getValue('empID'),
                        type: getValue('letterType'),
                    }
                    var filtersList = $('#' + table.sTableId + '_filter input,select');
                    if (filtersList.length >= 2) {
                        for (var i = 0; i < filtersList.length; i++) {
                            var id = $(filtersList[i]).attr('id');
                            var value = getValue(id);
                            var newobject = JSON.parse('{"' + id + '":"' + value + '"}');
                            if (typeof id != 'undefined') {
                                Object.assign(xfilters, newobject)
                            }
                        }
                    }
                    data.xfilter = xfilters;
                    return data;
                }
            },

        });
        $('input[type=search]').parent().remove();
        if (filters.length != 0) {
            filters.forEach(function (field) {
                if (field.type == 'text') {
                    var nameFilter = `<label>${field.title}:<input type="text" name="${field.name}" id="${field.name}" class="form-control form-control-sm" placeholder="" aria-controls="bank"></label> `;
                    $(id + '_filter').prepend(nameFilter);
                    $('#' + field.name).change(function () {
                        table.draw();
                    });
                }
                if (field.type == 'dropdown') {
                    var nameFilter = `<label>${field.title}:${field.value}</label>`;
                    $(id + '_filter').prepend(nameFilter);
                    $('#' + field.name).change(function () {
                        table.draw();
                    });
                }
                if (field.type == 'button') {
                    var nameFilter = `<label>${field.title}:${field.value}</label>`;
                    $(id + '_filter').prepend(nameFilter);
                    $('#' + field.name).change(function () {
                        table.draw();
                    });
                }
    
            });
        }

    });
}

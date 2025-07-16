$(document).ready(function () {
    if ($.fn.DataTable.isDataTable('#example')) {
        // Add placeholder text
        $('.dataTables_filter input').attr('placeholder', 'Search...');

        // Move filter and length dropdown to panel controls
        $('.panel-ctrls')
            .append($('.dataTables_filter').addClass("pull-right"))
            .find("label").addClass("panel-ctrls-center");

        $('.panel-ctrls').append("<i class='separator'></i>");

        $('.panel-ctrls')
            .append($('.dataTables_length').addClass("pull-left"))
            .find("label").addClass("panel-ctrls-center");

        // Move pagination controls to panel footer
        $('.panel-footer').append($(".dataTable + .row"));
        $('.dataTables_paginate > ul.pagination').addClass("pull-right m0");
    }
});
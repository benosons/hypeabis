(function ($) {
    'use strict';

    $(document).ready(function () {
        $(".list-view-wrapper").scrollbar();

        $(".btn-need-confirmation").click(function (e) {
            e.preventDefault();
            var url_redirection = $(this).attr("href");
            var confirm_message = $(this).attr("data-message");
            var modal_title = $(this).attr("data-modal-title");

            Swal.fire({
                title: (typeof modal_title !== 'undefined' && modal_title.length > 0 ? modal_title : 'Warning'),
                text: confirm_message,
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#007bff',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.value) {
                    window.location.href = url_redirection;
                }
            });
        });

        $('.binari-nav.nav-tabs').delay(3000, function () {
            $(this).listslider({
                left_label: '<span class="fa fa-chevron-left"></span>',
                right_label: '<span class="fa fa-chevron-right"></span>'
            });
        });

        $(".js-RtableTabs, .js-RtableAccordions").responsiveTable();

        //Search Toggle
        $("#search_toggle_advanced").click(function () {
            $("#search_wrapper").slideDown(200);
            $("#search_toggle_advanced").hide();
            $("#search_toggle_simple").show();
            $("#search_collapsed").val('0');
        });

        $("#search_toggle_simple").click(function () {
            $("#search_wrapper").slideUp(200);
            $("#search_toggle_advanced").show();
            $("#search_toggle_simple").hide();
            $("#search_collapsed").val('1');
        });
        //END Search Toggle

        // Select2
        $(".select_withsearch").select2();
        $(".select_nosearch").select2({
            minimumResultsForSearch: Infinity
        });
        $(".select_multiple").select2();
        $(".select_multiple_max2").select2({
            maximumSelectionSize: 2
        });
        $(".select_multiple_max3").select2({
            maximumSelectionSize: 3
        });
        $(".select_multiple_max4").select2({
            maximumSelectionSize: 4
        });
        $(".select_multiple_max5").select2({
            maximumSelectionSize: 5
        });
        $(".select_multiple_max6").select2({
            maximumSelectionSize: 6
        });
        //END Select2

        //Datepicker
        //setting format tgl di bootstrap-datepicker.js line 1399
        $('.datepicker').datepicker({
            autoclose: true,
        });
        //Date Pickers
        $('.datepicker-component').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            zIndex: 9999
        });
        // END Datepicker

        //Datepicker (range)
        var date = new Date();
        $('.datepicker-range-start').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            zIndex: 9999,
        }).on('changeDate', function (selected) {
            var minDate = new Date(selected.date.valueOf());
            $('.datepicker-range-finish').datepicker('setStartDate', minDate);
        });

        $('.datepicker-range-finish').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            zIndex: 9999,
        }).on('changeDate', function (selected) {
            var endDate = new Date(selected.date.valueOf());
            $('.datepicker-range-start').datepicker('setEndDate', endDate);
        });

        if ($('.datepicker-range-start').val() != "" && $('.datepicker-range-start').val() != null) {
            $('.datepicker-range-finish').datepicker('setStartDate', $('.datepicker-range-start').val());
        }
        if ($('.datepicker-range-finish').val() != "" && $('.datepicker-range-finish').val() != null) {
            $('.datepicker-range-start').datepicker('setEndDate', $('.datepicker-range-finish').val());
        }
        //End datepicker (range)

        //datepicker disable past date..
        var date = new Date();
        // date.setDate(date.getDate()-1);
        $('.datepicker-component-disablepast').datepicker({
            format: 'dd-mm-yyyy',
            zIndex: 9999,
            startDate: date
        });

        //Monthpicker
        //setting format tgl di bootstrap-datepicker.js line 1399
        $('.monthpicker').datepicker({
            autoclose: true,
            minViewMode: 1,
            zIndex: 9999,
            format: 'mm-yyyy'
        });
        // END Monthpicker

        $('.monthpicker-disablepast').datepicker({
            autoclose: true,
            minViewMode: 1,
            zIndex: 9999,
            format: 'mm-yyyy',
            startDate: '+0m'
        });

        //Yearpicker
        //setting format tgl di bootstrap-datepicker.js line 1399
        $('.yearpicker').datepicker({
            autoclose: true,
            minViewMode: 2,
            zIndex: 9999,
            format: 'yyyy'
        });
        $('.yearpicker-onlypast').datepicker({
            autoclose: true,
            minViewMode: 2,
            zIndex: 9999,
            format: 'yyyy',
            endDate: date,
            useCurrent: true
        });
        $('.yearpicker-onlypast-withstart').datepicker({
            autoclose: true,
            minViewMode: 2,
            zIndex: 9999,
            format: 'yyyy',
            startDate: $('.yearpicker-onlypast-withstart').attr("data-start"),
            endDate: date,
            useCurrent: true
        });
        // END Yearpicker

        //Tagsinput
        var $tagsinput = $('.tagsinput');

        if ($tagsinput.length) {
            var contentTags = new Bloodhound({
                datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
                queryTokenizer: Bloodhound.tokenizers.whitespace,
                remote: {
                    url: base_url + '/content_tag?q=%QUERY',
                    wildcard: '%QUERY'
                },
            });

            contentTags.initialize();

            $tagsinput.tagsinput({
                typeaheadjs: {
                    source: contentTags.ttAdapter(),
                }
            });
        }
        //END Tagsinput
    });

    $('.panel-collapse label').on('click', function (e) {
        e.stopPropagation();
    });

    $(window).scroll(function () {
        if ($(window).scrollTop() >= (14 + $('.jumbotron').height())) {
            $('#submenu-wrapper').addClass("submenu-sticky");
        } else {
            $('#submenu-wrapper').removeClass("submenu-sticky");
        }
    });

    $.fn.responsiveTable = function () {
        var toggleColumns = function ($table) {
            var selectedControls = [];
            $table.find(".Rtable-Accordion, .Tab").each(function () {
                selectedControls.push($(this).attr("aria-selected"));
            });
            var cellCount = 0, colCount = 0;
            var setNum = $table.find(".Rtable-cell").length / Math.max($table.find(".Tab").length, $table.find(".Rtable-Accordion").length);
            $table.find(".Rtable-cell").each(function () {
                $(this).addClass("hiddenSmall");
                if (selectedControls[colCount] === "true") $(this).removeClass("hiddenSmall");
                cellCount++;
                if (cellCount % setNum === 0) colCount++;
            });
        };
        $(this).each(function () {
            toggleColumns($(this));
        });

        $(this).find(".Tab").click(function () {
            $(this).attr("aria-selected", "true").siblings().attr("aria-selected", "false");
            toggleColumns($(this).parents(".Rtable"));
        });

        $(this).find(".Rtable-Accordion").click(function () {
            $(this).attr("aria-selected", $(this).attr("aria-selected") !== "true");
            toggleColumns($(this).parents(".Rtable"));
        });
    };

})(window.jQuery);

function getCSRFToken(current_data) {
    current_data[$('#csrf-token-name').val()] = $('#csrf-token-hash').val();
    return current_data;
}

function refreshCSRFToken(name, hash) {
    $('#csrf-token-name').val(name);
    $('#csrf-token-hash').val(hash);
    $('input[name ="' + name + '"]').val(hash);
}

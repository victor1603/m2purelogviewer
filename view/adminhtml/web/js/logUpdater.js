define(['jquery', 'domReady!', 'mage/calendar', 'Magento_Ui/js/modal/alert'], function ($) {
    'use strict';

    /**
     * Scroll console div to bottom
     */
    function scroll() {
        var logData = document.getElementById('log_data'),
            dh = logData.scrollHeight,
            ch = logData.clientHeight;

        if (dh > ch) {
            logData.scrollTop = dh - ch;
        }
    }

    /**
     * Add or remove datepicker
     * @param {Boolean} flag
     */
    function addDatePicker(flag)
    {
        $('#select-date-block').hide();
        if (flag) {
            $('#select-date-block').css('display','inline-block');
        }
    }

    /**
     * Get list of logs
     * @param {String} url
     */
    function logList(url)
    {
        let logId = $('#log-changer').val();
        let logDate = $('#select-date').val() != undefined ? $('#select-date').val() : '';
        $('body').trigger('processStart');
        $.post(url, {
            logId: logId,
            logDate: logDate
        }, function (json) {
            if (json.is_file == '1') {
                $('#log_data').html(json.data);
                $('#log-file-changer').html('<option value="'+json.file_name+'">'+json.file_name+'</option>');
            } else if (json.data && json.data.length){
                $('#log-file-changer').html('<option value="">Select Log File</option>')
                let i = 0;
                while (i < json.data.length) {
                    $('#log-file-changer').append('<option value="'+json.data[i]+'">'+json.data[i]+'</option>');
                    i++;
                }
                $('#log_data').html('Please choose log file and press Reload button');
            }
            $('body').trigger('processStop');
            scroll();
        });
    }

    /**
     * Reload log file
     * @param {String} url
     */
    function logRead(url) {
        let logId = $('#log-changer').val();
        let logDate = $('#select-date').val() != undefined ? $('#select-date').val() : '';
        let logFile = $('#log-file-changer').val();
        $('body').trigger('processStart');
        $.post(url, {
            logId: logId,
            logDate: logDate,
            logFile: logFile
        }, function (json) {
            $('#log_data').html(json.data);
            $('body').trigger('processStop');
            scroll();
        }).fail(function(xhr, status, error) {
            $('body').trigger('processStop');
        });
    }

    function logDownload() {
        let logId = $('#log-changer').val();
        let logDate = $('#select-date').val() != undefined ? $('#select-date').val() : '';
        let logFile = $('#log-file-changer').val();
        if (logId == '0' || !logFile) {
            return false;
        }
        $('#log-dwnld input[name="logId"]').val(logId);
        $('#log-dwnld input[name="logDate"]').val(logDate);
        $('#log-dwnld input[name="logFile"]').val(logFile);
        $('#log-dwnld').submit();
    }

    /**
     * Export/return log updater
     * @param {Object} logUpdater
     */
    return function (logUpdater) {
        scroll();

        //LogPath event
        $('#log-changer').change(function () {
            addDatePicker(false);
            $('#log-file-changer').attr('disabled', true);
            $('#log_data').html('');

            if ($(this).find('option:selected').attr('is_date_log') == '1') {
                addDatePicker(true);
            }
            if ($(this).find('option:selected').attr('is_file') == '1') {
                $('#log-file-changer').removeAttr('disabled');
            }
            if ($(this).val() != '0' && $(this).val() != undefined) {
                $('#log-file-changer').removeAttr('disabled');
                logList(logUpdater.urlList);
            }
        });
        //Datepicker event
        $(document).on('change', '#select-date', function () {
            logList(logUpdater.urlList);
        });

        //Reload button event
        $('#connector-log-reloader').click(function () {
            logRead(logUpdater.urlFile);
        });

        $('#connector-log-dwnld').click(function () {
            logDownload();
        });

        function unavailable(date) {
            var string = $.datepicker.formatDate('dd-mm-yy',date);
            return [unavailableDates.indexOf(string) == -1];
        }
        //Datepicker
        let currentYear = new Date().getFullYear();
        let unavailableDates = ["12-06-2020","10-06-2020"];

        $("#select-date").calendar({
            beforeShowDay: unavailable,
            class:"select-date-picker",
            changeYear:true,
            changeMonth:true,
            yearRange: "2000:" + currentYear,
            buttonText:"Select Date",
            dateFormat:"dd-mm-yy"
        });

        $('#ui-datepicker-div').on('click' ,function (){

            alert($(this).find('.ui-datepicker-month').val());
        });
    };
});

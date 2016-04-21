function exportToCSV(data, filename, filter) {
    var colDelim = '","';
    var rowDelim = '"\r\n"';
    var csv = '"';
    
    $.each(filter, function (index, value) {
        csv = (csv + value + colDelim);
    });
    csv = (csv + rowDelim);
    
    $.each(data, function (index, obj) {
        $.each (obj, function (label, value) {
            if ($.inArray(label, filter) > -1){
                csv = (csv + value + colDelim);
            }
        });
        csv = (csv + rowDelim);
    });
    
    // Data URI
    var csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);
    
    var link = document.createElement('a');
    link.download = filename;
    link.href = csvData;
    link.click();
}


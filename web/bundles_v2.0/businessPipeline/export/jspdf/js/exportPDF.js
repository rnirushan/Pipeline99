function exportPDF(data, filename, filter, heading) {
    var doc = new jsPDF('landscape');
        
    var cursor = new pdfCursor();
    doc.setFontSize(12);
    doc.text(cursor.X, cursor.Y, heading);
    cursor.nextRow(cursor, 1);
    var maxlength = colsMaxlength (data, filter);
    doc.setFontSize(9);
    $.each(filter, function (index, value) {
        doc.text(cursor.X, cursor.Y, value.toString(), 0);
        cursor.nextCol(cursor, maxlength[index]);
    });
        
    cursor.nextRow(cursor, 1);
        
    $.each(data, function (index, obj) {
        var i = 0;
        $.each (obj, function (label, value) {
            if ($.inArray(label, filter) > -1){
                doc.text(cursor.X, cursor.Y, value.toString());
                cursor.nextCol(cursor, maxlength[i]);
                i++;
            }
        });
        cursor.nextRow(cursor, 1);
    });
        
    doc.save(filename);
}

function pdfCursor(){
    this.X = 10;
    this.Y = 10;
    
    this.nextCol = function(cursor, char){
        cursor.X = cursor.X + char*2;
    };
    
    this.nextRow = function(cursor, row){
        cursor.X = 10;
        cursor.Y = cursor.Y + row*5;
    };
}

function colsMaxlength (data, filter) {
    var maxlength = [];
    $.each (filter, function (index, value) {
        maxlength[index] = value.length;
    });
    var i = 0;
    $.each(data, function (index, obj) {
        i = 0;
        $.each (obj, function (label, value) {
            if ($.inArray(label, filter) > -1){
                if (maxlength[i] < value.length) {
                    maxlength[i] = value.length;
                }
                i++;
            }
        });
    });
    
    return maxlength;
}

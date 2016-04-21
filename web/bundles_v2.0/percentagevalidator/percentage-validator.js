(function ($) {

    $.fn.percentageValidator = function () {
        var element = this;
        this.on('keydown', function (evt) {
            if ((48 <= evt.keyCode && evt.keyCode <= 57)) {
                if (parseInt(element.val() + (evt.keyCode - 48), 10) > 100) {
                    evt.preventDefault();
                }
            } else if ((96 <= evt.keyCode && evt.keyCode <= 105)) {
                if (parseInt(element.val() + (evt.keyCode - 96), 10) > 100) {
                    evt.preventDefault();
                }
            } else if (8 !== evt.keyCode) {
                evt.preventDefault();
            }
        });
    }

    
}(jQuery));



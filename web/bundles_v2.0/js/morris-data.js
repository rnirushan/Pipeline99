// Bootstrap Tooltip
$(function () {
	$('[data-toggle="tooltip"]').tooltip()
})

$(function() {

    Morris.Area({
        element: 'morris-area-chart',
        data: [{
            period: '2010',
            iphone: 2666,
            ipad: null,
            itouch: 2647
        }, {
            period: '2011',
            iphone: 6810,
            ipad: 1914,
            itouch: 2293
        }, {
            period: '2012',
            iphone: 10687,
            ipad: 4460,
            itouch: 2028
        }],
        xkey: 'period',
        ykeys: ['iphone', 'ipad', 'itouch'],
        labels: ['iPhone', 'iPad', 'iPod Touch'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    });

    Morris.Bar({
        element: 'morris-bar-chart',
        data: [{
            y: '2006',
            a: 100,
            b: 90
        }, {
            y: '2007',
            a: 75,
            b: 65
        }, {
            y: '2008',
            a: 50,
            b: 40
        }, {
            y: '2009',
            a: 75,
            b: 65
        }, {
            y: '2010',
            a: 50,
            b: 40
        }, {
            y: '2011',
            a: 75,
            b: 65
        }, {
            y: '2012',
            a: 100,
            b: 90
        }],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B'],
        hideHover: 'auto',
        resize: true
    });

});

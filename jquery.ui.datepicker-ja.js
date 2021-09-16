/* Japanese initialisation for the jQuery UI date picker plugin. */
/* Written by Kentaro SATO (kentaro@ranvis.com). */
jQuery(function($){
	$.datepicker.regional['ja'] = {
		closeText: '髢峨§繧�',
		prevText: '&#x3c;蜑�',
		nextText: '谺｡&#x3e;',
		currentText: '莉頑律',
		monthNames: ['1譛�','2譛�','3譛�','4譛�','5譛�','6譛�',
		'7譛�','8譛�','9譛�','10譛�','11譛�','12譛�'],
		monthNamesShort: ['1譛�','2譛�','3譛�','4譛�','5譛�','6譛�',
		'7譛�','8譛�','9譛�','10譛�','11譛�','12譛�'],
		dayNames: ['譌･譖懈律','譛域屆譌･','轣ｫ譖懈律','豌ｴ譖懈律','譛ｨ譖懈律','驥第屆譌･','蝨滓屆譌･'],
		dayNamesShort: ['譌･','譛�','轣ｫ','豌ｴ','譛ｨ','驥�','蝨�'],
		dayNamesMin: ['譌･','譛�','轣ｫ','豌ｴ','譛ｨ','驥�','蝨�'],
		weekHeader: '騾ｱ',
		dateFormat: 'yy/mm/dd',
		firstDay: 0,
		isRTL: false,
		showMonthAfterYear: true,
		yearSuffix: '蟷ｴ'};
	$.datepicker.setDefaults($.datepicker.regional['ja']);
});
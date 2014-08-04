if (!window.tempobj) window.tempobj = [];
if (!window.dict) window.dict = {};

dict.calendar = {
	div:'-', 
	table:{
		'2011-10-31':'Хэллоуин — канун Дня всех святых', 
		'2011-11-01':'День судебного пристава',
		'2011-11-19':'День ракетных войск и артиллерии',
		'2011-12-12':'День Конституции Российской Федерации',
		'2011-12-31':'День Сурка'
	}
};

-function () {
	var d = document, year_offset = 5,
	red_day = 'red',
	hover = 'hover',
	lang = {
		dn:['пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс'], 
		mm:['янв', 'фев', 'мар', 'апр', 'май', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек'],
		correctDn: function(d){return (d == 0) ? 6 : d - 1},
		makeDate: function(s){s = s.split(/\D+/); return new Date(s[2], s[1] - 1, s[0])},
		formatDate: function (d) {
			d = [d.getDate(), d.getMonth() + 1, d.getFullYear()]
			return d.join(dict.calendar.div || '.').replace(/\b(\d)\b/g, '0$1')
		}
	},
	box = ce('div');
	box.className = 'mm disnone';

	var buildCArr = function (y) {
		var d0 = new Date(y, 0, 1), c = [], dd, dn, mm;
		for (var i = 0; i < 12; i ++) {c[i] = []}
		while (d0.getFullYear() == y) {
			dd = d0.getDate()
			dn = lang.correctDn(d0.getDay())
			mm = d0.getMonth()
			c[mm][dd] = dn
			d0.setDate(dd + 1)
		}
		
		return c;
	}

	var setDate = function (e) {
		e = e || window.event
		var o = e.target || e.srcElement
		if (!o.tagName || o.tagName.toLowerCase() != 'div') return
		var d = o.firstChild.nodeValue;
		
		this.input.date = new Date(this.year, this.month, d)
		this.input.value = lang.formatDate(this.input.date)
		
		cc(this, 'disnone')
		this.input.onblur = hideMonth
		this.input.onfocus = showMonth
	}
	
	
	var hideMonth = function (e, o) {
		e = e || window.event
		o = o || this
		o.onfocus = showMonth
		if (e && o.calendar && !hc(o.calendar.className, 'disnone')) 
			setTimeout(function(){cc(o.calendar, 'disnone')}, 400) 
	}
	
	var getEvent = function(d){
		d = d.replace(/\b(\d)\b/g, '0$1')
		return dict.calendar.table[d] 
	}
	
	var showMonth = function (e, dd, el) {
		e = e || window.event
		var oInput = e && (e.target || e.srcElement)
		dd = dd || this.date || new Date()
		var o = el || this;
		o.onfocus = '';
		
		if (o.timer) {clearTimeout(o.timer); o.timer = null}
		if (e && e.type == 'focus') return (o.timer = setTimeout(function(){showMonth(null, dd, o)}, 400))
		
		if (e && e.type == 'click' && oInput == o && o.calendar && !hc(o.calendar.className, 'disnone')) 
			return cc(o.calendar, 'disnone')
		
		var Y = dd.getFullYear(), c = buildCArr(Y), m = dd.getMonth(), 
		 arr = c[m], cN, curr_day = (o.date) ? dd.getDate() : 0,
		 marr = [], htm = '<table><col><col><col><col><col><col class="red"><col class="red">', 
		 week = new Array(7), coo = getTopLeft(o),
		 flush = function(arr){marr.push('<td>' + arr.join('</td><td>') + '</td>'); return new Array(7)};
		//alert(pageY)
		if (!o.calendar) {
			o.calendar = ac(box.cloneNode(true))
		}
		
		var c_div = o.calendar, title;
		
		htm += '<tr><th>' + lang.dn.join('</th><th>') + '</th></tr>'
		
		for (var i = 1, l = arr.length; i < l; i ++) {
			//if (arr[i] == undefined) continue;
			cN = []
			title = getEvent(Y + '-' + (m + 1) + '-' + i) || ''
			if (title) title = ' title="' + title + '"'
			if (arr[i] > 4) cN.push(red_day)
			if (i == curr_day) cN.push(hover)
			if (title) cN.push('metka')
			cN = (cN.length) ? ' class="' + cN.join(' ') + '"' : ''
			week[arr[i]] = '<div' + cN +  title + '>' + i + '</div>'
			if (arr[i] === 6) week = flush(week)
		}
		flush(week)
		
		htm += '<tr>' + marr.join('</tr><tr>') + '</tr></table>';
		c_div.innerHTML = htm
		
		var p = ce('p'), months = buildField('select', {}, lang.mm),
		 years = {}, yl = year_offset * 2 + 1, y0 = Y - year_offset;
		
		for (var i = 0; i < yl; i ++) {years[y0] = y0++}
		years = buildField('select', {}, years)
		ac(years, p)
		years.selectedIndex = year_offset
		years.onchange = function(){showMonth(null, new Date(this.value, m, 1), o)}
		
		ac(months, p)
		cc(p, 'ri')
		
		c_div.insertBefore(p, c_div.firstChild)
		months.selectedIndex = m
		months.onchange = function(){showMonth(null, new Date(Y, this.value, 1), o)}
		
		if (hc(c_div.className, 'disnone')) {
			cc(c_div, null, 'disnone')
			tempobj.push(o.calendar)
			
			var pageY = e && e.clientY || 0, c_height = c_div.offsetHeight + 10, th_height = o.offsetHeight + 10,
			 c_top = (pageY > c_height) ? coo.top - c_height : coo.top + th_height;
			o.calendar.style.left = coo.left + 'px'
			o.calendar.style.top = c_top + 'px'
		}
		
		c_div.input = o
		c_div.month = m
		c_div.year = Y 
		c_div.onclick = setDate
		c_div.onmouseover = function(){o.onblur = '';}
		c_div.onmouseout = function(){o.onblur = hideMonth;}
		o.focus()
		
	}

	var buildField = function (el, params, idxs) {
/** поле ввода (м.б. select, если есть список опций - idxs)
* @params = {
*  type:string, name:string, id:string, value...
* }
* @el:string
* @idxs:array - только для элемента select
*/
		var el = ce(el), name = params.name;
		for (var id in params) el[id] = params[id]
		if (idxs) {
			var params;
			for (id in idxs) {
				params = {value:id}
				ac(buildEl2('option', params, idxs[id]), el)
			}
		}
		return el
	}
	
	var buildEl2 = function (el, params, txt) {
/** элемент с вложенным textNode */
		var el = ce(el);
		for (var id in params) el[id] = params[id]
		if (txt != undefined) ac(ct(txt), el)
		return el
	}
	
	function initCalendar(e, el) {
		d.onkeyup = cancel
		var inputs = (el) ? [el] : gt("input") , inp;
		for (var e in inputs) {
			inp = inputs[e]
			if (!hc(inp && inp.className, "date")) continue;
			inp.onclick = showMonth 
			inp.onfocus = showMonth 
			inp.onblur = hideMonth 
		}
	}	
	
	addLoadEvent(initCalendar);
	
}()

/* common func */

function gi (i) {return document.getElementById(i)}
function ce (t) {return document.createElement(t)}
function ct (t) {return document.createTextNode(t)}
function gt (t, e) {e = e || document; return e.getElementsByTagName(t)}
function ac (n, e) {e = e || document.body; return e.appendChild(n);}

function hc(s, c) {return ~(' ' + s + ' ').indexOf(' ' + c + ' ')}
function cc (o, add, del) { /*cnangeClass*/
	var o = o || {}, n = 'className', cN = (undefined != o[n]) ? o[n] : o, ok = 0
	if ('string' !== typeof cN) return false
	var re = new RegExp('(\\s+|^)' + del + '(\\s+|$)', 'g')
	if (add) /*addClass*/
		if (!hc(cN, add)) {cN += ' ' + add; ok++}
	if (del) /*delClass*/
		if (hc(cN, del)) {cN = cN.replace(re, ' '); ok++}
	if (!ok) return false
	if ('object' == typeof o) o[n] = cN 
	else return cN
}

function getTopLeft (el) {
	var top = 0, left = 0;
	while(el) {
		top = top + parseInt(el.offsetTop)
		left = left + parseInt(el.offsetLeft)
		el = el.offsetParent
	}
	return {top:top, left:left}
} 

function cancel(e) {
	e = e || window.event
	if (27 == e.keyCode) {hidTemp()}
}

function hidTemp() { /* Спрятать всплывшее <>*/
	tempobj = window.tempobj
	if (!tempobj) return
	var el, t = (tempobj.length) ? tempobj : [tempobj]
	for (var i=0; i<t.length; i++) {
		if (t[i]) cc(t[i], 'disnone')
	}
}

function addLoadEvent(func) {
	var old = window.onload
	if (typeof window.onload != 'function') window.onload = func
	else window.onload = function() { old(); func(); }
}

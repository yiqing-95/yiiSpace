/**
 * 
 * @author ZhangHuihua@msn.com
 * @param {Object} opts Several options
 */
(function($){

	$.setRegional("datepicker", {
		dayNames:['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		monthNames:['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
	});

	$.fn.datepicker = function(opts){
		var setting = {
			box$:"#calendarDiv",
			year$:"#calendarDiv [name=year]", month$:"#calendarDiv [name=month]",
			close$:"#calendarDiv .close", calIcon$:"a.inputDateButton",
			days$:"#calendarDiv .days", dayNames$:"#calendarDiv .dayNames"
		};

		return this.each(function(){
			var $this = $(this);
			var dp = new Datepicker($this.val(), opts);
			
			function generateCalendar(dateWrap){
				var startDay = new Date(dateWrap.year,dateWrap.month-1,1).getDay();
				var dayStr="";
				for(t=0;t<startDay;t++) {dayStr+='<span></span>';}
				for(t=1;t<=dateWrap.days;t++){
					if(t==dateWrap.day){
						dayStr+='<dd class="slt" value="' + t + '">'+t+'</dd>';
					}else{
						dayStr+='<dd value="' + t + '">'+t+'</dd>';
					}
				}
				
				$(setting.days$).html(dayStr).find("dd").click(function(){
					$this.val(dp.changeDay($(this).attr("value")));
					closeCalendar();
				});
			}
			
			function closeCalendar() {
				$(setting.box$).remove();
				$(document).unbind("click", closeCalendar);
			}

			$this.click(function(event){
				closeCalendar();
				var offset = $this.offset();
				$(DWZ.frag['calendarFrag']).appendTo("body").css({
					left:offset.left+'px',
					top:offset.top+this.offsetHeight+'px'
				}).show().click(function(event){
					return false;
				});
				
				($.fn.bgiframe && $(setting.box$).bgiframe());
				
				var dayNames = "";
				$.each($.regional.datepicker.dayNames, function(i,v){
					dayNames += "<dt>" + v + "</dt>"
				});
				$(setting.dayNames$).html(dayNames);
				
				var dw = dp.getDateWrap();
				var dwNow = dp.getDateWrap(new Date());

				var $year = $(setting.year$);
				var yearstart = dwNow.year+parseInt(dp.get("yearstart"));
				var yearend = dwNow.year+parseInt(dp.get("yearend"));
				for(y=yearstart; y<=yearend; y++){
					$year.append('<option value="'+ y +'"'+ (dw.year==y ? 'selected="selected"' : '') +'>'+ y +'</option>');
				}
				var $month = $(setting.month$);
				$.each($.regional.datepicker.monthNames, function(i,v){
					var m = i+1;
					$month.append('<option value="'+ m +'"'+ (dw.month==m ? 'selected="selected"' : '') +'>'+ v +'</option>');
				});
				
				// generate calendar
				generateCalendar(dw);
				$year.add($month).change(function(){
					dp.changeDate($year.val(), $month.val());
					generateCalendar(dp.getDateWrap());
				});
				
				$(setting.close$).click(function(){
					closeCalendar();
				});
				
				$(document).bind("click", closeCalendar);
				return false;
			});
			
			$this.parent().find(setting.calIcon$).click(function(){
				$this.trigger("click");
				return false;
			});
		});
		
	}

	var Datepicker = function(sDate, opts) {
		this.opts = $.extend({
			pattern:'yyyy-mm-dd',
			yearstart:-10,
			yearend:10
		}, opts);
		
		this.sDate = sDate.trim();
	}
	
	$.extend(Datepicker.prototype, {
		get: function(name) {
			return this.opts[name];
		},
		_getDays: function (y,m){//获取某年某月的天数
			return m==2?(y%4||!(y%100)&&y%400?28:29):(/4|6|9|11/.test(m)?30:31);
		},

		getDateWrap: function(date){ //得到年,月,日
			if (!date) date = this.sDate ? this.parseDate(this.sDate) : new Date();
			var y = date.getFullYear();
			var m = date.getMonth()+1;
			var days = this._getDays(y,m);
			return {
				year:y, month:m, day:date.getDate(),
				days: days, date:date
			}
		},
		/**
		 * @param {year:2010, month:05, day:24}
		 */
		changeDate: function(y, m, d){
			var date = new Date(y, m - 1, d || 1);
			this.sDate = this.formatDate(date);
			return this.sDate;
		},
		changeDay: function(day){
			var dw = this.getDateWrap();
			return this.changeDate(dw.year, dw.month, day);
		},
		parseDate: function(sDate){
			var opts = this.opts;
			var getNumber = function(match){
				var iStart = -1, iCount = 0;
				for (var i = 0; i < opts.pattern.length; i++) {
					if (opts.pattern.charAt(i) == match){
						if (iStart < 0) iStart = i;
						iCount++;
					}
				}
				return sDate.substr(iStart, iCount);
			}
			
			var year = getNumber('y');
			var month = getNumber('m');
			var day = getNumber('d');
			
			var date = new Date(year, month - 1, day);
			if (date.getFullYear() != year || date.getMonth()+1 != month || date.getDate() != day)
				throw 'Invalid date';
			return date;
		},
		formatDate: function(date){
			var year = date.getFullYear();
			var month = date.getMonth()+1;
			var day = date.getDate();
			return this.opts.pattern.replace("yyyy", year)
				.replace("mm", month<10 ? "0"+month : month)
				.replace("dd", day<10 ? "0"+day : day);
		}
	});
})(jQuery);

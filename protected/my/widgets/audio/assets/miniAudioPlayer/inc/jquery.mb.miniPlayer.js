/*
 * ******************************************************************************
 *  jquery.mb.components
 *  file: jquery.mb.miniPlayer.js
 *
 *  Copyright (c) 2001-2014. Matteo Bicocchi (Pupunzi);
 *  Open lab srl, Firenze - Italy
 *  email: matteo@open-lab.com
 *  site: 	http://pupunzi.com
 *  blog:	http://pupunzi.open-lab.com
 * 	http://open-lab.com
 *
 *  Licences: MIT, GPL
 *  http://www.opensource.org/licenses/mit-license.php
 *  http://www.gnu.org/licenses/gpl.html
 *
 *  last modified: 27/01/14 20.07
 *  *****************************************************************************
 */

(function (jQuery) {

	var ua = navigator.userAgent.toLowerCase();
	var isAndroid = /android/.test(ua);
	var isAndroidDefault = isAndroid && !(/chrome/i).test(ua);
	var isDevice = 'ontouchstart' in window;

	/*Browser detection patch*/
	if (!jQuery.browser) {
		jQuery.browser = {}, jQuery.browser.mozilla = !1, jQuery.browser.webkit = !1, jQuery.browser.opera = !1, jQuery.browser.safari = !1, jQuery.browser.chrome = !1, jQuery.browser.msie = !1;
		var nAgt = navigator.userAgent;
		jQuery.browser.ua = nAgt, jQuery.browser.name = navigator.appName, jQuery.browser.fullVersion = "" + parseFloat(navigator.appVersion), jQuery.browser.majorVersion = parseInt(navigator.appVersion, 10);
		var nameOffset, verOffset, ix;
		if (-1 != (verOffset = nAgt.indexOf("Opera")))jQuery.browser.opera = !0, jQuery.browser.name = "Opera", jQuery.browser.fullVersion = nAgt.substring(verOffset + 6), -1 != (verOffset = nAgt.indexOf("Version")) && (jQuery.browser.fullVersion = nAgt.substring(verOffset + 8)); else if (-1 != (verOffset = nAgt.indexOf("MSIE")))jQuery.browser.msie = !0, jQuery.browser.name = "Microsoft Internet Explorer", jQuery.browser.fullVersion = nAgt.substring(verOffset + 5); else if (-1 != nAgt.indexOf("Trident")) {
			jQuery.browser.msie = !0, jQuery.browser.name = "Microsoft Internet Explorer";
			var start = nAgt.indexOf("rv:") + 3, end = start + 4;
			jQuery.browser.fullVersion = nAgt.substring(start, end)
		} else-1 != (verOffset = nAgt.indexOf("Chrome")) ? (jQuery.browser.webkit = !0, jQuery.browser.chrome = !0, jQuery.browser.name = "Chrome", jQuery.browser.fullVersion = nAgt.substring(verOffset + 7)) : -1 != (verOffset = nAgt.indexOf("Safari")) ? (jQuery.browser.webkit = !0, jQuery.browser.safari = !0, jQuery.browser.name = "Safari", jQuery.browser.fullVersion = nAgt.substring(verOffset + 7), -1 != (verOffset = nAgt.indexOf("Version")) && (jQuery.browser.fullVersion = nAgt.substring(verOffset + 8))) : -1 != (verOffset = nAgt.indexOf("AppleWebkit")) ? (jQuery.browser.webkit = !0, jQuery.browser.name = "Safari", jQuery.browser.fullVersion = nAgt.substring(verOffset + 7), -1 != (verOffset = nAgt.indexOf("Version")) && (jQuery.browser.fullVersion = nAgt.substring(verOffset + 8))) : -1 != (verOffset = nAgt.indexOf("Firefox")) ? (jQuery.browser.mozilla = !0, jQuery.browser.name = "Firefox", jQuery.browser.fullVersion = nAgt.substring(verOffset + 8)) : (nameOffset = nAgt.lastIndexOf(" ") + 1) < (verOffset = nAgt.lastIndexOf("/")) && (jQuery.browser.name = nAgt.substring(nameOffset, verOffset), jQuery.browser.fullVersion = nAgt.substring(verOffset + 1), jQuery.browser.name.toLowerCase() == jQuery.browser.name.toUpperCase() && (jQuery.browser.name = navigator.appName));
		-1 != (ix = jQuery.browser.fullVersion.indexOf(";")) && (jQuery.browser.fullVersion = jQuery.browser.fullVersion.substring(0, ix)), -1 != (ix = jQuery.browser.fullVersion.indexOf(" ")) && (jQuery.browser.fullVersion = jQuery.browser.fullVersion.substring(0, ix)), jQuery.browser.majorVersion = parseInt("" + jQuery.browser.fullVersion, 10), isNaN(jQuery.browser.majorVersion) && (jQuery.browser.fullVersion = "" + parseFloat(navigator.appVersion), jQuery.browser.majorVersion = parseInt(navigator.appVersion, 10)), jQuery.browser.version = jQuery.browser.majorVersion
	}

	/*******************************************************************************
	 * jQuery.mb.components: jquery.mb.CSSAnimate
	 ******************************************************************************/

	jQuery.fn.CSSAnimate=function(a,b,k,l,f){return this.each(function(){var c=jQuery(this);if(0!==c.length&&a){"function"==typeof b&&(f=b,b=jQuery.fx.speeds._default);"function"==typeof k&&(f=k,k=0);"function"==typeof l&&(f=l,l="cubic-bezier(0.65,0.03,0.36,0.72)");if("string"==typeof b)for(var j in jQuery.fx.speeds)if(b==j){b=jQuery.fx.speeds[j];break}else b=null;if(jQuery.support.transition){var e="",h="transitionEnd";jQuery.browser.webkit?(e="-webkit-",h="webkitTransitionEnd"):jQuery.browser.mozilla? (e="-moz-",h="transitionend"):jQuery.browser.opera?(e="-o-",h="otransitionend"):jQuery.browser.msie&&(e="-ms-",h="msTransitionEnd");j=[];for(d in a){var g=d;"transform"===g&&(g=e+"transform",a[g]=a[d],delete a[d]);"transform-origin"===g&&(g=e+"transform-origin",a[g]=a[d],delete a[d]);j.push(g);c.css(g)||c.css(g,0)}d=j.join(",");c.css(e+"transition-property",d);c.css(e+"transition-duration",b+"ms");c.css(e+"transition-delay",k+"ms");c.css(e+"transition-timing-function",l);c.css(e+"backface-visibility", "hidden");setTimeout(function(){c.css(a)},0);setTimeout(function(){c.called||!f?c.called=!1:f()},b+20);c.on(h,function(a){c.off(h);c.css(e+"transition","");a.stopPropagation();"function"==typeof f&&(c.called=!0,f());return!1})}else{for(var d in a)"transform"===d&&delete a[d],"transform-origin"===d&&delete a[d],"auto"===a[d]&&delete a[d];if(!f||"string"===typeof f)f="linear";c.animate(a,b,f)}}})}; jQuery.fn.CSSAnimateStop=function(){var a="",b="transitionEnd";jQuery.browser.webkit?(a="-webkit-",b="webkitTransitionEnd"):jQuery.browser.mozilla?(a="-moz-",b="transitionend"):jQuery.browser.opera?(a="-o-",b="otransitionend"):jQuery.browser.msie&&(a="-ms-",b="msTransitionEnd");jQuery(this).css(a+"transition","");jQuery(this).off(b)}; jQuery.support.transition=function(){var a=(document.body||document.documentElement).style;return void 0!==a.transition||void 0!==a.WebkitTransition||void 0!==a.MozTransition||void 0!==a.MsTransition||void 0!==a.OTransition}();

	/*
	 * Metadata - jQuery plugin for parsing metadata from elements
	 * Copyright (c) 2006 John Resig, Yehuda Katz, Jörn Zaefferer, Paul McLanahan
	 * Dual licensed under the MIT and GPL licenses:
	 *   http://www.opensource.org/licenses/mit-license.php
	 *   http://www.gnu.org/licenses/gpl.html
	 */

	(function(c){c.extend({metadata:{defaults:{type:"class",name:"metadata",cre:/({.*})/,single:"metadata"},setType:function(b,c){this.defaults.type=b;this.defaults.name=c},get:function(b,f){var d=c.extend({},this.defaults,f);d.single.length||(d.single="metadata");var a=c.data(b,d.single);if(a)return a;a="{}";if("class"==d.type){var e=d.cre.exec(b.className);e&&(a=e[1])}else if("elem"==d.type){if(!b.getElementsByTagName)return;e=b.getElementsByTagName(d.name);e.length&&(a=c.trim(e[0].innerHTML))}else void 0!= b.getAttribute&&(e=b.getAttribute(d.name))&&(a=e);0>a.indexOf("{")&&(a="{"+a+"}");a=eval("("+a+")");c.data(b,d.single,a);return a}}});c.fn.metadata=function(b){return c.metadata.get(this[0],b)}})(jQuery);

//ID3
	var q=null;function y(g,i,d){function f(b,h,e,a,d,f){var j=c();if(j){typeof f==="undefined"&&(f=!0);if(h)typeof j.onload!="undefined"?j.onload=function(){j.status=="200"||j.status=="206"?(j.fileSize=d||j.getResponseHeader("Content-Length"),h(j)):e&&e();j=q}:j.onreadystatechange=function(){if(j.readyState==4)j.status=="200"||j.status=="206"?(j.fileSize=d||j.getResponseHeader("Content-Length"),h(j)):e&&e(),j=q};j.open("GET",b,f);j.overrideMimeType&&j.overrideMimeType("text/plain; charset=x-user-defined");a&&j.setRequestHeader("Range",
			"bytes="+a[0]+"-"+a[1]);j.setRequestHeader("If-Modified-Since","Sat, 1 Jan 1970 00:00:00 GMT");j.send(q)}else e&&e()}function c(){var b=q;window.XMLHttpRequest?b=new XMLHttpRequest:window.F&&(b=new ActiveXObject("Microsoft.XMLHTTP"));return b}function a(b,h){var e=c();if(e){if(h)typeof e.onload!="undefined"?e.onload=function(){e.status=="200"&&h(this);e=q}:e.onreadystatechange=function(){e.readyState==4&&(e.status=="200"&&h(this),e=q)};e.open("HEAD",b,!0);e.send(q)}}function b(b,h){var e,a;function c(b){var p=
			~~(b[0]/e)-a,b=~~(b[1]/e)+1+a;p<0&&(p=0);b>=blockTotal&&(b=blockTotal-1);return[p,b]}function g(a,c){for(;n[a[0]];)if(a[0]++,a[0]>a[1]){c&&c();return}for(;n[a[1]];)if(a[1]--,a[0]>a[1]){c&&c();return}var k=[a[0]*e,(a[1]+1)*e-1];f(b,function(b){parseInt(b.getResponseHeader("Content-Length"),10)==h&&(a[0]=0,a[1]=blockTotal-1,k[0]=0,k[1]=h-1);for(var b={data:b.W||b.responseText,s:k[0]},p=a[0];p<=a[1];p++)n[p]=b;i+=k[1]-k[0]+1;c&&c()},d,k,j,!!c)}var j,i=0,l=new z("",0,h),n=[];e=e||2048;a=typeof a==="undefined"?
			0:a;blockTotal=~~((h-1)/e)+1;for(var m in l)l.hasOwnProperty(m)&&typeof l[m]==="function"&&(this[m]=l[m]);this.a=function(b){var a;g(c([b,b]));a=n[~~(b/e)];if(typeof a.data=="string")return a.data.charCodeAt(b-a.s)&255;else if(typeof a.data=="unknown")return IEBinary_getByteAt(a.data,b-a.s)};this.N=function(){return i};this.f=function(b,a){g(c(b),a)}}(function(){a(g,function(a){a=parseInt(a.getResponseHeader("Content-Length"),10)||-1;i(new b(g,a))})})()}
	function z(g,i,d){var f=g,c=i||0,a=0;this.P=function(){return f};if(typeof g=="string")a=d||f.length,this.a=function(b){return f.charCodeAt(b+c)&255};else if(typeof g=="unknown")a=d||IEBinary_getLength(f),this.a=function(b){return IEBinary_getByteAt(f,b+c)};this.n=function(b,a){for(var h=Array(a),e=0;e<a;e++)h[e]=this.a(b+e);return h};this.j=function(){return a};this.d=function(b,a){return(this.a(b)&1<<a)!=0};this.Q=function(b){b=this.a(b);return b>127?b-256:b};this.r=function(b,a){var h=a?(this.a(b)<<
			8)+this.a(b+1):(this.a(b+1)<<8)+this.a(b);h<0&&(h+=65536);return h};this.S=function(b,a){var h=this.r(b,a);return h>32767?h-65536:h};this.h=function(b,a){var h=this.a(b),e=this.a(b+1),c=this.a(b+2),d=this.a(b+3),h=a?(((h<<8)+e<<8)+c<<8)+d:(((d<<8)+c<<8)+e<<8)+h;h<0&&(h+=4294967296);return h};this.R=function(b,a){var c=this.h(b,a);return c>2147483647?c-4294967296:c};this.q=function(b){var a=this.a(b),c=this.a(b+1),b=this.a(b+2),a=((a<<8)+c<<8)+b;a<0&&(a+=16777216);return a};this.c=function(b,a){for(var c=
			[],e=b,d=0;e<b+a;e++,d++)c[d]=String.fromCharCode(this.a(e));return c.join("")};this.e=function(b,a,c){b=this.n(b,a);switch(c.toLowerCase()){case "utf-16":case "utf-16le":case "utf-16be":var a=c,e,d=0,f=1,c=0;e=Math.min(e||b.length,b.length);b[0]==254&&b[1]==255?(a=!0,d=2):b[0]==255&&b[1]==254&&(a=!1,d=2);a&&(f=0,c=1);for(var a=[],g=0;d<e;g++){var j=b[d+f],i=(j<<8)+b[d+c];d+=2;if(i==0)break;else j<216||j>=224?a[g]=String.fromCharCode(i):(j=(b[d+f]<<8)+b[d+c],d+=2,a[g]=String.fromCharCode(i,j))}b=
			new String(a.join(""));b.g=d;break;case "utf-8":e=0;d=Math.min(d||b.length,b.length);b[0]==239&&b[1]==187&&b[2]==191&&(e=3);f=[];for(c=0;e<d;c++)if(a=b[e++],a==0)break;else a<128?f[c]=String.fromCharCode(a):a>=194&&a<224?(g=b[e++],f[c]=String.fromCharCode(((a&31)<<6)+(g&63))):a>=224&&a<240?(g=b[e++],i=b[e++],f[c]=String.fromCharCode(((a&255)<<12)+((g&63)<<6)+(i&63))):a>=240&&a<245&&(g=b[e++],i=b[e++],j=b[e++],a=((a&7)<<18)+((g&63)<<12)+((i&63)<<6)+(j&63)-65536,f[c]=String.fromCharCode((a>>10)+55296,
			(a&1023)+56320));b=new String(f.join(""));b.g=e;break;default:d=[];f=f||b.length;for(e=0;e<f;){c=b[e++];if(c==0)break;d[e-1]=String.fromCharCode(c)}b=new String(d.join(""));b.g=e}return b};this.M=function(a){return String.fromCharCode(this.a(a))};this.Z=function(){return window.btoa(f)};this.L=function(a){f=window.atob(a)};this.f=function(a,c){c()}}document.write("<script type='text/vbscript'>\r\nFunction IEBinary_getByteAt(strBinary, iOffset)\r\n\tIEBinary_getByteAt = AscB(MidB(strBinary,iOffset+1,1))\r\nEnd Function\r\nFunction IEBinary_getLength(strBinary)\r\n\tIEBinary_getLength = LenB(strBinary)\r\nEnd Function\r\n<\/script>\r\n");(function(g){g.FileAPIReader=function(g){return function(d,f){var c=new FileReader;c.onload=function(a){f(new z(a.target.result))};c.readAsBinaryString(g)}}})(this);(function(g){g.k={i:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",z:function(g){for(var d="",f,c,a,b,p,h,e=0;e<g.length;)f=g[e++],c=g[e++],a=g[e++],b=f>>2,f=(f&3)<<4|c>>4,p=(c&15)<<2|a>>6,h=a&63,isNaN(c)?p=h=64:isNaN(a)&&(h=64),d=d+Base64.i.charAt(b)+Base64.i.charAt(f)+Base64.i.charAt(p)+Base64.i.charAt(h);return d}};g.Base64=g.k;g.k.encodeBytes=g.k.z})(this);(function(g){var i=g.t={},d={},f=[0,7];i.C=function(c,a,b){b=b||{};(b.dataReader||y)(c,function(g){g.f(f,function(){var f=g.c(4,7)=="ftypM4A"?ID4:g.c(0,3)=="ID3"?ID3v2:ID3v1;f.o(g,function(){var e=b.tags,i=f.p(g,e),e=d[c]||{},k;for(k in i)i.hasOwnProperty(k)&&(e[k]=i[k]);d[c]=e;a&&a()})})})};i.A=function(c){if(!d[c])return q;var a={},b;for(b in d[c])d[c].hasOwnProperty(b)&&(a[b]=d[c][b]);return a};i.B=function(c,a){if(!d[c])return q;return d[c][a]};g.ID3=g.t;i.loadTags=i.C;i.getAllTags=i.A;i.getTag=
			i.B})(this);(function(g){var i=g.u={},d=["Blues","Classic Rock","Country","Dance","Disco","Funk","Grunge","Hip-Hop","Jazz","Metal","New Age","Oldies","Other","Pop","R&B","Rap","Reggae","Rock","Techno","Industrial","Alternative","Ska","Death Metal","Pranks","Soundtrack","Euro-Techno","Ambient","Trip-Hop","Vocal","Jazz+Funk","Fusion","Trance","Classical","Instrumental","Acid","House","Game","Sound Clip","Gospel","Noise","AlternRock","Bass","Soul","Punk","Space","Meditative","Instrumental Pop","Instrumental Rock",
		"Ethnic","Gothic","Darkwave","Techno-Industrial","Electronic","Pop-Folk","Eurodance","Dream","Southern Rock","Comedy","Cult","Gangsta","Top 40","Christian Rap","Pop/Funk","Jungle","Native American","Cabaret","New Wave","Psychadelic","Rave","Showtunes","Trailer","Lo-Fi","Tribal","Acid Punk","Acid Jazz","Polka","Retro","Musical","Rock & Roll","Hard Rock","Folk","Folk-Rock","National Folk","Swing","Fast Fusion","Bebob","Latin","Revival","Celtic","Bluegrass","Avantgarde","Gothic Rock","Progressive Rock",
		"Psychedelic Rock","Symphonic Rock","Slow Rock","Big Band","Chorus","Easy Listening","Acoustic","Humour","Speech","Chanson","Opera","Chamber Music","Sonata","Symphony","Booty Bass","Primus","Porn Groove","Satire","Slow Jam","Club","Tango","Samba","Folklore","Ballad","Power Ballad","Rhythmic Soul","Freestyle","Duet","Punk Rock","Drum Solo","Acapella","Euro-House","Dance Hall"];i.o=function(d,c){var a=d.j();d.f([a-128-1,a],c)};i.p=function(f){var c=f.j()-128;if(f.c(c,3)=="TAG"){var a=f.c(c+3,30).replace(/\0/g,
			""),b=f.c(c+33,30).replace(/\0/g,""),g=f.c(c+63,30).replace(/\0/g,""),h=f.c(c+93,4).replace(/\0/g,"");if(f.a(c+97+28)==0)var e=f.c(c+97,28).replace(/\0/g,""),i=f.a(c+97+29);else e="",i=0;f=f.a(c+97+30);return{version:"1.1",title:a,artist:b,album:g,year:h,comment:e,track:i,genre:f<255?d[f]:""}}else return{}};g.ID3v1=g.u})(this);(function(g){function i(a,b){var c=b.a(a),d=b.a(a+1),e=b.a(a+2);return b.a(a+3)&127|(e&127)<<7|(d&127)<<14|(c&127)<<21}var d=g.G={};d.b={};d.frames={BUF:"Recommended buffer size",CNT:"Play counter",COM:"Comments",CRA:"Audio encryption",CRM:"Encrypted meta frame",ETC:"Event timing codes",EQU:"Equalization",GEO:"General encapsulated object",IPL:"Involved people list",LNK:"Linked information",MCI:"Music CD Identifier",MLL:"MPEG location lookup table",PIC:"Attached picture",POP:"Popularimeter",REV:"Reverb",
		RVA:"Relative volume adjustment",SLT:"Synchronized lyric/text",STC:"Synced tempo codes",TAL:"Album/Movie/Show title",TBP:"BPM (Beats Per Minute)",TCM:"Composer",TCO:"Content type",TCR:"Copyright message",TDA:"Date",TDY:"Playlist delay",TEN:"Encoded by",TFT:"File type",TIM:"Time",TKE:"Initial key",TLA:"Language(s)",TLE:"Length",TMT:"Media type",TOA:"Original artist(s)/performer(s)",TOF:"Original filename",TOL:"Original Lyricist(s)/text writer(s)",TOR:"Original release year",TOT:"Original album/Movie/Show title",
		TP1:"Lead artist(s)/Lead performer(s)/Soloist(s)/Performing group",TP2:"Band/Orchestra/Accompaniment",TP3:"Conductor/Performer refinement",TP4:"Interpreted, remixed, or otherwise modified by",TPA:"Part of a set",TPB:"Publisher",TRC:"ISRC (International Standard Recording Code)",TRD:"Recording dates",TRK:"Track number/Position in set",TSI:"Size",TSS:"Software/hardware and settings used for encoding",TT1:"Content group description",TT2:"Title/Songname/Content description",TT3:"Subtitle/Description refinement",
		TXT:"Lyricist/text writer",TXX:"User defined text information frame",TYE:"Year",UFI:"Unique file identifier",ULT:"Unsychronized lyric/text transcription",WAF:"Official audio file webpage",WAR:"Official artist/performer webpage",WAS:"Official audio source webpage",WCM:"Commercial information",WCP:"Copyright/Legal information",WPB:"Publishers official webpage",WXX:"User defined URL link frame",AENC:"Audio encryption",APIC:"Attached picture",COMM:"Comments",COMR:"Commercial frame",ENCR:"Encryption method registration",
		EQUA:"Equalization",ETCO:"Event timing codes",GEOB:"General encapsulated object",GRID:"Group identification registration",IPLS:"Involved people list",LINK:"Linked information",MCDI:"Music CD identifier",MLLT:"MPEG location lookup table",OWNE:"Ownership frame",PRIV:"Private frame",PCNT:"Play counter",POPM:"Popularimeter",POSS:"Position synchronisation frame",RBUF:"Recommended buffer size",RVAD:"Relative volume adjustment",RVRB:"Reverb",SYLT:"Synchronized lyric/text",SYTC:"Synchronized tempo codes",
		TALB:"Album/Movie/Show title",TBPM:"BPM (beats per minute)",TCOM:"Composer",TCON:"Content type",TCOP:"Copyright message",TDAT:"Date",TDLY:"Playlist delay",TENC:"Encoded by",TEXT:"Lyricist/Text writer",TFLT:"File type",TIME:"Time",TIT1:"Content group description",TIT2:"Title/songname/content description",TIT3:"Subtitle/Description refinement",TKEY:"Initial key",TLAN:"Language(s)",TLEN:"Length",TMED:"Media type",TOAL:"Original album/movie/show title",TOFN:"Original filename",TOLY:"Original lyricist(s)/text writer(s)",
		TOPE:"Original artist(s)/performer(s)",TORY:"Original release year",TOWN:"File owner/licensee",TPE1:"Lead performer(s)/Soloist(s)",TPE2:"Band/orchestra/accompaniment",TPE3:"Conductor/performer refinement",TPE4:"Interpreted, remixed, or otherwise modified by",TPOS:"Part of a set",TPUB:"Publisher",TRCK:"Track number/Position in set",TRDA:"Recording dates",TRSN:"Internet radio station name",TRSO:"Internet radio station owner",TSIZ:"Size",TSRC:"ISRC (international standard recording code)",TSSE:"Software/Hardware and settings used for encoding",
		TYER:"Year",TXXX:"User defined text information frame",UFID:"Unique file identifier",USER:"Terms of use",USLT:"Unsychronized lyric/text transcription",WCOM:"Commercial information",WCOP:"Copyright/Legal information",WOAF:"Official audio file webpage",WOAR:"Official artist/performer webpage",WOAS:"Official audio source webpage",WORS:"Official internet radio station homepage",WPAY:"Payment",WPUB:"Publishers official webpage",WXXX:"User defined URL link frame"};var f={title:["TIT2","TT2"],artist:["TPE1",
		"TP1"],album:["TALB","TAL"],year:["TYER","TYE"],comment:["COMM","COM"],track:["TRCK","TRK"],genre:["TCON","TCO"],picture:["APIC","PIC"],lyrics:["USLT","ULT"]},c=["title","artist","album","track"];d.o=function(a,b){a.f([0,i(6,a)],b)};d.p=function(a,b){var g=0,h=a.a(g+3);if(h>4)return{version:">2.4"};var e=a.a(g+4),v=a.d(g+5,7),k=a.d(g+5,6),s=a.d(g+5,5),j=i(g+6,a);g+=10;if(k){var o=a.h(g,!0);g+=o+4}var h={version:"2."+h+"."+e,major:h,revision:e,flags:{unsynchronisation:v,extended_header:k,experimental_indicator:s},
		size:j},l;if(v)l={};else{j-=10;for(var v=a,e=b,k={},s=h.major,o=[],n=0,m;m=(e||c)[n];n++)o=o.concat(f[m]||[m]);for(e=o;g<j;){o=q;n=v;m=g;var u=q;switch(s){case 2:l=n.c(m,3);var r=n.q(m+3),t=6;break;case 3:l=n.c(m,4);r=n.h(m+4,!0);t=10;break;case 4:l=n.c(m,4),r=i(m+4,n),t=10}if(l=="")break;g+=t+r;if(!(e.indexOf(l)<0)&&(s>2&&(u={message:{Y:n.d(m+8,6),K:n.d(m+8,5),V:n.d(m+8,4)},m:{T:n.d(m+8+1,7),H:n.d(m+8+1,3),J:n.d(m+8+1,2),D:n.d(m+8+1,1),w:n.d(m+8+1,0)}}),m+=t,u&&u.m.w&&(i(m,n),m+=4,r-=4),!u||!u.m.D))l in
			d.b?o=d.b[l]:l[0]=="T"&&(o=d.b["T*"]),o=o?o(m,r,n,u):void 0,o={id:l,size:r,description:l in d.frames?d.frames[l]:"Unknown",data:o},l in k?(k[l].id&&(k[l]=[k[l]]),k[l].push(o)):k[l]=o}l=k}for(var w in f)if(f.hasOwnProperty(w)){a:{r=f[w];typeof r=="string"&&(r=[r]);t=0;for(g=void 0;g=r[t];t++)if(g in l){a=l[g].data;break a}a=void 0}a&&(h[w]=a)}for(var x in l)l.hasOwnProperty(x)&&(h[x]=l[x]);return h};g.ID3v2=d})(this);(function(){function g(d){var f;switch(d){case 0:f="iso-8859-1";break;case 1:f="utf-16";break;case 2:f="utf-16be";break;case 3:f="utf-8"}return f}var i=["32x32 pixels 'file icon' (PNG only)","Other file icon","Cover (front)","Cover (back)","Leaflet page","Media (e.g. lable side of CD)","Lead artist/lead performer/soloist","Artist/performer","Conductor","Band/Orchestra","Composer","Lyricist/text writer","Recording Location","During recording","During performance","Movie/video screen capture","A bright coloured fish",
		"Illustration","Band/artist logotype","Publisher/Studio logotype"];ID3v2.b.APIC=function(d,f,c,a,b){var b=b||"3",a=d,p=g(c.a(d));switch(b){case "2":var h=c.c(d+1,3);d+=4;break;case "3":case "4":h=c.e(d+1,f-(d-a),p),d+=1+h.g}b=c.a(d,1);b=i[b];p=c.e(d+1,f-(d-a),p);d+=1+p.g;return{format:h.toString(),type:b,description:p.toString(),data:c.n(d,a+f-d)}};ID3v2.b.COMM=function(d,f,c){var a=d,b=g(c.a(d)),i=c.c(d+1,3),h=c.e(d+4,f-4,b);d+=4+h.g;d=c.e(d,a+f-d,b);return{language:i,X:h.toString(),text:d.toString()}};
		ID3v2.b.COM=ID3v2.b.COMM;ID3v2.b.PIC=function(d,f,c,a){return ID3v2.b.APIC(d,f,c,a,"2")};ID3v2.b.PCNT=function(d,f,c){return c.O(d)};ID3v2.b.CNT=ID3v2.b.PCNT;ID3v2.b["T*"]=function(d,f,c){var a=g(c.a(d));return c.e(d+1,f-1,a).toString()};ID3v2.b.TCON=function(){return ID3v2.b["T*"].apply(this,arguments).replace(/^\(\d+\)/,"")};ID3v2.b.TCO=ID3v2.b.TCON;ID3v2.b.USLT=function(d,f,c){var a=d,b=g(c.a(d)),i=c.c(d+1,3),h=c.e(d+4,f-4,b);d+=4+h.g;d=c.e(d,a+f-d,b);return{language:i,I:h.toString(),U:d.toString()}};
		ID3v2.b.ULT=ID3v2.b.USLT})();(function(g){function i(c,a,b,d){var g=c.h(a,!0);if(g==0)d();else{var e=c.c(a+4,4);["moov","udta","meta","ilst"].indexOf(e)>-1?(e=="meta"&&(a+=4),c.f([a+8,a+8+8],function(){i(c,a+8,g-8,d)})):c.f([a+(e in f.l?0:g),a+g+8],function(){i(c,a+g,b,d)})}}function d(c,a,b,g,h){for(var h=h===void 0?"":h+"  ",e=b;e<b+g;){var i=a.h(e,!0);if(i==0)break;var k=a.c(e+4,4);if(["moov","udta","meta","ilst"].indexOf(k)>-1){k=="meta"&&(e+=4);d(c,a,e+8,i-8,h);break}if(f.l[k]){var s=a.q(e+16+1),j=f.l[k],s=f.types[s];if(k==
			"trkn")c[j[0]]=a.a(e+16+11),c.count=a.a(e+16+13);else{var k=e+16+4+4,o=i-16-4-4;switch(s){case "text":c[j[0]]=a.e(k,o,"UTF-8");break;case "uint8":c[j[0]]=a.r(k);break;case "jpeg":case "png":c[j[0]]={m:"image/"+s,data:a.n(k,o)}}}}e+=i}}var f=g.v={};f.types={0:"uint8",1:"text",13:"jpeg",14:"png",21:"uint8"};f.l={"\u00a9alb":["album"],"\u00a9art":["artist"],"\u00a9ART":["artist"],aART:["artist"],"\u00a9day":["year"],"\u00a9nam":["title"],"\u00a9gen":["genre"],trkn:["track"],"\u00a9wrt":["composer"],
		"\u00a9too":["encoder"],cprt:["copyright"],covr:["picture"],"\u00a9grp":["grouping"],keyw:["keyword"],"\u00a9lyr":["lyrics"],"\u00a9gen":["genre"]};f.o=function(c,a){c.f([0,7],function(){i(c,0,c.j(),a)})};f.p=function(c){var a={};d(a,c,0,c.j());return a};g.ID4=g.v})(this);


	if(typeof map != "object")
		map = {};

	jQuery.mbMiniPlayer = {
		author  : "Matteo Bicocchi",
		version : "1.7.6",
		name    : "mb.miniPlayer",
		isMobile: false,

		icon    : {
			play      : "P",
			pause     : "p",
			stop      : "S",
			rewind    : "R",
			volume    : "Vm",
			volumeMute: "Vm"
		},
		defaults: {
			ogg                 :null,
			m4a                 :null,
			width               : 150,
			skin                : "black", // available: black, blue, orange, red, gray or use the skinMaker tool to create your.
			volume              : .5,
			autoplay            : false,
			animate             : true,
			id3                 : false,
			playAlone           : true,
			loop                : false,
			inLine              : false,
			volumeLevels        : 12,
			showControls        : true,
			showVolumeLevel     : true,
			showTime            : true,
			showRew             : true,
			addShadow           : true,
			downloadable        : false,
			downloadablesecurity: false,
			downloadPage        :null,
			swfPath             : "inc/",
			onPlay              : function () {},
			onEnd               : function () {}
		},

		getID3        : function (player) {

			if(!player.opt.id3)
				return;

			var $titleBox = player.controlBox.find(".map_title");
			var url = (player.opt.mp3 || player.opt.m4a);
			if (url && typeof ID3 == "object") {

				ID3.loadTags(url, function () {
					var info = {};
					info.title = ID3.getTag(url, "title");
					info.artist = ID3.getTag(url, "artist");
					info.album = ID3.getTag(url, "album");
					info.track = ID3.getTag(url, "track");

					if (info.title != undefined){
						$titleBox.html(info.title + " - " + info.artist);
					}
				})
			}
		},

		buildPlayer: function (options) {

			this.each(function (idx) {

				if (this.isInit)
					return;

				this.isInit = true;

				var $master = jQuery(this);
				$master.hide();
				var url = $master.attr("href");
				var playerID = "mp_" + ($master.attr("id") ? $master.attr("id") : new Date().getTime());
				var title = $master.html();

				// There are serious problems with the player events and Android default browser.
				// the default HTML5 player is used on that case.
				if(isAndroidDefault){
					var androidPlayer = jQuery("<audio/>").attr({src: url, controls: "controls"}).css({display:"block"});
					$master.after(androidPlayer);

					return;
				}

				var $player = jQuery("<div/>").attr({id: "JPL_" + playerID});
				var player = $player.get(0);
				player.opt = {};
				jQuery.extend(player.opt, jQuery.mbMiniPlayer.defaults, options);

				jQuery.mbMiniPlayer.isMobile = 'ontouchstart' in window;
				jQuery.mbMiniPlayer.eventEnd = jQuery.mbMiniPlayer.isMobile ? "touchend" : "mouseup";

				player.idx = idx+1;
				player.title = title;

				player.opt.isIE = jQuery.browser.msie ;//&& jQuery.browser.version === 9;

				this.player = player;

				if (jQuery.metadata) {
					jQuery.metadata.setType("class");
					jQuery.extend(player.opt, $master.metadata());
				}

				if (player.opt.width.toString().indexOf("%") >= 0) {

					var margin = player.opt.downloadable ? 220 : 180;
					var pW = $master.parent().outerWidth() - margin;
					player.opt.width = (pW * (parseFloat(player.opt.width))) / 100;

				} else if(player.opt.width == 0){
					player.opt.showControls = false;
				}

				if (jQuery.mbMiniPlayer.isMobile) { //'ontouchstart' in window

					player.opt.showVolumeLevel = false;
					player.opt.autoplay = false;
					player.opt.downloadable = false;

				}

				if (!player.opt.mp3 && url.indexOf("mp3")>0)
					player.opt.mp3 = url;
				if (!player.opt.m4a && url.indexOf("m4a")>0)
					player.opt.m4a = url;
				if( typeof player.opt.mp3 == "undefined")
					player.opt.mp3 = null;
				if( typeof player.opt.m4a == "undefined")
					player.opt.m4a = null;

				var skin = player.opt.skin;

				var $controlsBox = jQuery("<div/>").attr({id: playerID, isPlaying: false, tabIndex: player.idx }).addClass("mbMiniPlayer").addClass(skin);
				player.controlBox = $controlsBox;

				if (player.opt.inLine)
					$controlsBox.css({display: "inline-block", verticalAlign: "middle"});
				if (player.opt.addShadow)
					$controlsBox.addClass("shadow");
				var $layout = "<table cellpadding='0' cellspacing='0' border='0'><tr><td></td><td></td><td></td><td></td><td></td><td></td></tr></table>";
				jQuery("body").append($player);
				$master.after($controlsBox);
				$controlsBox.html($layout);

				var fileUrl = encodeURI(player.opt.mp3 || player.opt.m4a);
				var fileExtension = fileUrl.substr((Math.max(0, fileUrl.lastIndexOf(".")) || Infinity) + 1);
				var fileName = encodeURI(fileUrl.replace("."+fileExtension, "").split("/").pop());

				var download = jQuery("<span/>").addClass("map_download").css({display: "inline-block", cursor: "pointer"}).html("d").on(jQuery.mbMiniPlayer.eventEnd,function () {
					jQuery.mbMiniPlayer.saveFile(player, fileUrl, fileName, fileExtension);

				}).attr("title", "download: " + fileName);

				if (player.opt.downloadable) {
					$controlsBox.append(download);
				}

				var $tds = $controlsBox.find("td").unselectable();

				var $volumeBox = jQuery("<span/>").addClass("map_volume").html(jQuery.mbMiniPlayer.icon.volume);
				var $volumeLevel = jQuery("<span/>").addClass("map_volumeLevel").html("").hide();
				for (var i = 0; i < player.opt.volumeLevels; i++) {
					$volumeLevel.append("<a/>")
				}
				var $playBox = jQuery("<span/>").addClass("map_play").html(jQuery.mbMiniPlayer.icon.play);
				var $rewBox = jQuery("<span/>").addClass("map_rew").html(jQuery.mbMiniPlayer.icon.rewind).hide();
				var $timeBox = jQuery("<span/>").addClass("map_time").html("").hide();

				var $controls = jQuery("<div/>").addClass("map_controls");
				var $titleBox = jQuery("<span/>").addClass("map_title").html(player.title);
				var $progress = jQuery("<div/>").addClass("jp-progress");

				var $loadBar = jQuery("<div/>").addClass("jp-load-bar").attr("id", "loadBar_" + playerID);
				var $playBar = jQuery("<div/>").addClass("jp-play-bar").attr("id", "playBar_" + playerID);
				$progress.append($loadBar);
				$loadBar.append($playBar);
				$controls.append($titleBox).append($progress);

				$tds.eq(0).append($volumeBox);
				$tds.eq(1).append($volumeLevel).hide();
				$tds.eq(2).addClass("map_controlsBar").append($controls).hide();
				$tds.eq(3).append($timeBox).hide();
				$tds.eq(4).append($rewBox).hide();
				$tds.eq(5).append($playBox);

				player.opt.media = {};
				player.opt.supplied = [];

				if(player.opt.mp3){
					player.opt.media.mp3 = player.opt.mp3;
					player.opt.supplied.push("mp3");
				}
				if(player.opt.m4a){
					player.opt.media.m4a = player.opt.m4a;
					player.opt.supplied.push("m4a");
				}
				if(player.opt.ogg){
					player.opt.media.oga = player.opt.ogg;
					player.opt.supplied.push("oga");
				}

				player.opt.supplied = player.opt.supplied.toString();

				//init jPlayer component (Happyworm Ltd - http://www.jplayer.org)
				$player.jPlayer({

					ready  : function () {
						var el = jQuery(this);

						el.jPlayer("setMedia", player.opt.media);

						if(player.opt.mp3){
							jQuery.mbMiniPlayer.getID3(player);
						}

						function animatePlayer(anim) {

							if (anim == undefined)
								anim = true;

							var speed = anim ? 500 : 0;

							var isIE = jQuery.browser.msie && jQuery.browser.version < 9;

							if (!player.isOpen) {

								if(player.opt.showControls){
									$controls.parent("td").show()
									$controls.css({display: "block", height: 20}).animate({width: player.opt.width}, speed);
								}

								if (player.opt.showRew) {
									$rewBox.parent("td").show()
									if (isIE)
										$rewBox.show().css({width: 20, display: "block"});
									else
										$rewBox.show().animate({width: 20}, speed / 2);
								}
								if (player.opt.showTime) {
									$timeBox.parent("td").show()
									if (isIE)
										$timeBox.show().css({width: 34, display: "block"});
									else
										$timeBox.animate({width: 34}, speed / 2).show();
								}
								if (player.opt.showVolumeLevel) {
									$volumeLevel.parent("td").show()
									if (isIE)
										$volumeLevel.show().css({width: 40, display: "block"});
									else
										$volumeLevel.show().animate({width: 40}, speed / 2);
								}
							} else {
								$controls.animate({width: 1}, speed, function () {
									jQuery(this).parent("td").css({display: "none"})
								});
								if (player.opt.showRew) {
									$rewBox.animate({width: 1}, speed / 2, function () {
										jQuery(this).parent("td").css({display: "none"})
									});
								}
								if (player.opt.showTime) {
									$timeBox.animate({width: 1}, speed / 2, function () {
										jQuery(this).parent("td").css({display: "none"})
									});
								}
								if (player.opt.showVolumeLevel) {
									$volumeLevel.animate({width: 1}, speed / 2, function () {
										jQuery(this).parent("td").css({display: "none"})
									});
								}
							}
						}

						if (!player.opt.animate)
							animatePlayer(false)

						$playBox.on(jQuery.mbMiniPlayer.eventEnd, function (e) {

							if (!player.isOpen) {

								if (player.opt.animate)
									animatePlayer();

								player.isOpen = true;

								jQuery.mbMiniPlayer.actualPlayer = $master;

								if (player.opt.playAlone) {
									jQuery("[isPlaying='true']").find(".map_play").trigger(jQuery.mbMiniPlayer.eventEnd);
								}

								jQuery(this).html(jQuery.mbMiniPlayer.icon.pause);

								el.jPlayer("play");
								$controlsBox.attr("isPlaying", "true");

								//add track for Google Analytics
								if (typeof _gaq != "undefined")
									_gaq.push(['_trackEvent', 'Audio', 'Play', player.title]);

								if (typeof player.opt.onPlay == "function")
									player.opt.onPlay(player);

							} else {

								if (player.opt.animate)
									animatePlayer();

								player.isOpen = false;

								jQuery(this).html(jQuery.mbMiniPlayer.icon.play);

								$controlsBox.attr("isPlaying", "false");
								el.jPlayer("pause");
							}

							e.stopPropagation();
							return false;

						}).hover(
								function () {
									jQuery(this).css({opacity: .8})
								},
								function () {
									jQuery(this).css({opacity: 1})
								}
						);

						$volumeBox.on(jQuery.mbMiniPlayer.eventEnd,
								function () {
									if (jQuery(this).hasClass("mute")) {
										jQuery(this).removeClass("mute");
										jQuery(this).html(jQuery.mbMiniPlayer.icon.volume);
										el.jPlayer("volume", player.opt.vol);
									} else {
										jQuery(this).addClass("mute");
										jQuery(this).html(jQuery.mbMiniPlayer.icon.volumeMute);
										player.opt.vol = player.opt.volume;
										el.jPlayer("volume", 0);
									}
								}).hover(
								function () {
									jQuery(this).css({opacity: .8})
								},
								function () {
									jQuery(this).css({opacity: 1})
								}
						);

						$rewBox.on(jQuery.mbMiniPlayer.eventEnd, function () {
							el.jPlayer("playHead", 0);
						}).hover(
								function () {
									jQuery(this).css({opacity: .8})
								},
								function () {
									jQuery(this).css({opacity: 1})
								}
						);

						var bars = player.opt.volumeLevels;
						var barVol = 1 / bars;
						$volumeLevel.find("a").each(function (i) {
							jQuery(this).css({opacity: .3, height: "80%", width: Math.floor(35 / bars)});
							var IDX = Math.floor(player.opt.volume / barVol) - 1;
							if (player.opt.volume < .1 && player.opt.volume > 0)
								IDX = 0;

							$volumeLevel.find("a").css({opacity: .1}).removeClass("sel");
							for (var x = 0; x <= IDX; x++) {
								$volumeLevel.find("a").eq(x).css({opacity: .4}).addClass("sel");
							}

							jQuery(this).on(jQuery.mbMiniPlayer.eventEnd, function () {
								var vol = (i + 1) * barVol;
								el.jPlayer("volume", vol);
								if (i == 0) el.jPlayer("volume", .1);
								$volumeBox.removeClass("mute");
							});
						});
						// autoplay can't work on devices
						if (!jQuery.mbMiniPlayer.isMobile && player.opt.autoplay && ((player.opt.playAlone && jQuery("[isPlaying=true]").length == 0) || !player.opt.playAlone))
							$playBox.trigger(jQuery.mbMiniPlayer.eventEnd);
					},
					supplied           : player.opt.supplied,
					wmode              : "transparent",
					smoothPlayBar      : true,
					volume             : player.opt.volume,
					swfPath            : player.opt.swfPath,
					solution           : player.opt.isIE ? 'flash' : 'html, flash',
					preload            : isDevice ? 'none' : 'metadata',
					cssSelectorAncestor: "#" + playerID, // Remove the ancestor css selector clause
					cssSelector        : {
						playBar: "#playBar_" + playerID,
						seekBar: "#loadBar_" + playerID
						// The other defaults remain unchanged
					}
				})
						.on(jQuery.jPlayer.event.play, function (e) {})
						.on(jQuery.jPlayer.event.ended, function () {

							if(isAndroidDefault)
								return;

							if (player.opt.loop)
								$player.jPlayer("play");
							else
								$playBox.trigger(jQuery.mbMiniPlayer.eventEnd);
							if (typeof player.opt.onEnd == "function")
								player.opt.onEnd(player);
						})
						.on(jQuery.jPlayer.event.timeupdate, function (e) {

							player.duration = e.jPlayer.status.duration;
							player.currentTime = e.jPlayer.status.currentTime;
							player.seekPercent = e.jPlayer.status.seekPercent;

							$timeBox.html(jQuery.jPlayer.convertTime(e.jPlayer.status.currentTime)).attr("title", jQuery.jPlayer.convertTime(e.jPlayer.status.duration));
						})
						.on( jQuery.jPlayer.event.volumechange,function (event) {
							var bars = player.opt.volumeLevels;
							var barVol = 1 / bars;
							player.opt.volume = event.jPlayer.options.volume;
							var IDX = Math.floor(player.opt.volume / barVol) - 1;
							if (player.opt.volume < .1 && player.opt.volume > 0)
								IDX = 0;

							$volumeLevel.find("a").css({opacity: .1}).removeClass("sel");
							for (var x = 0; x <= IDX; x++) {
								$volumeLevel.find("a").eq(x).css({opacity: .4}).addClass("sel");
							}
						})

				$controlsBox.on("keypress",function(e){

					if (e.charCode == 32) { //toggle play
						$master.mb_miniPlayer_toggle();
						e.preventDefault();
						e.stopPropagation();
					}
					if (e.charCode == 43) { // volume +

						var bars = player.opt.volumeLevels;
						var barVol = 1 / bars;

						var vol = player.opt.volume + barVol;

						if(vol>1)
							vol = 1;

						$player.jPlayer("volume", vol);
						$volumeBox.removeClass("mute");

						e.preventDefault();
						e.stopPropagation();
					}
					if (e.charCode == 45) { //volume -

						var bars = player.opt.volumeLevels;
						var barVol = 1 / bars;

						var vol = player.opt.volume - barVol;
						if(vol<0)
							vol = 0;

						$player.jPlayer("volume", vol);

						if(vol<=0)
							$volumeBox.addClass("mute");

						e.preventDefault();
						e.stopPropagation();
					}
				})
			})
		},

		changeFile : function (media, title) {
			var ID = jQuery(this).attr("id");
			var $controlsBox = jQuery("#mp_" + ID);
			var $player = jQuery("#JPL_mp_" + ID);
			var player = $player.get(0);
			var $titleBox = $controlsBox.find(".map_title");

			if (!media.ogg) media.ogg = null;
			if (!media.mp3) media.mp3 = null;
			if (!media.m4a) media.m4a = null;

			if (!title) title = "audio file";
			$player.jPlayer("setMedia", media);

			if ($controlsBox.attr("isPlaying") == "true")
				$player.jPlayer("play");
			$titleBox.html(title);

			jQuery.mbMiniPlayer.getID3(player);
		},

		saveFile      : function (player, fileUrl, fileName, fileExtension) {
			var host = location.hostname.split(".");
			host = host.length == 3 ? host[1] : host[0];
			var isSameDomain = (fileUrl.indexOf(host) >= 0) || fileUrl.indexOf("http") <0;

			var a = document.createElement('a');
			if (!player.opt.downloadPage && isSameDomain && typeof a.download != "undefined") {

				var downloadA = jQuery("<a/>").attr({id:"mb_dwnl",href: fileUrl, download: fileName+"."+fileExtension}).html("dwnload")//.hide();
				jQuery("body").append(downloadA);

				function fakeClick(anchorObj) {
					if (anchorObj.click) {
						anchorObj.click()
					} else if(document.createEvent) {
						var evt = document.createEvent("MouseEvents");
						evt.initMouseEvent("click", true, true, window,
								0, 0, 0, 0, 0, false, false, false, false, 0, null);
						var allowDefault = anchorObj.dispatchEvent(evt);
					}
				}
				fakeClick(downloadA.get(0));
				downloadA.remove();

			} else if(!player.opt.downloadPage) {
				window.open(fileUrl, "map_download");
			} else {
				/* player.opt.downloadPage = path to the PHP page that stream the file and download it.*/
				location.href = player.opt.downloadPage + "?filename=" + fileName +"."+ fileExtension + "&fileurl=" + fileUrl;
			}
		},

		play       : function () {
			return this.each(function () {
				var id = jQuery(this).attr("id");
				var $player = jQuery("#mp_" + id);

				if ($player.attr("isplaying") === "false")
					$player.find(".map_play").trigger(jQuery.mbMiniPlayer.eventEnd);
			})
		},

		stop       : function () {
			return this.each(function () {
				var id = jQuery(this).attr("id");
				var $player = jQuery("#mp_" + id);
				if ($player.attr("isplaying") === "true")
					$player.find(".map_play").trigger(jQuery.mbMiniPlayer.eventEnd);
			})
		},

		toggle       : function () {
			return this.each(function () {
				var id = jQuery(this).attr("id");
				var player = this.player;
				var $player = player.controlBox;
				$player.find(".map_play").trigger(jQuery.mbMiniPlayer.eventEnd);
			})
		},

		destroy    : function () {
			return this.each(function () {
				var id = this.attr("id");
				var $player = jQuery("#mp_" + id);
				$player.remove();
			})
		},

		getPlayer  : function () {
			var id = this.attr("id");
			return jQuery("#mp_" + id);
		}
	};

	jQuery.fn.unselectable = function () {
		this.each(function () {
			jQuery(this).css({
				"-moz-user-select"  : "none",
				"-khtml-user-select": "none",
				"user-select"       : "none"
			}).attr("unselectable", "on");
		});
		return jQuery(this);
	};
	//Public method
	jQuery.fn.mb_miniPlayer = jQuery.mbMiniPlayer.buildPlayer;
	jQuery.fn.mb_miniPlayer_changeFile = jQuery.mbMiniPlayer.changeFile;
	jQuery.fn.mb_miniPlayer_play = jQuery.mbMiniPlayer.play;
	jQuery.fn.mb_miniPlayer_stop = jQuery.mbMiniPlayer.stop;
	jQuery.fn.mb_miniPlayer_toggle = jQuery.mbMiniPlayer.toggle;
	jQuery.fn.mb_miniPlayer_destroy = jQuery.mbMiniPlayer.destroy;
	jQuery.fn.mb_miniPlayer_getPlayer = jQuery.mbMiniPlayer.getPlayer;

	jQuery(document).on("keypress.mbMiniPlayer",function(e){

		if (e.keyCode == 32) {

			if(jQuery(e.target).is("textarea, input, [contenteditable]") || jQuery(e.target).parents().is("[contenteditable]"))
				return;

			if (jQuery.mbMiniPlayer.actualPlayer){
				jQuery.mbMiniPlayer.actualPlayer.mb_miniPlayer_toggle();
				e.preventDefault();
			}
		}
	});

})(jQuery);

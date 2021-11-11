// ovládání klávesami

// funkce zavádějící ovládání klávesami
jQuery.texyla.initPlugin(function () {
	var _this = this;

	this.textarea.bind(jQuery.browser.opera ? "keypress" : "keydown", function(e) {
		_this.keys(e);
	});
});


jQuery.texyla.extend({
	keys: function(e) {
		var pressedKey = e.charCode || e.keyCode || -1;
	
		var action = false;
	
		// tučně (Ctrl + B nebo např. Shift + Ctrl + B)
		if (e.ctrlKey && pressedKey == 66 && !e.altKey) {			
			this.texy.bold();
			action = true;				
		}
	
		// kurzíva (Ctrl + I nebo např. Alt + Ctrl + I)
		if (e.ctrlKey && pressedKey == 73) {
			this.texy.italic();
			action = true;
		}

		// Zrušit odsazení (shift + tab)
		if (pressedKey == 9 && e.shiftKey) {
			this.texy.unindent();
			action = true;
		}
	
		// tabulátor (tab)
		if (pressedKey == 9 && !e.shiftKey) {
			if (this.texy.update().text().indexOf(this.texy.lf()) == -1) {
				this.texy.tag('\t', '');
			} else {
				this.texy.indent();
			}
			action = true;
		}
	
		// Odeslat formulář (Ctrl + S nebo např. Shift + Ctrl + S)
		if (e.ctrlKey && pressedKey == 83) {
			this.submit();
			action = true;
		}
	
		// zruší defaultní akce
		if (action) {
			// Firefox & Opera (ale ta na to docela sere co se týče klávesových zkratek programu)
			if (e.preventDefault && e.stopPropagation) {
				e.preventDefault();
				e.stopPropagation();
	
			// IE
			} else {
				window.event.cancelBubble = true;
				window.event.returnValue = false;
			}
		}			
	}
});



// Zvětšovací textarea
jQuery.texyla.initPlugin(function () {
	// pokud není načteno jQuery UI resizable, nic se nedělá
	if (typeof(this.textarea.resizable) != "function") return;

	var _this = this;
	this.textarea.resizable({
		handles: 's',
		minHeight: 80,
		stop: function () {
			_this.textareaHeight = _this.textarea.get(0).offsetHeight;
		}
	});

	// fix
	this.textarea.parent().css("padding-bottom", 0);
});


// Okno obrázku
jQuery.texyla.addWindow("img", {
	createContent: function () {
		return jQuery(
			'<div><table><tbody><tr>' +
				// Adresa
				'<th><label>' + this.lng.imgSrc + '</label></th>' +
				'<td><input type="text" class="src"></td>' +
			'</tr><tr>' +
				// Alt
				'<th><label>' + this.lng.imgAlt + '</label></th>' +
				'<td><input type="text" class="alt"></td>' +
			'</tr><tr>' +
				// Zobrazit jako popisek
				'<td></td>' +
				'<td><label><input type="checkbox" class="descr">' + this.lng.imgDescription + '</label></td>' +
			'</tr><tr>' +
				// Zarovnání
				'<th><label>' + this.lng.imgAlign + '</label></th>' +
				'<td><select class="align">' +
					'<option value="*">' + this.lng.imgAlignNone + '</option>' +
					'<option value="<">' + this.lng.imgAlignLeft + '</option>' +
					'<option value=">">' + this.lng.imgAlignRight + '</option>' +
					'<option value="<>">' + this.lng.imgAlignCenter + '</option>' +
				'</select></td>' +
			'</tr></tbody></table></div>'
		);
	},

	action: function (el) {
		this.texy.img(
			el.find(".src").val(),
			el.find(".alt").val(),
			el.find(".align").val(),
			el.find(".descr").get(0).checked
		);
	},
	
	dimensions: [350, 250]
});


jQuery.texyla.addWindow("table", {
	dimensions: [320, 200],

	action: function (cont) {
		this.texy.table(cont.find(".cols").val(), cont.find(".rows").val(), cont.find(".header").val());
	},

	createContent: function () {
		var _this = this;

		var cont = jQuery(
			"<div style='position:relative'>" +
				'<table class="table"><tbody>' +
				'<tr><th><label>' + this.lng.tableCols + '</label></th><td><input type="number" class="cols" size="3" maxlength="2" min="1" value="2"></td></tr>' +
				'<tr><th><label>' + this.lng.tableRows + '</label></th><td><input type="number" class="rows" size="3" maxlength="2" min="1" value="2"></td></tr>' +
				'<tr><th><label>' + this.lng.tableTh + '</label></th><td><select class="header">' +
				'<option>' + this.lng.tableThNone + '</option>' +
				'<option value="n">' + this.lng.tableThTop + '</option>' +
				'<option value="l">' + this.lng.tableThLeft + '</option>' +
				'</select></td></tr></tbody></table>' +

				// vizuální tabulka - html
				'<div class="tab-background"><div class="tab-selection"></div><div class="tab-control"></div></div>' +
			"</div>"
		);

		// vizuální tabulka
		var resizing = true, posX, posY;

		// povolení nebo zakázání změny velikosti po kliku
		cont.find(".tab-control").click(function (e) {
			resizing = !resizing;

		// změny velikosti apos
		}).mousemove(function (e) {
			if (resizing) {
				posX = e.pageX;
				var el = this;
				while (el.offsetParent) {
					posX -= el.offsetLeft;
					el = el.offsetParent;
				}

				posY = e.pageY;
				el = this;
				while (el.offsetParent) {
					posY -= el.offsetTop;
					el = el.offsetParent;
				}

				var cols = Math.ceil(posX / 8);
				var rows = Math.ceil(posY / 8);

				cont.find(".tab-selection").css({
					width: cols * 8,
					height: rows * 8
				});

				cont.find(".cols").val(cols);
				cont.find(".rows").val(rows);
			}

		// vložení na dvojklik
		}).dblclick(function () {
			_this.getWindowAction("table").call(_this, cont);
			cont.dialog("close");
		});

		cont.find(".cols, .rows").bind("change click blur", function () {
			var cols = Math.min(cont.find(".cols").val(), 10);
			var rows = Math.min(cont.find(".rows").val(), 10);

			cont.find(".tab-selection").css({
				width: cols * 8,
				height: rows * 8
			});
		});

		return cont;
	}
});



jQuery.texyla.addWindow("link", {
	dimensions: [330, 180],
	
	createContent: function () {
		return jQuery(
			'<div><table><tbody><tr>' +
				'<th><label>' + this.lng.linkText + '</label></th>' +
				'<td><input type="text" class="link-text" value="' + this.texy.trimSelect().text() + '"></td>' +
			'</tr><tr>' +
				'<th><label>' + this.lng.linkUrl + '</label></th>' +
				'<td><input type="text" class="link-url" value="http://"></td>' +
			'</tr></tbody></table></div>'
		);
	},
	
	action: function (el) {
		var txt = el.find(".link-text").val();
		txt = txt == '' ? '' : '"' + txt + '":';
		this.texy.replace(txt + el.find(".link-url").val());
	}
});




// Výchozí zvláštní znaky
jQuery.texyla.setDefaults({
	symbols: [
		"&", "@", ["<", "&lt;"], [">", "&gt;"], "[", "]", "{", "}", "\\", 
		"α", "β", "π", "µ", "Ω", "∑", "°", "∞", "≠", "±", "×", "÷", "≥",
		"≤", "®", "™", "€", "£", "$", "~", "^", "·", "•"
	]
});

jQuery.texyla.addWindow("symbol", {
	dimensions: [300, 230],

	createContent: function () {
		var _this = this;
		
		var el = jQuery('<div></div>');
		var symbolsEl = jQuery('<div class="symbols"></div>').appendTo(el);

		var symbols = this.options.symbols;

		// projít symboly
		for (var i = 0; i < symbols.length; i++) {
			function clk(text) {
				return function () {
					_this.texy.replace(text);

					if (el.find("input.close-after-insert").get(0).checked) {
						el.dialog("close");
					}
				}
			};

			jQuery("<span class='ui-state-default'></span>")
				.hover(function () {
					jQuery(this).addClass("ui-state-hover");
				}, function () {
					jQuery(this).removeClass("ui-state-hover");
				})
				.text(symbols[i] instanceof Array ? symbols[i][0] : symbols[i])
				.click(clk(symbols[i] instanceof Array ? symbols[i][1] : symbols[i]))
				.appendTo(symbolsEl);
		}

		// kontrolka na zavření po vložení
		el.append(
			"<br><label><input type='checkbox' checked class='close-after-insert'> " +
			this.lng.windowCloseAfterInsert + "</label>"
		);

		return el;
	}
});



jQuery.texyla.addWindow("textTransform", {
	createContent: function () {
		return jQuery(
			"<div><form>" +
			"<label><input type='radio' name='changeCase' value='lower'> " + this.lng.textTransformLower + "</label><br>" +
			"<label><input type='radio' name='changeCase' value='upper'> " + this.lng.textTransformUpper + "</label><br>" +
			"<label><input type='radio' name='changeCase' value='firstUpper'> " + this.lng.textTransformFirstUpper + "</label><br>" +
			"<label><input type='radio' name='changeCase' value='cap'> " + this.lng.textTransformCapitalize + "</label><br>" +
			"<label><input type='radio' name='changeCase' value='url'> " + this.lng.textTransformUrl + "</label>" +
			"</form></div>"
		);
	},

	action: function (el) {
		var text = this.texy.update().text();
		var newText = null;

		var transformation = el.find("form input:checked").val();

		switch (transformation) {
			case "lower":
				newText = text.toLowerCase();
				break;
			case "upper":
				newText = text.toUpperCase();
				break;
			case "cap":
				newText = text.replace(/\S+/g, function (a) {
					return a.charAt(0).toUpperCase() + a.substr(1, a.length).toLowerCase();
				});
				break;
			case "firstUpper":
				newText = text.charAt(0).toUpperCase() + text.substr(1, text.length).toLowerCase();
				break;
			case "url":
				// (c) Jakub Vrána, http://php.vrana.cz
				var nodiac = {
					'á': 'a', 'č': 'c', 'ď': 'd', 'é': 'e', 'ě': 'e', 'í': 'i', 'ň': 'n',
					'ó': 'o', 'ř': 'r', 'š': 's', 'ť': 't', 'ú': 'u', 'ů': 'u', 'ý': 'y',
					'ž': 'z'
				};

				var s = text.toLowerCase();
				var s2 = '';
				for (var i=0; i < s.length; i++) {
					s2 += (typeof nodiac[s.charAt(i)] != 'undefined' ? nodiac[s.charAt(i)] : s.charAt(i));
				}
				newText = s2.replace(/[^a-z0-9_]+/g, '-').replace(/^-|-$/g, '');
				break;
			default:
		}

		// replace
		if (newText !== null) {
			this.texy.replace(newText);
		}
	},

	dimensions: [220, 210]
});



// nastavení
$.texyla.setDefaults({
	emoticonPath: "%texyla_base%/emoticons/texy/%var%.gif",
	emoticons: {
		':-)': 'smile',
		':-(': 'sad',
		';-)': 'wink',
		':-D': 'biggrin',
		'8-O': 'eek',
		'8-)': 'cool',
		':-?': 'confused',
		':-x': 'mad',
		':-P': 'razz',
		':-|': 'neutral'		
	}
});

$.texyla.initPlugin(function () {
	this.options.emoticonPath = this.expand(this.options.emoticonPath);
});

$.texyla.addWindow("emoticon", {
	createContent: function () {
		var _this = this;

		var emoticons = $('<div></div>');
		var emoticonsEl = $('<div class="emoticons"></div>').appendTo(emoticons);

		// projít smajly
		for (var i in this.options.emoticons) {
			function emClk(emoticon) {
				return function () {
					_this.texy.replace(emoticon);

					if (emoticons.find("input.close-after-insert").get(0).checked) {
						emoticons.dialog("close");
					}
				}
			};

			$(
				"<img src='" + this.options.emoticonPath.replace("%var%", this.options.emoticons[i]) +
				"' title='" + i + "' alt='" + i + "' class='ui-state-default'>"
			)
				.hover(function () {
					$(this).addClass("ui-state-hover");
				}, function () {
					$(this).removeClass("ui-state-hover");
				})
				.click(emClk(i))
				.appendTo(emoticonsEl);
		}

		emoticons.append("<br><label><input type='checkbox' checked class='close-after-insert'> " + this.lng.windowCloseAfterInsert + "</label>");

		return emoticons;
	},

	dimensions: [192, 170]
});


jQuery.texyla.setDefaults({
	youtubeMakro: "[* youtube:%var% *]"
});

jQuery.texyla.addWindow("youtube", {
	createContent: function () {
		var el = jQuery(
			"<div><form><div>" +
			'<label>' + this.lng.youtubeUrl + '<br>' +
			'<input type="text" size="35" class="key">' +
			"</label><br><br>" +
			this.lng.youtubePreview + '</div>' +
			'<div class="thumb"></div>' +
			"</form></div>"
		);

		el.find(".key").bind("keyup change", function () {
			var val = this.value;
			var key = "";

			if (val.substr(0, 7) == "http://") {
				var res = val.match("[?&]v=([a-zA-Z0-9_-]+)");
				if (res) key = res[1];
			} else {
				key = val;
			}

			jQuery(this).data("key", key);

			el.find(".thumb").html(
				'<img src="http://img.youtube.com/vi/' + key + '/1.jpg" width="120" height="90">'
			);
		});

		return el;
	},

	action: function (el) {
		var txt = this.expand(this.options.youtubeMakro, el.find(".key").data("key"));
		this.texy.update().replace(txt);
	},

	dimensions: [320, 300]
});

jQuery.texyla.addStrings("cs", {
});



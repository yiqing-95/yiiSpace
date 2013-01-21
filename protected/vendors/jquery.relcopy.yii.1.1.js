/**
 * jquery.relcopy.yii.1.1.js
 * Version for Yii extension 'jqrelcopy' and 'multimodelform'
 * Added: beforeClone,afterClone,beforeNewId,afterNewId
 * @link http://www.yiiframework.com/extension/jqrelcopy
 * @link http://www.yiiframework.com/extension/multimodelform
 * @author: J. Blocher
 * -----------------------------------------------------------------
 * jQuery-Plugin "relCopy"
 *
 * @version: 1.1.0, 25.02.2010
 *
 * @author: Andres Vidal
 *          code@andresvidal.com
 *          http://www.andresvidal.com
 *
 * Instructions: Call $(selector).relCopy(options) on an element with a jQuery type selector
 * defined in the attribute "rel" tag. This defines the DOM element to copy.
 * @example: $('a.copy').relCopy({limit: 5}); // <a href="example.com" class="copy" rel=".phone">Copy Phone</a>
 *
 * @param: string	excludeSelector - A jQuery selector used to exclude an element and its children
 * @param: integer	limit - The number of allowed copies. Default: 0 is unlimited
 * @param: string	append - HTML to attach at the end of each copy. Default: remove link
 * @param: string	copyClass - A class to attach to each copy
 * @param: boolean	clearInputs - Option to clear each copies text input fields or textarea
 *
 */

(function($) {

	// Clear names on elements to reflect the fact they will need insertion to
	// the DB
	function resetName(element, counter){
		var re = new RegExp(/\[\d+\](?=\[)/);
		return element.attr('name').replace(re, "[" + counter + "]");		
	}
	
	// Clear ids on elements to reflect the fact they will need insertion to
	// the DB
	function resetId(element, counter){
		var re = new RegExp(/_\d/);
		if (element.attr('id').match(re)){
			return element.attr('id').replace(re, "_" + counter);
		}
		else {
			return element.attr('id') + (counter +1);
		}
	}
	

	$.fn.relCopy = function(options) {
		var settings = jQuery.extend({
			excludeSelector: ".exclude",
			emptySelector: ".empty",
			copyClass: "copy",
			append: '',
			clearInputs: true,
			limit: 0, // 0 = unlimited
			beforeClone: null,
			afterClone: null,
			beforeNewId: null,
			afterNewId: null
		}, options);

		settings.limit = parseInt(settings.limit);

		// loop each element
		this.each(function() {

			// set click action
			$(this).click(function(){
				var rel = $(this).attr('rel'); // rel in jquery selector format
				var counter = $(rel).length;

				// stop limit
				if (settings.limit != 0 && counter >= settings.limit){
					return false;
				};

				var funcBeforeClone = function(){eval(settings.beforeClone);};
				var funcAfterClone = function(){eval(settings.afterClone);};
				var funcBeforeNewId = function(){eval(settings.beforeNewId);};
				var funcAfterNewId = function(){eval(settings.afterNewId);};

				var master = $(rel+":first");
				funcBeforeClone.call(master);

				var parent = $(master).parent();
				var clone = $(master).clone(true).addClass(settings.copyClass+counter).append(settings.append);
                funcAfterClone.call(clone);

				//Remove Elements with excludeSelector
				if (settings.excludeSelector){
					$(clone).find(settings.excludeSelector).remove();
				};

				//Empty Elements with emptySelector
				if (settings.emptySelector){
					$(clone).find(settings.emptySelector).empty();
				};

				// Increment Clone IDs
				if ( $(clone).attr('id') ){
					funcBeforeNewId.call(clone);
					$(clone).attr('id', resetId($(clone), counter));
				    funcAfterNewId.call(clone);
				};

				// Increment Clone Children IDs
				$(clone).find('[id]').each(function(){
					funcBeforeNewId.call($(this));
					$(this).attr('id', resetId($(this), counter));
					funcAfterNewId.call($(this));
				});
				
				// Increment Clone names
				if ( $(clone).attr('name') ){
					$(clone).attr('name', resetName($(clone), counter));
				};
				// Increment Clone Children names
				$(clone).find('[name]').each(function(){
					$(this).attr('name', resetName($(this), counter));
				});
				
				//funcAfterNewId.call();

				//Clear Inputs/Textarea
				if (settings.clearInputs){
					$(clone).find(':input').each(function(){
						var type = $(this).attr('type');
						switch(type)
						{
							case "button":
								break;
							case "reset":
								break;
							case "submit":
								break;
							case "checkbox":
								$(this).removeAttr('checked');
								break;
							default:
							  $(this).val("");
						}
					});
				};

				$(parent).find(rel+':last').after(clone);
				return false;

			}); // end click action

		}); //end each loop

		return this; // return to jQuery
	};

})(jQuery);
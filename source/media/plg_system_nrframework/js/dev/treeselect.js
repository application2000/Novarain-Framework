/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2018 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

!(function(window, document){
	'use strict';

	var treeselect = {};

	treeselect.init = function(element) {

		if (!element) {
			throw new Error("Invalid Element");
		}

		jQuery(function($) {

			var el = element;
			var $el = $(element);
			var controls = $el.find("div.nr_treeselect-controls");
			var list = $el.find("ul.nr_treeselect-ul");
			var menu = $el.find("div.nr_treeselect-menu-block").html();
			var maxheight = list.css("max-height");

			list.find("li").each(function() {
				var $li = $(this);
				var $div = $li.find("div.nr_treeselect-item:first");
				$li.prepend('<span class="pull-left icon-"></span>');
				$div.after('<div class="clearfix"></div>');
				if ($li.find("ul.nr_treeselect-sub").length) {
					$li.find("span.icon-").addClass("nr_treeselect-toggle icon-minus");
					$div.find("label:first").after(menu);
					if (!$li.find("ul.nr_treeselect-sub ul.nr_treeselect-sub").length) $li.find("div.nr_treeselect-menu-expand").remove();
				}
			});

			list.find("span.nr_treeselect-toggle").on('click', function() {
				var $icon = $(this);
				if ($icon.parent().find("ul.nr_treeselect-sub").is(":visible")) {
					$icon.removeClass("icon-minus").addClass("icon-plus");
					$icon.parent().find("ul.nr_treeselect-sub").hide();
					$icon.parent().find("ul.nr_treeselect-sub span.nr_treeselect-toggle").removeClass("icon-minus").addClass("icon-plus");
				} else {
					$icon.removeClass("icon-plus").addClass("icon-minus");
					$icon.parent().find("ul.nr_treeselect-sub").show();
					$icon.parent().find("ul.nr_treeselect-sub span.nr_treeselect-toggle").removeClass("icon-plus").addClass("icon-minus");
				}
			});

			controls.find("input.nr_treeselect-filter").on('keyup', function() {
				var $text = $(this).val().toLowerCase();
				list.find("li").each(function() {
					var $li = $(this);
					if ($li.text().toLowerCase().indexOf($text) == -1) $li.hide();
					else $li.show();
				});
			});

			controls.find("a.nr_treeselect-checkall").on('click', function() {
				list.find("input").prop("checked", true);
			});
			controls.find("a.nr_treeselect-uncheckall").on('click', function() {
				list.find("input").prop("checked", false);
			});
			controls.find("a.nr_treeselect-toggleall").on('click', function() {
				list.find("input").each(function() {
					var $input = $(this);
					if ($input.prop("checked")) $input.prop("checked", false);
					else $input.prop("checked", true);
				});
			});
			
			controls.find("a.nr_treeselect-expandall").on('click', function() {
				list.find("ul.nr_treeselect-sub").show();
				list.find("span.nr_treeselect-toggle").removeClass("icon-plus").addClass("icon-minus");
			});
			controls.find("a.nr_treeselect-collapseall").on('click', function() {
				list.find("ul.nr_treeselect-sub").hide();
				list.find("span.nr_treeselect-toggle").removeClass("icon-minus").addClass("icon-plus");
			});

			controls.find("a.nr_treeselect-showall").on('click', function() {
				list.find("li").show();
			});
			controls.find("a.nr_treeselect-showselected").on('click', function() {
				list.find("li").each(function() {
					var $li = $(this);
					var $hide = true;
					$li.find("input").each(function() {
						if ($(this).prop("checked")) {
							$hide = false;
							return false;
						}
					});
					if ($hide) {
						$li.hide();
						return;
					}
					$li.show();
				});
			});
			controls.find("a.nr_treeselect-maximize").on('click', function() {
				list.css("max-height", "");
				controls.find("a.nr_treeselect-maximize").hide();
				controls.find("a.nr_treeselect-minimize").show();
			});
			controls.find("a.nr_treeselect-minimize").on('click', function() {
				list.css("max-height", maxheight);
				controls.find("a.nr_treeselect-minimize").hide();
				controls.find("a.nr_treeselect-maximize").show();
			});

			// Check / Uncheck all
			el.querySelectorAll(".checkall, .uncheckall").forEach(function(el) {
				el.addEventListener('click', function() {
					var value = this.classList.contains("checkall") ? true : false;
					
			 		this.closest(".nr_treeselect-item").parentNode.querySelectorAll(":scope .nr_treeselect-sub input").forEach(function(el) {
						el.checked = value;
					})
				})
			})
			
			// Expand / Collapse All
			el.querySelectorAll(".expandall, .collapseall").forEach(function(el) {
				el.addEventListener('click', function() {
					var expand = this.classList.contains("expandall") ? true : false;
					var parent = this.closest('.nr_treeselect-item').parentNode;

					parent.querySelectorAll("ul.nr_treeselect-sub").forEach(function(el) {
						el.style.display = (expand) ? 'block' : 'none';
					});
	
					var toggle = parent.querySelector("ul.nr_treeselect-sub span.nr_treeselect-toggle");

					toggle.classList.remove((expand) ? 'icon-plus' : 'icon-minus');
					toggle.classList.add(expand ? 'icon-minus' : 'icon-plus');
				})
			})
		});

		return true;
	};

	/** Instantiate the treeselect when the document is ready */
	document.addEventListener("DOMContentLoaded", function() {
		var elements, i;
		elements = document.querySelectorAll(".nr_treeselect");
		for (i = 0; i < elements.length; i++) {
			treeselect.init(elements[i]);
		}
	});	

	window.NRTreeselect = treeselect;

})(window, document);

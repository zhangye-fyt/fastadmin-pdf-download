!function(){"use strict";"object"==typeof elementor&&elementor.channels.editor.on("section:activated",function(e,t){var i,o,n,t=t.getOption("editedElementView");null!=e&&("tm-flip-box"===(i=t.model.get("widgetType"))&&(o=!1,-1===e.indexOf("back_side_section")&&-1===e.indexOf("button_style_section")||(o=!0),t.$el.toggleClass("minimog-flip-box--flipped",o),n=t.$el.find(".back-side"),o&&n.css("transition","none"),o||setTimeout(function(){n.css("transition","")},10)),"tm-heading"===i&&"wrapper_style_section"===e?t.$el.addClass("minimog-heading-wrapper-editing"):t.$el.removeClass("minimog-heading-wrapper-editing"),"swiper_arrows_style_section"===e?t.$el.addClass("minimog-swiper-arrows-editing"):t.$el.removeClass("minimog-swiper-arrows-editing"),"markers_popup_style_section"===e?t.$el.addClass("minimog-map-marker-overlay-editing"):t.$el.removeClass("minimog-map-marker-overlay-editing"))}),window.MinimogElementor={helpers:{getRepeaterSelectOptionText:function(e,t,i,o){var n=elementor.documents.currentDocument.config.widgets,n=(n[e],n[e].controls),e=(n[t],n[t]);return"object"==typeof e&&"object"==typeof e.fields?(n=e.fields[i],_.unescape(n.options[o])):""},getRepeaterTextForProductTabs:function(e,t,i,o){var n=elementor.documents.currentDocument.config.widgets,n=(n[e],n[e].controls),e=(n[t],n[t]);return"object"==typeof e&&"object"==typeof e.fields?(n=e.fields[i],_.unescape(n.options[o])):""}}}}(jQuery);
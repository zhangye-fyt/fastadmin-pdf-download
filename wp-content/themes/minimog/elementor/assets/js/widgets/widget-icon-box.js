!function(n){"use strict";function e(n,e){var i=n.find(".tm-icon-box");elementorFrontend.waypoint(i,function(){var n,e,t,o=i.data("vivus");o&&"yes"===o.enable&&0<(n=i.find(".minimog-svg-icon")).length&&0<(n=n.children("svg").not(".svg-defs-gradient")).length&&(e={type:o.type,duration:o.duration,animTimingFunction:Vivus.EASE_OUT},t&&t.destroy(),t=new Vivus(n[0],e,function(){}),"yes"===o.play_on_hover)&&i.hover(function(){t.stop().reset().play(2)},function(){})})}n(window).on("elementor/frontend/init",function(){elementorFrontend.hooks.addAction("frontend/element_ready/tm-animated-icon-box.default",e)})}(jQuery);
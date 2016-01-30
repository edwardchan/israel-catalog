/*	
 *	jQuery carouFredSel 2.0.2
 *	Demo's and documentation:
 *	caroufredsel.frebsite.nl
 *	
 *	Copyright (c) 2010 Fred Heusschen
 *	www.frebsite.nl
 *
 *	Licensed under the MIT license.
 *	http://www.opensource.org/licenses/mit-license.php
 */

(function($) {
	$.fn.carouFredSel = function(options) {
		return this.each(function() {
			var opts 			= $.extend(true, {}, $.fn.carouFredSel.defaults, options),
				$ul 			= $(this),
				$items 			= $("> *", $ul),
				totalItems		= $items.length,
				itemWidth		= $items.outerWidth(true),
				itemHeight		= $items.outerHeight(true),
				firstItem		= 0,
				autoInterval	= null,
				direction		= (opts.direction == 'up' || opts.direction == 'left') ? 'next' : 'prev';

			if (opts.scroll.items == 0 ||
				opts.scroll.items == null) 	opts.scroll.items = opts.visibleItems;
			
			opts.auto		= $.fn.carouFredSel.getNaviObject(opts.auto, false, true);
			opts.prev		= $.fn.carouFredSel.getNaviObject(opts.prev);
			opts.next		= $.fn.carouFredSel.getNaviObject(opts.next);
			opts.pagination	= $.fn.carouFredSel.getNaviObject(opts.pagination, true);

			opts.auto 		= $.extend({}, 	opts.scroll,	opts.auto);
			opts.prev 		= $.extend({}, 	opts.scroll,	opts.prev);
			opts.next 		= $.extend({}, 	opts.scroll,	opts.next);
			opts.pagination	= $.extend({}, 	opts.scroll,	opts.pagination);

			if (typeof(	opts.pagination.anchorBuilder)	!= 'function')			opts.pagination.anchorBuilder 	= $.fn.carouFredSel.pageAnchorBuilder;
			if (typeof(	opts.pagination.keys) 			!= 'boolean')			opts.pagination.keys 			= false;
			if (typeof(	opts.auto.play) 				!= 'boolean')			opts.auto.play 					= true;
			if (typeof(	opts.auto.delay)				!= 'number')			opts.auto.delay					= 0;
			if (typeof(	opts.auto.pauseDuration)		!= 'number')			opts.auto.pauseDuration			= opts.auto.duration * 6;
			if (		opts.auto.pauseDuration 		== 	opts.auto.duration) opts.auto.duration--;

			opts.prev.name 			= 'prev';
			opts.next.name 			= 'next';
			opts.pagination.name	= 'pagination';

			if (opts.visibleItems >= totalItems) {
				$.fn.carouFredSel.log('Not enough items: terminating');
				if (opts.prev.button) 			opts.prev.button.hide();
				if (opts.next.button) 			opts.next.button.hide();
				if (opts.pagination.container) 	opts.pagination.container.hide();
				return;
			}

			if (opts.direction == "right" ||
				opts.direction == "left"
			) {
				var directionSize = ['width', itemWidth * 2];
				var cs2 = {
					width	: opts.width	|| itemWidth * opts.visibleItems,
					height	: opts.height 	|| itemHeight
				};
			} else {
				var directionSize = ['height', itemHeight * 2];
				var cs2 = {
					height	: opts.height	|| itemHeight * opts.visibleItems,
					width	: opts.width	|| itemWidth
				};
			}

			$ul.css(directionSize[0], directionSize[1] * totalItems).css({
				position	: 'absolute'
			}).wrap('<div class="caroufredsel_wrapper" />').parent().css(cs2).css({ 
				position	: "relative",
				overflow	: "hidden"
			});

			$ul
				.bind('pause', function() {
					if (autoInterval != null) {
						clearTimeout(autoInterval);
					}
				})
				.bind('play', function(e, d, f) {
					if (opts.auto.play) {
						if (typeof(d) == 'undefined') 	d = direction;
						if (typeof(f) == 'undefined') 	f = 0;

						autoInterval = setTimeout(function() {
							if ($ul.is(":animated")) 	$ul.trigger('pause').trigger('play', d);	//	still animating, wait
							else 						$ul.trigger(d, opts.auto);					//	scroll
						}, opts.auto.pauseDuration + f);
					}
				})
				.bind('prev', function(e, sliderObj, numItems) {
					if ($ul.is(":animated")) return;
					if (opts.visibleItems >= totalItems) return;

					if (typeof(sliderObj) == 'number')	numItems  	= sliderObj;
					if (typeof(sliderObj) != 'object')	sliderObj 	= opts.prev;
					if (typeof(numItems)  != 'number')	numItems 	= sliderObj.items;

					if (!numItems || typeof(numItems) != 'number') {
						$.fn.carouFredSel.log('Not a valid number.');
						return;
					}

					$ul.find("> *:gt("+(totalItems-numItems-1)+")").prependTo($ul);

					if (totalItems < opts.visibleItems+numItems) {
						$ul.find("> *:lt("+((opts.visibleItems+numItems)-totalItems)+")").clone(true).appendTo($ul);
					}

						firstItem -= numItems;
					if (firstItem < 0) firstItem += totalItems;

					var currentItems = $.fn.carouFredSel.getCurrentItems($ul, opts, numItems);

					if (opts.direction == "right" ||
						opts.direction == "left"
					) {
						var pos = 'left',
							siz = itemWidth;
					} else {
						var pos = 'top',
							siz = itemHeight;
					}

					var css = {},
						ani = {};

					css[pos] = -(siz * numItems);
					ani[pos] = 0;

					if (sliderObj.onBefore) {
						sliderObj.onBefore(currentItems[1], currentItems[0], sliderObj.name);
					}
					$ul
						.data("numItems", 	numItems)
						.data("sliderObj", 	sliderObj)
						.data("oldItems", 	currentItems[1])
						.data("newItems", 	currentItems[0])
						.css(css)
						.animate(ani, { 
							duration: sliderObj.duration,
							easing	: sliderObj.easing,
							complete: function() {
								if (totalItems < opts.visibleItems+$ul.data("numItems")) {
									$ul.find("> *:gt("+(totalItems-1)+")").remove();
								}
								if ($ul.data("sliderObj").onAfter) {
									$ul.data("sliderObj").onAfter($ul.data("oldItems"), $ul.data("newItems"), $ul.data("sliderObj").name);
								}
							}
						});

					//	auto-play
					$ul.trigger('updatePageStatus')
						.trigger('pause').trigger('play', 'prev');					
				})
				.bind('next', function(e, sliderObj, numItems) {
					if ($ul.is(":animated")) return;
					if (opts.visibleItems >= totalItems) return;

					if (typeof(sliderObj) == 'number')	numItems  	= sliderObj;
					if (typeof(sliderObj) != 'object')	sliderObj 	= opts.next;
					if (typeof(numItems)  != 'number')	numItems 	= sliderObj.items;

					if (!numItems || typeof(numItems) != 'number') {
						$.fn.carouFredSel.log('Not a valid number.');
						return;
					}
					if (totalItems < opts.visibleItems+numItems) {
						$ul.find("> *:lt("+((opts.visibleItems+numItems)-totalItems)+")").clone(true).appendTo($ul);
					}

						firstItem += numItems;
					if (firstItem >= totalItems) firstItem -= totalItems;

					var currentItems = $.fn.carouFredSel.getCurrentItems($ul, opts, numItems);

					if (opts.direction == "right" ||
						opts.direction == "left"
					) {
						var pos = 'left',
							siz = itemWidth;
					} else {
						var pos = 'top',
							siz = itemHeight;
					}
					var ani = {},
						cal = {};

					ani[pos] = -(siz * numItems);
					cal[pos] = 0;

					if (sliderObj.onBefore) {
						sliderObj.onBefore(currentItems[0], currentItems[1], sliderObj.name);
					}

					$ul
						.data("numItems", 	numItems)
						.data("sliderObj", 	sliderObj)
						.data("oldItems", 	currentItems[0])
						.data("newItems", 	currentItems[1])
						.animate(ani, { 
							duration: sliderObj.duration,
							easing	: sliderObj.easing,
							complete: function() {
								if ($ul.data("sliderObj").onAfter) {
									$ul.data("sliderObj").onAfter($ul.data("oldItems"), $ul.data("newItems"), $ul.data("sliderObj").name);
								}
								if (totalItems < opts.visibleItems+$ul.data("numItems")) {
									$ul.find("> *:gt("+(totalItems-1)+")").remove();
								}
								$ul.css(cal).find("> *:lt("+$ul.data("numItems")+")").appendTo($ul);
							}
						});

					//	auto-play
					$ul.trigger('updatePageStatus')
						.trigger('pause').trigger('play', 'next');
				})
				.bind("scrollTo", function(e, n, d, o) {
					if ($ul.is(":animated")) return;

						 if (typeof(n) == 'string') n = parseInt(n);
						 if (typeof(n) == 'object') n = $ul.find('> *').index(n);
					else if (typeof(n) == 'number') {
						n += -firstItem + totalItems;
						if (n >= totalItems) n -= totalItems;
					}
					
					if (typeof(n) != 'number' || n == -1) {
						$.fn.carouFredSel.log('Not a valid number.');
						return;
					}

					if (typeof(d) == 'string') d = parseInt(d);
					if (typeof(d) != 'number') d = 0;

					if (typeof(o) != 'object') o = false;

					n += d;
					if (n < 0) 				n += totalItems;
					if (n >= totalItems)	n -= totalItems;
					if (n == 0) return;

					if (n < totalItems / 2) $ul.trigger("next", [o, n]);
					else					$ul.trigger("prev", [o, totalItems-n]);
				})
				.bind("slideTo", function(e, n, d, o) {
					$ul.trigger("scrollTo", [n, d, o]);
				})
				.bind("insertItem", function(e, i, n) {
					if (typeof(i) == 'string') i = $(i);
					if (typeof(i) != 'object' ||
						typeof(i.jquery) == 'undefined' ||
						i.length == 0
					) {
						$.fn.carouFredSel.log('Not a valid object.');
						return;
					}

					if (typeof(n) == 'undefined')	n = 'after';
					if (typeof(n) == 'number') 		n = n-1;
					if (typeof(n) == 'string')	{
							 if (n == 'before') 	n = totalItems-1;
						else if (n == 'after')		n = opts.visibleItems-1;
						else if (n == 'end') 		n = totalItems-firstItem-1;
						else 						n = $(n);
					}
					if (typeof(n) == 'object')		n = $ul.find('> *').index(n)-1;
					if (typeof(n) != 'number') {
						$.fn.carouFredSel.log('Not a valid number.');
						return;
					}

					var nulItem = totalItems - firstItem;
					if (nulItem >= totalItems) nulItem -= totalItems;
					if (n >= nulItem && nulItem > 0) {
						firstItem += i.length;
					}

					var $itms = $ul.find('> *');
					if ($itms.length < 2) {
						if (n == -1)	$ul.prepend(i);
						else			$ul.append(i);
					} else {
						if (n == -1) 	$itms.filter(':nth(0)').before(i);
						else			$itms.filter(':nth('+n+')').after(i);
					}

					$items 		= $ul.find('> *');
					totalItems 	= $items.length;
					$ul.css(directionSize[0], directionSize[1] * totalItems)
						.trigger('updatePageStatus', true);
				})
				.bind("removeItem", function(e, i) {
					if (typeof(i) == 'object' &&
						typeof(i.jquery) == 'undefined') 	i = $(i);
					if (typeof(i) == 'string')				i = $(i);
					if (typeof(i) == 'number')				i = $ul.find('> *:nth('+i+')');
					
					if (typeof(i) != 'object' ||
						typeof(i.jquery) == 'undefined' ||
						i.length == 0
					) {
						$.fn.carouFredSel.log('Not a valid object.');
						return;
					}

					var n = $ul.find('> *').index(i);
					var nulItem = totalItems - firstItem;
					if (nulItem >= totalItems) nulItem -= totalItems;
					if (n >= nulItem && nulItem > 0)	firstItem -= i.length;
					else if (nulItem - i.length == 0)	firstItem = 0;

					i.remove();

					$items 		= $ul.find('> *');
					totalItems 	= $items.length;
					$ul.css(directionSize[0], directionSize[1] * totalItems)
						.trigger('updatePageStatus', true);
				})
				.bind('updatePageStatus', function(e, bpa) {
					if (opts.pagination.container == null ||
						opts.pagination.container.length == 0) return false;

					if (typeof(bpa) == 'boolean' && bpa) {
						opts.pagination.container.find('> *').remove();
						for (var a = 0; a < Math.ceil(totalItems/opts.visibleItems); a++) {
							opts.pagination.container.append(opts.pagination.anchorBuilder(a+1));
						}
						opts.pagination.container.find('> *').each(function(a) {
							$(this).unbind('click').click(function() {
								$ul.trigger('scrollTo', [a * opts.visibleItems, 0, opts.pagination]);
								return false;
							});
						});
					}

					var nr = Math.round(firstItem / opts.visibleItems);
					opts.pagination.container.find('> *')
						.removeClass('selected')
						.filter(':nth('+nr+')').addClass('selected');
				});


			if (opts.auto.pauseOnHover && opts.auto.play) {
				$ul.hover(
					function() { $ul.trigger('pause'); },
					function() { $ul.trigger('play', direction); }
				);
			}

			//	via prev-button
			if (opts.prev.button != null &&
				opts.prev.button.length
			) {
				opts.prev.button.click(function() {
					$ul.trigger('prev');
					return false;
				});
				if (opts.prev.pauseOnHover && opts.auto.play) {
					opts.prev.button.hover(
						function() { $ul.trigger('pause'); },
						function() { $ul.trigger('play', direction); }
					);
				}
			}

			//	via next-button
			if (opts.next.button != null &&
				opts.next.button.length
			) {
				opts.next.button.click(function() {
					$ul.trigger("next");
					return false;
				});
				if (opts.next.pauseOnHover && opts.auto.play) {
					opts.next.button.hover(
						function() { $ul.trigger("pause"); },
						function() { $ul.trigger("play", direction); }
					);
				}
			}

			//	via pagination
			if (opts.pagination.container != null &&
				opts.pagination.container.length
			) {

				$ul.trigger('updatePageStatus', true);

				if (opts.pagination.pauseOnHover && opts.auto.play) {
					opts.pagination.container.hover(
						function() { $ul.trigger("pause"); },
						function() { $ul.trigger("play", direction); }
					);
				}
			}

			//	via keyboard
			if (opts.next.key ||
				opts.prev.key
			) {
				$(document).keyup(function(e) {
					var k = e.keyCode;
					if (k == opts.next.key)	$ul.trigger("next");
					if (k == opts.prev.key)	$ul.trigger("prev");
				});
			}
			if (opts.pagination.keys) {
				$(document).keyup(function(e) {
					var k = e.keyCode;
					if (k >= 49 && k < 58) {
						k = (k - 49) * opts.visibleItems;
						if (k <= totalItems) {
							$ul.trigger('scrollTo', [k, 0, opts.pagination]);
						}
					}
				});
			}

			//	via auto-play
			$ul.trigger("play", [direction, opts.auto.delay]);
		});
	}

	$.fn.carouFredSel.defaults = {
		height				: null,
		width				: null,
		visibleItems		: 5,
		direction			: 'left',
		scroll : {
			items				: null,
			easing				: 'swing',
			duration			: 500,							
			pauseOnHover		: false,
			onBefore			: null,
			onAfter				: null
		}
	};
	$.fn.carouFredSel.getKeyCode = function(string) {
		if (string == 'right')	return 39;
		if (string == 'left') 	return 37;
		if (string == 'up')		return 38;
		if (string == 'down')	return 40;
		return -1;
	};
	$.fn.carouFredSel.getNaviObject = function(obj, pagi, auto) {
		if (typeof(pagi) != 'boolean')	pagi = false;
		if (typeof(auto) != 'boolean')	auto = false;

		if (typeof(obj) == 'undefined') 			obj  = {};
		if (typeof(obj) == 'string') {
			var temp = $.fn.carouFredSel.getKeyCode(obj);
			if (temp == -1)							obj = $(obj);
			else									obj = temp;
		}

		if (pagi) {
			if (typeof(obj.jquery) != 'undefined')	obj = { container: obj };
			if (typeof(obj) == 'boolean')			obj = { keys: obj };
			if (typeof(obj.container) == 'string')	obj.container = $(obj.container);

		} else if (auto) {
			if (typeof(obj) == 'boolean')			obj = { play: obj };
			if (typeof(obj) == 'number')			obj = { pauseDuration: obj };

		} else {
			if (typeof(obj.jquery) != 'undefined')	obj = { button: obj };
			if (typeof(obj) == 'number')			obj = { key: obj };
			if (typeof(obj.button) == 'string')		obj.button = $(obj.button);
			if (typeof(obj.key) == 'string')		obj.key = $.fn.carouFredSel.getKeyCode(obj.key);
		}
		return obj;
	};
	$.fn.carouFredSel.getCurrentItems = function($u, o, n) {
		var oi = $u.find("> *:lt("+o.visibleItems+")"),
			ni = $u.find("> *:lt("+(o.visibleItems+n)+"):gt("+(n-1)+")");

		return [oi, ni];
	};
	$.fn.carouFredSel.log = function(msg) {
		try { console.log('carouFredSel: '+msg); } catch(err) {};
	};
	$.fn.carouFredSel.pageAnchorBuilder = function(nr) {
		return '<a href="#"><span>'+nr+'</span></a>';
	};
	
})(jQuery);
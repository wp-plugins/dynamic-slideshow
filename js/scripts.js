jQuery(document).ready(function($){
	// generic images preloader
	$('#content img, #billboard img').each(function(){
		var image = this;
		$(image).css({opacity:0});
		var tester = function(){
			if(image.complete){
				$(image).animate({opacity: 1}, {duration: 700});
				clearInterval(interval);
			}
		}
		
		var interval = setInterval(tester, 300);
	});
	
	// inner slider
	$('.inner-slider').each(function(){
		$(this).cycle({
			delay:parseInt($('.inner-slider-delay', this).remove().text()),
			height:$('.inner-slider-height', this).remove().text()
		});
	});
	
	// menu
	$('#menu a').attr('title', '');
	$('#menu>ul>li>ul').show().slideUp(0).css('opacity', 0.7);
	$('#menu>ul>li:has(ul)').hover(function(){
		$('ul:first', this).stop(true, true).slideDown(400).animate({opacity: 1}, {duration: 400, queue: false});
	}, function(){
		$('ul:first', this).stop(true, true).slideUp(400).animate({opacity: 0.7}, {duration: 400});
	});
	
	// search box handling
	var original_field_value = $('#top-search').val();
	$('#top-search').focus(function(){
		$(this).val('');
	}).blur(function(){
		if($(this).val() == ''){
			$(this).val(original_field_value);
		}
	});
	
	// fix WP125
	$('.wp125ad').parent().append("<div class='clear'></div>");
	
	// portfolio setup
	
	curve = function(el, inOut){
		$('img', el).stop().animate({
			left: inOut == 'out' ? '320px' : '0px', 
			top: inOut == 'out' ? '240px' : '0px'
		}, {
			specialEasing: {
				left: 'easeOutQuad', top: 'easeInQuad'
			},
			queue: false,
			duration: 400
		});
	}
	/*
	left = function(el, inOut){
		$('img', el).stop().animate({
			left: inOut == 'out' ? '-320px' : '0px'
		}, {
			specialEasing: {
				left: 'easeOutQuad'
			},
			queue: false,
			duration: 400
		});
	}
	
	right = function(el, inOut){
		$('img', el).stop().animate({
			left: inOut == 'out' ? '320px' : '0px'
		}, {
			specialEasing: {
				left: 'easeOutQuad'
			},
			queue: false,
			duration: 400
		});
	}
	
	up = function(el, inOut){
		$('img', el).stop().animate({
			top: inOut == 'out' ? '-240px' : '0px'
		}, {
			specialEasing: {
				top: 'easeInOutQuad'
			},
			queue: false,
			duration: 400
		});
	}
	
	down = function(el, inOut){
		$('img', el).stop().animate({
			top: inOut == 'out' ? '240px' : '0px'
		}, {
			specialEasing: {
				top: 'easeInOutQuad'
			},
			queue: false,
			duration: 400
		});
	}
	*/
	$('.portfolio-item').each(function(){
		var func = $('.anim', this).text();
		$(this).hover(function(){
			eval(func+"(this, 'out')")
		}, function(){
			eval(func+"(this, 'in')")
		});
	});
	
	
	// Sidebar slideshow
	$('.uds-slideshow-widget .images img').each(function(i, el){
		var link = $('<div>' + (i + 1) + '</div>');
		if(i != 0) {
			$(this).hide();
		} else {
			$(link).addClass('active');
		}
		$(link).click(function(event){
			$('.uds-slideshow-widget .control div').removeClass('active');
			$(this).addClass('active');
			
			if($('.uds-slideshow-widget .images img:eq(' + i + ')').is(':visible')) return;
			
			$('.uds-slideshow-widget .images img:visible').animate({opacity: 0}, {
				duration: 800, 
				easing: 'easeOutExpo', 
				complete:function(){
					$(this).hide();
					$('.uds-slideshow-widget').height($(this).height());
				}
			});
			
			$('.uds-slideshow-widget .images img:eq(' + i + ')').show().css({opacity: 0}).animate({opacity: 1},{
				duration: 800,
				easing: 'easeOutExpo'
			});
		});
		$('.uds-slideshow-widget .control').append(link);
	});
	$('.uds-slideshow-widget').height($('.uds-slideshow-widget .images img:first').height());
	$('.uds-slideshow-widget .images img:first').load(function(){
		$('.uds-slideshow-widget').height($('.uds-slideshow-widget .images img:first').height());
	});
	
	// billboard
	if(parseInt(billboard_delay) < 2000) billboard_delay = 2000;
	else billboard_delay = parseInt(billboard_delay);
	
	if(billboard_type == 'cycle'){
		$("#billboard").cycle({
			timeout: billboard_delay
		});
	} else if(billboard_type == 'uBillboard'){
		$("#billboard").uBillboard({
			width: ss_width+'px',
			height: ss_height+'px',
			delay: billboard_delay,
			loader_image: template_url+'/images/image-preload.gif'
		});
	} else {
		var bbImgs = [];
		var bbCount = $("#billboard .billboard-item").size();
		var currentItem = 0;
		var minimizedWidth = Math.round(170 / (bbCount - 1)); // width of a minimized slide
		
		$(".billboard-description").css('opacity', 0.7);
		$("#billboard .billboard-item").each(function(i, el){
			var struct = {
				el: this,
				left: 770 * (i == 0 ? 0 : 1) + (minimizedWidth * (i == 0 ? i : i - 1)) + "px"
			}; 
			$(this).css({
				left: struct.left,
				width: i == 0 ? 770 : minimizedWidth + "px",
				zIndex: i
			}).find('.billboard-shadow-left').css('visibility', i == 0 ? 'hidden' : 'visible');
			$(this).data({left: i == 0 ? false : true, right: false});
			$('.billboard-shadow-right', this).css('visibility', 'hidden');
			
			if(i == 0){
				$(".billboard-description", this).animate({top: '300px'}, {duration: 500, easing: 'easeOutExpo'});
			}
			
			$(this).hoverIntent(function(){
				currentEl = this;
				prevItem = currentItem;
				currentItem = i;
				
				if(currentItem == prevItem) return;
				
				if(currentItem > prevItem){
					$("#billboard .billboard-item:lt("+(i+1)+")").each(function(j, elem){
						$(currentEl).find('.billboard-shadow-left,.billboard-shadow-right').css('visibility', 'hidden');
						$(currentEl).data('right', false).data('left', false);
						if($(elem).data('right') == false && j != i){
							$(elem).find('.billboard-shadow-right').css({
								visibility: 'visible',
								left: '700px'
							}).animate({left: minimizedWidth - 34 + "px"}, {duration: 730, easing: 'easeOutExpo'});
							$(elem).find('.billboard-shadow-left').css('visibility', 'hidden');
							$(elem).data('right', true).data('left', false);
						}
						
						$(".billboard-description").animate({top: '360px'}, {duration: 500, easing: 'easeOutExpo', queue: false });
						
						$(bbImgs[i-j].el).animate({
							left: minimizedWidth * (i - j) + 'px',
							width: j == 0 ? '770px' : minimizedWidth
						}, {
							duration: 700,
							easing: 'easeOutExpo',
							complete: function(){
								$(".billboard-description:not(:empty)", currentEl).animate(
									{top: '300px'}, 
									{ duration: 500, easing: 'easeOutExpo', queue: false }
								);
							}
						});
					});
					
				} else {
					$("#billboard .billboard-item:gt("+i+")").each(function(j, elem){
						$(currentEl).find('.billboard-shadow-left,.billboard-shadow-right').css('visibility', 'hidden');
						$(currentEl).data('right', false).data('left', false);
						if($(elem).data('left') == false){
							$(elem).find('.billboard-shadow-left').css({
								visibility: 'visible',
								left: 0 + "px"
							});
							if(!$.browser.msie) $(elem).find('.billboard-shadow-left').css('opacity', 0).animate({opacity: 1}, 700);
							$(elem).find('.billboard-shadow-right').css('visibility', 'hidden');
							$(elem).data('right', false).data('left', true);
						}
						
						$(".billboard-description").animate({top: '360px'}, {duration: 500, easing: 'easeOutExpo', queue: false });
						
						$(bbImgs[1+i+j].el).animate({
							left: bbImgs[1+i+j].left,
							width: minimizedWidth + "px"
						}, {
							duration: 700,
							easing: 'easeOutExpo',
							complete: function(){
								$(".billboard-description:not(:empty)", currentEl).animate(
									{top: '300px'}, 
									{ duration: 500, easing: 'easeOutExpo', queue: false }
								);
							}
						});
					});
					$(this).animate({
						width: '770px'
					}, {
						duration: 700,
						easing: 'easeOutExpo'
					});
				}
			}, function(){});
			bbImgs.push(struct);
		});
	}
});

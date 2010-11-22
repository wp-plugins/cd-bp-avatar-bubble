jQuery(document).ready(function($) {
	function getClientWidth() { return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth; }
	function getClientHeight() { return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight; }
	
	$(function() {
		var hideDelay = 0;
		var hideTimer = null;

		var container = $('<div id="popupContainer"><table width="" border="0" cellspacing="0" cellpadding="0" align="center" class="popupBubble"><tr><td class="corner topLeft"></td><td class="top"></td><td class="corner topRight"></td></tr><tr><td class="left">&nbsp;</td><td><div id="popupContent"></div></td><td class="right">&nbsp;</td></tr><tr><td class="corner bottomLeft">&nbsp;</td><td class="bottom">&nbsp;</td><td class="corner bottomRight"></td></tr></table></div>');

		$('body').append(container);

		$('.avatar').live('click', function(event) {

			event.preventDefault();
			
			var userID = $(this).attr('rel');
			if ( !userID ) return;

			if (hideTimer) clearTimeout(hideTimer);
			
			var pos = $(this).offset();
			var width = $(this).width();

            $('div#popupContainer').ajaxStart(function(){
                $('div#popupContent').html('<img src="'+ajax_image+'/ajax-loader.gif" alt="Loading" />');	
                var right = getClientWidth() - pos.left - width;
				var boxWidth = $('div#popupContainer').width();
				if ( boxWidth < right ) {
					container.css({
						left: (pos.left + width) + 'px',
						top: pos.top - 5 + 'px'
					});
				}else{
					container.css({
						left: (pos.left - boxWidth) + 'px',
						top: pos.top - 5 + 'px'
					})
				}
				container.css('display', 'block');
            });
            
			$.ajax({
				type: 'GET',
				url: ajax_url,
				data: {
                    ID: userID,
                    action: 'the_personalinfo'
                },
				success: function(data) {
                    // Get time dealy if any
                    var data = String(data).split('|~|');
                    //alert(data[0]);
                    if(data[0] > 0) {
                        var delay = data[0] * 1000;
                        setTimeout(function() {
                            // Show data
                            var text = $(data[1]).html();
                            $('div#popupContent').html(text);
                        }, delay);
                    }else{
                        // Show data
                        var text = $(data[1]).html();
                        $('div#popupContent').html(text);
                    }
				}
			});
  
		});

		$('.avatar').live('mouseout', function() {
			if (hideTimer) clearTimeout(hideTimer);
			hideTimer = setTimeout(function() { container.css('display', 'none'); }, hideDelay);
		});

		// Allow mouseover of details without hiding details
		$('#popupContainer').mouseover(function() {
			if (hideTimer)	clearTimeout(hideTimer);
		});

		// Hide after mouseout
		$('#popupContainer').mouseout(function() {
			if (hideTimer)	clearTimeout(hideTimer);
			hideTimer = setTimeout(function() {container.css('display', 'none');}, hideDelay);
		});
	});

	// Select all checkboxes after clicking the link All 
        $("a[href='#select_all']").click( function() {
           $("input:checkbox.link").attr('checked', 'checked');
            return false;
        });

});
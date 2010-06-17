jQuery(document).ready(function($) {
	function getClientWidth() {
		return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth;
	}
	function getClientHeight() {
		return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight;
	}
	
	$(function() {
		var hideDelay = 0;
		var hideTimer = null;

	  // One instance that's reused to show info for the current person
		var container = $('<div id="popupContainer">'
			+ '<table width="" border="0" cellspacing="0" cellpadding="0" align="center" class="popupBubble">'
			+ '<tr>'
			+ '	<td class="corner topLeft"></td>'
			+ '	<td class="top"></td>'
			+ '	<td class="corner topRight"></td>'
			+ '</tr><tr>'
			+ '	<td class="left">&nbsp;</td>'
			+ '	<td><div id="popupContent"></div></td>'
			+ '	<td class="right">&nbsp;</td>'
			+ '</tr><tr>'
			+ '	<td class="corner bottomLeft">&nbsp;</td>'
			+ '	<td class="bottom">&nbsp;</td>'
			+ '	<td class="corner bottomRight"></td>'
			+ '</tr>'
			+ '</table>'
			+ '</div>');

		$('body').append(container);

		$('.avatar').live('mouseover', function() {
			// format of 'rel' tag: userID
			var userID = $(this).attr('rel');
		  
			  // If no userID in url rel tag, don't popup blank
			if ( !userID)
				return;

			if (hideTimer)
				clearTimeout(hideTimer);
			
			var pos = $(this).offset();
			var width = $(this).width();

			$.ajax({
				type: 'GET',
				url: '/wp-content/plugins/cd-bp-avatar-bubble/ajax.php',
				data: 'ID=' + userID,
				success: function(data) {
				// Verify that we're pointed to a page that returned the expected results.
					if (data.indexOf('result') < 0) {
					}

				// Verify requested person is this person since we could have multiple ajax requests out if the server is taking a while.
				//$('#popupContent').html('<img src="/wp-content/plugins/cd-bp-avatar-bubble/_inc/images/loader.gif" style="border:none" />');
					if (data.indexOf(userID) > 0) {
						var text = $(data).html();
						$('div#popupContent').html(text);
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
					}
				}
			});
  
		});

		$('.avatar').live('mouseout', function() {
			if (hideTimer)
				clearTimeout(hideTimer);
			hideTimer = setTimeout(function() {
				container.css('display', 'none');
			}, hideDelay);
		});

		// Allow mouseover of details without hiding details
		$('#popupContainer').mouseover(function() {
			if (hideTimer)
				clearTimeout(hideTimer);
		});

		// Hide after mouseout
		$('#popupContainer').mouseout(function() {
			if (hideTimer)
				clearTimeout(hideTimer);
			hideTimer = setTimeout(function() {
				container.css('display', 'none');
			}, hideDelay);
		});
	});
});
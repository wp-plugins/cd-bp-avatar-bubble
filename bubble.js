jQuery(document).ready(function($) {
	$(function()
	{
	  var hideDelay = 200;
	  var userInfo;
	  var hideTimer = null;

	  // One instance that's reused to show info for the current person
	  var container = $('<div id="popupContainer">'
		  + '<table width="" border="0" cellspacing="0" cellpadding="0" align="center" class="popupBubble">'
		  + '<tr>'
		  + '   <td class="corner topLeft"></td>'
		  + '   <td class="top"></td>'
		  + '   <td class="corner topRight"></td>'
		  + '</tr>'
		  + '<tr>'
		  + '   <td class="left">&nbsp;</td>'
		  + '   <td><div id="popupContent"></div></td>'
		  + '   <td class="right">&nbsp;</td>'
		  + '</tr>'
		  + '<tr>'
		  + '   <td class="corner bottomLeft">&nbsp;</td>'
		  + '   <td class="bottom">&nbsp;</td>'
		  + '   <td class="corner bottomRight"></td>'
		  + '</tr>'
		  + '</table>'
		  + '</div>');

	  $('body').append(container);

	  $('.avatar').live('mouseover', function()
	  {
		  // format of 'rel' tag: userID
		var userID = $(this).attr('rel');
		  
		  // If no userID in url rel tag, don't popup blank
		 if ( !userID)
			 return;

		  if (hideTimer)
			  clearTimeout(hideTimer);

		  var pos = $(this).offset();
		  var width = $(this).width();
		  container.css({
			  left: (pos.left + width) + 'px',
			  top: pos.top - 5 + 'px'
		  });

		  $('#popupContent').html('<img src="/wp-content/plugins/cd-bp-avatar-bubble/images/loader.gif" style="border:none" />');

		  $.ajax({
			  type: 'GET',
			  url: '/wp-content/plugins/cd-bp-avatar-bubble/personalajax.php',
			  data: 'ID=' + userID,
			  success: function(data)
			  {
				  // Verify that we're pointed to a page that returned the expected results.
				  if (data.indexOf('result') < 0)
				  {
					  $('#popupContent').html('<span>There was an error while loading data about user ' + userID + '. <br>Try again later please...</span>');
				  }

				  // Verify requested person is this person since we could have multiple ajax requests out if the server is taking a while.
				  if (data.indexOf(userID) > 0)
				  {                  
					  var text = $(data).html();
					  $('#popupContent').html(text);
				  }
			  }
		  });

		  container.css('display', 'block');
	  });

	  $('.avatar').live('mouseout', function()
	  {
		  if (hideTimer)
			  clearTimeout(hideTimer);
		  hideTimer = setTimeout(function()
		  {
			  container.css('display', 'none');
		  }, hideDelay);
	  });

	  // Allow mouse over of details without hiding details
	  $('#popupContainer').mouseover(function()
	  {
		  if (hideTimer)
			  clearTimeout(hideTimer);
	  });

	  // Hide after mouseout
	  $('#popupContainer').mouseout(function()
	  {
		  if (hideTimer)
			  clearTimeout(hideTimer);
		  hideTimer = setTimeout(function()
		  {
			  container.css('display', 'none');
		  }, hideDelay);
	  });
	});
});
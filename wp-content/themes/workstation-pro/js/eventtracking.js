/* Event Tracking Methods */
function TrackEvent(Category, Action, Label, Value) {
    jQuery(document).ready(function () {        
        try
        {
			if (typeof Value == 'undefined')
			{
				Value = 1;
			}		
		
            ga('send', 'event', Category, Action, Label.toString(), Value);
			/*gtag('event', Action, {
			  'event_category': Category,
			  'event_label': Label.toString(),
			  'value': Value
			});*/
			
        }
        catch (err)
        {
            alert('Event Error\nCategory:' + Category + '\nAction:' + Action + '\nLabel:' + Label + '\nValue:' + Value + '\n ' + err.description);
        }
    });
}

function TrackPhoneNumberClicks() {
    try
    {
        jQuery("[href*='tel:']").click(function () {
            TrackEvent('Page Engagement', 'Clicked Phone Number', jQuery(this).attr("href"), 1);
			//event.preventDefault();
        });
    }
    catch (err) 
    {
    }
}

function TrackEmailClicks() {
    try
    {
        jQuery("[href*='mailto:']").click(function () {
			//alert("test");
            TrackEvent('Page Engagement', 'Clicked Email Address', jQuery(this).attr("href"), 1);
        });
    }
    catch (err)
    {
    }
}
function TrackWhatsClicks() {
    try
    {
        jQuery("[href*='https://api.whatsapp.com']").click(function () {
			//alert("test");
            TrackEvent('Page Engagement', 'Clicked WhatsApp Button', jQuery(this).attr("href"), 1);
        });
    }
    catch (err)
    {
    }
}
/*function TrackSecurityClick() {
    try
    {
        jQuery("#menu-item-2247").click(function () {
			//alert("test");
            TrackEvent('Page Engagement', 'Clicked Security Info', jQuery(this).attr("href"), 1);
        });
    }
    catch (err)
    {
    }
}*/

/* Exec event tracking functions */
jQuery(document).ready(function () {
	TrackEmailClicks();
    TrackPhoneNumberClicks();
	TrackWhatsClicks();
	jQuery('#menu-item-2247').click(function() {
	  window.open('https://www.sitelock.com/verify.php?site=globalag.ca','SiteLock','width=600,height=600,left=160,top=170');
	});
});


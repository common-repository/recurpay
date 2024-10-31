(function( $ ) {
	'use strict';
	var getLocation = window.location.origin;
      window.intercomSettings = {
        "api_base": "https://api-iam.intercom.io",
        "app_id": "lo1anqbh",
        "custom_launcher_selector":'#chat-widget',
        "Storefront URL": window.location.origin
      };
    
    (function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',w.intercomSettings);}else{var d=document;var i=function(){i.c(arguments);};i.q=[];i.c=function(args){i.q.push(args);};w.Intercom=i;var l=function(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/lo1anqbh';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);};if(document.readyState==='complete'){l();}else if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})();
    
	$(document).on("click",".btn-tab",function(e) {
		e.preventDefault();
		var getAttr = $(this).attr("id");
		$('.btn-tab').removeClass("setting-tabs-tab--selected");
		$(this).addClass("setting-tabs-tab--selected");
		$("[data-tab]").hide();
		$("[data-tab='"+getAttr+"']").show().addClass("setting-tabs-tab--selected");
	});
	$('.label-group-box:hidden').parents('tr').hide();
})( jQuery );

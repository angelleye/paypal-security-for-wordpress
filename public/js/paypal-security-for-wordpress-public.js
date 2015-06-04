jQuery( document ).ready(function() {
	 jQuery('form').each(function(){
			var cmdcode = jQuery(this).find('input[name="cmd"]').val();
			var bncode = jQuery(this).find('input[name="bn"]').val();
			
			if (cmdcode && bncode) {
				 jQuery('input[name="bn"]').val("AngellEYE_PHPClass");
			}else if ((cmdcode) && (!bncode )) {
				 jQuery(this).find('input[name="cmd"]').after("<input type='hidden' name='bn' value='AngellEYE_PHPClass' />");
			}
			
	 			
});
     
      
});

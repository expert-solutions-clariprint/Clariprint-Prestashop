
jQuery(function() {
	if (typeof Clariprint_ui === 'undefined') { return; };
	StartClariprint();
	Clariprint.ui = Clariprint_ui;
	Clariprint.api = Clariprint_api;
	Clariprint.productController = Clariprint_productController;
	Clariprint.startClient();
	$('.ClariprintProductController').projectController();
	$('#add_to_cart').hide();
	
});

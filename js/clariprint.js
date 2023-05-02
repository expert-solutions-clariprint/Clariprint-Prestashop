

DBGW = null;

StartClariprint = function() {
	
	jQuery('input[visibility-control]').each(function(idx,elem){
		var key = $(elem).attr('visibility-control');
		$(key).toggle(elem.checked);
		$(elem).change(function(){
			/*
			if ($(elem).is(':checked')) $(key).show();
			else $(key).hide(); */
			$(key).toggle(elem.checked);
		});
	});
	
	jQuery.widget("custom.deliveryselector",{
		country:null,
		zone:null,
		zones:{},
		_create: function()
		{
			this.country = this.element.find('[role=country]');
			this.country.change($.proxy(this.update,this));
			this.zone = this.element.find('[role=zone]');

			var sel = this.country[0];
			for(var i = 0; i < sel.options.length; i++)
			{
				var $z = $(sel.options[i]);
				this.zones[$z.attr('value')] = [];
			}
			var sel = this.zone[0];
			for(var i = 0; i < sel.options.length; i++)
			{
				var $z = $(sel.options[i]);
				this.zones[$z.attr('country')].push({ value: $z.val(), text: $z.text() });
			}
			this.update();
		},
		update:function()
		{
			console.log('deliveryselector Update');

			var filter = this.country.val();
			this.zone.find('option').remove();
			var options = this.zones[filter];
			for(var i = 0; i < options.length; i++ )
			{
				var z = options[i];
				console.log(i,z);
				this.zone.append(`<option value="${z.value}">${z.text}</option>`);
			}
		}
	});
	
	$('[role=delivery_selector]').deliveryselector();
	
	
	
	Clariprint = {
		lang : {},
		ui: "",
		api: "",
		admin: false,
		productController: null,
		bootstrap: function() { return Clariprint.ui.match(/bootstrap/); },
		bootstrap2: function() { return (Clariprint.ui == 'bootstrap'); },
		bootstrap3: function() { return Clariprint.ui.match(/bootstrap3/); },
		bootstrap4: function() { return Clariprint.ui.match(/bootstrap4/); },
		parseFloat:function(txt)
			{
				var x = parseFloat(txt.replace(',','.'));
				if (isNaN(x)) return 0.0;
				return x;
			},
		log: function(txt) {
				if (console.log) console.log(txt);
			},
		l: function(x)
			{
				if (Clariprint.lang.hasOwnProperty(x)) return Clariprint.lang[x];
				return  x;
			},
		setFolding: function(fold)
			{
				var width = Clariprint.parseFloat($(fold).closest('.clariprint_product').find('.ClWidth').val());
				var height = Clariprint.parseFloat($(fold).closest('.clariprint_product').find('.ClHeight').val());
				var hf = Clariprint.parseFloat($(fold).attr('foldheight'));
				var hw = Clariprint.parseFloat($(fold).attr('foldwidth'));
				$(fold).closest('.clariprint_product').find('.ClOpenHeight').val(hf*height);
				$(fold).closest('.clariprint_product').find('.ClOpenWidth').val(hw*width);
			}
	};

jQuery.fn.hasAttr = function(name) {  
	return this.attr(name) !== undefined;
};

jQuery.widget("custom.clint",{
	max : '',
	min : '',
	_create: function() {
		this.element.on('blur',jQuery.proxy(this.keyup,this));
		this.max = parseInt(this.element.attr('cl_max'));
		this.min = parseInt(this.element.attr('cl_min'));
		this.required = this.element.hasAttr('cl_required');
	},
	keyup: function(evt){
		var v = parseInt(this.element.val());
		if (this.max != '') {
			if (v > this.max) v = this.max;
		}
		if (this.min != '') {
			if (v < this.min) v = this.min;
		}
		if (isNaN(v)) v = '';

		if (this.required) {
			if (v == '') this.element.addClass("CLWarning");
			else this.element.removeClass("CLWarning");
		}

		this.element.val(v);
	}
});

jQuery.widget("custom.clfloat",{
	max : '',
	min : '',

	_create: function() {
		this.element.on('blur',jQuery.proxy(this.keyup,this));
		this.max = parseFloat(this.element.attr('cl_max'));
		this.min = parseFloat(this.element.attr('cl_min'));
	},
	keyup: function(evt){
		var v = parseFloat(this.element.val().replace(',','.').replace(/[^0-9.]+/g,''));
		if (this.max != '') {
			if (v > this.max) v = this.max;
		}
		if (this.min != '') {
			if (v < this.min) v = this.min;
		}
		if (isNaN(v)) this.element.addClass("CLWarning");
		else this.element.removeClass("CLWarning");
		this.element.val(v);
	}
});


jQuery.widget("custom.adminsolver", {
	_create: function(options) {
		$('#ClariprintAdminGetPrice').on('click',jQuery.proxy(this.getPrice,this));
	},

	getPrice: function(evt)
	{
		evt.stopPropagation();
		evt.preventDefault();
		
		var sid = $('#clariprint_solver_id');
//		var product = $('#product_form').serialize();
		var product = this.element.closest('form').serialize();
		$('#clariprint_solver_message').html("Calcul du prix ..");
		jQuery.ajax('/index.php',{
				'type': 'POST',
				'data' : {
					'fc': 'module',
					'module' : 'clariprint',
					'controller' : 'solver',
					'nodiscount' :1,
					'no_cache': 1,
					'ajax' : 1,
					'action' : 'PriceRequest',
					'content' : 'urlencoded',
					'product' : product,
					'id_server' : (sid.length ? sid.val() : null)
				},
				'dataType' : 'json',
				'error' : function( jqXHR,  textStatus,  errorThrown ) {
					alert( "Price request error "  + textStatus + " (" + errorThrown + ")");
				},
				'success':jQuery.proxy(this.getPriceDone,this)
			});
	},
	getPriceDone: function(data, textStatus, jqXHR) {
		if (data && data.success && data.response > 0 ) {
			$('#clariprint_resume').html(data.html);
			$('#details_produit').show();
			$('#priceTE').val(data.response);
			$('#priceTE').keyup();
			$('#clariprint_solver_message').html("prix mis à jour : " + data.priceHT + ". N'oubliez pas d'enregistrer");
		} else {
			$('#clariprint_solver_message').html("Error");
		}
	}
});

var WIDGET=null;

jQuery.widget("custom.solver", {
	lastresponse:null,
	interval:null,
	hash:null,
	defaults:{'live': true, 'button': null},
	options:null,
	validators:null,
	start_button:null,
	rfqDelay:2000,
	progress:null,
	$price:null,
	
	options: {
		div_short_description:'[itemprop="description"]',
		div_price:'.product-prices'
	},
	
	_create: function(options) {
		this.options = $.extend( this.options, this.defaults, options);
		
		$(this.options.div_price).hide();
		this.$price = $('<h5 class="clariprint_price"></h5>');
		$(this.options.div_price).before(this.$price);
		this.start_button = this.element.find('.start_solve');
		if (this.start_button.length > 0) {
			this.options.live = false;
			this.start_button.click(jQuery.proxy(this.startButton,this));
		}
		
		$('.product-actions').hide();
		Clariprint.log('** SETUP add-to-cart');
		this.addToCartButton = $('button.add-to-cart').clone();
		Clariprint.Widget = this;
		

		this.addToCartButton.removeClass('add-to-cart');
		this.addToCartButton.hide();
		this.addToCartButton.click(jQuery.proxy(this.showSave,this));
		this.addToCartButton.insertBefore($('.product-actions').last());
//		$('.product-actions').before(this.addToCartButton);
		this.addToCartButton.prop('disabled', false);
		this.addToCartButton.hide();
//		$('.product-actions').hide();
		
		this.start_marketplace = this.element.find('.start_marketplace');
		Clariprint.log('SETUP MARKET PLACE . ' + this.start_marketplace.length);
		if (this.start_marketplace.length > 0) {
			Clariprint.log('SETUP MARKET PLACE');
			this.start_marketplace.click(jQuery.proxy(this.startMarketplace,this));
		}
		this.interval  = setInterval(jQuery.proxy(this.checkForm,this),1500);
		
		if (this.options.button)
			$(this.options.button).on('click',jQuery.proxy(this.adminPrice,this));

		//this.progress = $('<progress class="progress progress-striped progress-animated" value="100" max="100">Calculation.....</progress>');
		//this.progress = $('<div><img src="/modules/clariprint/img/calculate_spin.svg" />\
		//					<p>Instant quote in process, please wait.</p>\
		//					<p>Calcul de votre prix, veuillez patienter quelques secondes.</p></div>');

		this.progress = $('#clariprint_wait_message');
		
		$('.product-information').before(this.progress);
		
		
//		$('#add-to-cart-or-refresh').hide();

//		$('button.add-to-cart').unbind('click').click(
//			jQuery.proxy(this.showSave,this));
			
		this.element.submit(function(evt){
			evt.preventDefault();
			return false;});

		$('clariprint_cart_add').click($.proxy(this.checkRef,this));
		$('.clariprint_save_project').button({
					text: true,
					icons: {
						primary: 'ui-icon-disk'
					}
				}).click(
				jQuery.proxy(this.saveProject,this));
		this.output = $('#short_description_content');

		$('button[role="clariprint-save-project"]').click($.proxy(this.saveConfiguration,this));
		$('button[role="clariprint-add-to-cart"]').click($.proxy(this.saveAddToCart,this));

		$('#clariprintsavecustomization').on('shown', function(){
			console.log('ici');
			$('button[role="clariprint-save-project"]').prop('disabled', false);
			$('button[role="clariprint-add-to-cart"]').prop('disabled', false);
		});

		$('#add_to_cart').hide();
//		prestashop.on('updateCart',$.proxy(this.prestashopUpdateCart,this));
		prestashop.on('updateProduct',$.proxy(this.prestashopUpdateCart,this));
		prestashop.on('updatedCart',$.proxy(this.prestashopUpdatedCart,this));
		
	},
	
	dialog:null,
	initAddToCart: true,
	
	checkRef: function(evt) {
		var ref = $('#clariprint_project_reference').val();
		if (ref == '') {
			$('#clariprint_project_reference').focus();
			evt.stopPropagation();
			evt.preventDefault();
			return false;
		}
		return true;
	},
	
	showSave: function(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		console.log('showSave');
		$('button[role="clariprint-save-project"]').prop('disabled', false);
		$('button[role="clariprint-add-to-cart"]').prop('disabled', false);
		
		$('#clariprintsavecustomization').modal('show');
		return false;
	},
	saveAddToCart:function(evt) {
		evt.preventDefault();
		var project_name = $('#clariprint_project_name').val().trim();
		if ($('#clariprint_project_name').val() == '') {
			
			$('#clariprint_project_name').closest('.input-group').addClass('has-warning')
			return false;
		}
		
		jQuery.ajax({
				url: '/index.php',
				type: 'POST',
				data: {
					'fc': 'module',
					'module' : 'clariprint',
					'controller' : 'cart',
					'ajax' : 1,
					'action' : 'addToCart2',
					'content' : 'urlencoded',
					'clariprint_project_name' : $('#clariprint_project_name').val(),
					'clariprint_product_id' :  this.element.attr('product-id'),
					'clariprint_solver_uid' :  $('#clariprint_solver_uid').val(),
					'clariprint_form' : this.element.serialize(),
					'clariprint_project_reference' : 'auto_save'
				},
				dataType: 'json',
				success: function(data){
					$('button[role="clariprint-save-project"]').prop('disabled', false);
					$('button[role="clariprint-add-to-cart"]').prop('disabled', false);
					
					if (data) {
						$('#clariprintsavecustomization').modal('hide');
						$('#product_customization_id').val(data);
						var $form = $('button.add-to-cart').closest('form');
						var query = $form.serialize() + '&add=1&action=update';
						var actionURL = $form.attr('action');
						$.post(actionURL, query, null, 'json').then(function (resp) {
							prestashop.emit('updateCart', {
								reason: {
									idProduct: resp.id_product,
									idProductAttribute: resp.id_product_attribute,
									linkAction: 'add-to-cart',
									cart: resp.cart
								},
								resp: resp
							});
							$('.ClariprintSolverWidget').solver('updatePrice');
						}).fail(function (resp) {
							prestashop.emit('handleError', { eventType: 'addProductToCart', resp: resp });
							$('.ClariprintSolverWidget').solver('updatePrice');
						});
					}
				},
				error: null
			});
		
		return false;
	},
	instance:function() {
		return this;
	},
	
	prestashopUpdateCart:function() {
		Clariprint.log('prestashopUpdateCart');
		$('.ClariprintSolverWidget').solver('updatePrice');
	},
	prestashopUpdatedCart:function() {
//		$('button[role="clariprint-add-to-cart"]').click($.proxy(this.saveAddToCart,this));
		Clariprint.log('prestashopUpdatedCart');
//		$('.ClariprintSolverWidget').solver('updatePrice');
	},
	
	setPrice:function(price) {
		
		this.$price.html(price);
		this.$price.show();
	},
	
	preventDblClick: function() {
//		$('button[role="clariprint-save-project"]').prop('disabled', true);
//		$('button[role="clariprint-add-to-cart"]').prop('disabled', true);
	},
	preventDblClick: function() {
//		$('button[role="clariprint-save-project"]').prop('disabled', true);
//		$('button[role="clariprint-add-to-cart"]').prop('disabled', true);
	},
	
	saveConfiguration: function(evt) {
		this.preventDblClick();
		evt.preventDefault();
		jQuery.ajax({
				url: '/index.php',
				type: 'POST',
				data: {
					'fc': 'module',
					'module' : 'clariprint',
					'controller' : 'cart',
					'ajax' : 1,
					'action' : 'SaveProject',
					'content' : 'urlencoded',
					'clariprint_project_name' : $('#clariprint_project_name').val(),
					'clariprint_product_id' :  this.element.attr('product-id'),
					'clariprint_solver_uid' :  $('#clariprint_solver_uid').val(),
					'clariprint_form' : this.element.serialize(),
					'clariprint_project_reference' : 'auto_save'
				},
				dataType: 'json',
				error: jQuery.proxy(this.saveConfigurationSuccess,this),
				success: jQuery.proxy(this.saveConfigurationSuccess,this)
			});
//			Clariprint.log('clear project name');
			$('#clariprint_project_name').val('');
		return false;
	},
	saveConfigurationSuccess:function(data)
	{
		console.log('saveConfigurationSuccess');
		$('button[role="clariprint-save-project"]').prop('disabled', false);
		$('button[role="clariprint-add-to-cart"]').prop('disabled', false);
		
		$('#clariprintsavecustomization').modal('hide');
		$('#product_customization_id').val(data);
		
	},
	saveConfigurationError:function(data)
	{
		
	},
	
	
	saveProject: function() {
		if (this.initAddToCart) {
			jQuery('#clariprint_cart_cancel').button();
			jQuery('#clariprint_cart_cancel').unbind('click').click($.proxy(this.cancelAddToCart,this));

			jQuery('#clariprint_cart_add').button();
			jQuery('#clariprint_cart_add').on('click',$.proxy(this.submitSaveProjectCart,this));
			jQuery('#clariprint_cart_add').attr('value',Clariprint_term_button_save_project);

			this.initAddToCart = false;
		}
		$('#clariprint_product_key').val(this.element.serialize());

		this.dialog = $('#clariprint_cart').dialog({modal: true,'width': 550 });
		return false;
	},
	
	submitSaveProjectCart: function(evt) {
		$("#clariprint_cart_form input[name=action]").attr('value','addProject');
		if (!this.checkRef(evt)) return false;

		var datas =  $("#clariprint_cart_form").serialize();
		datas['action'] = 'addProject';
		$.ajax({
			type: "POST",
			url: url,
			data: datas, // serializes the form's elements.
			success: function(data)
			{
				$("#clariprint_cart").dialog('close');
			}
		});

		evt.preventDefault();
	},

	addValidator:function(val) {
		if (this.validators == null) this.validators = new Array();
		this.validators.push(val);
	},
	addToCart: function() {

		if (!this.initAddToCart) {
			$("#clariprint_cart_form input[name=action]").attr('value','addToCart');
			jQuery('#clariprint_cart_cancel').button();
			jQuery('#clariprint_cart_cancel').on('click',$.proxy(this.cancelAddToCart,this));

			jQuery('#clariprint_cart_add').button();
			jQuery('#clariprint_cart_add').off()
			jQuery('#clariprint_cart_add').click($.proxy(this.checkRef,this));
/*			jQuery('#clariprint_cart_add').on('click',$.proxy(this.submitAddToCart,this)); */
			jQuery('#clariprint_cart_add').attr('value',Clariprint_term_button_add_cart);
			this.initAddToCart = false;
		}
		$('#clariprint_product_key').val(this.element.serialize());

		this.dialog = $('#clariprint_cart').dialog({modal: true,'width': 550 });
		return false;
	},

	cancelAddToCart: function() {
		$("#clariprint_cart").dialog('close');
		return false;
	},
	
	submitAddToCart: function(evt) {
//		jQuery('cl_reload').val(window.location.pathname + window.location.search);
	},
	
	submitAddToCartDone: function()
	{
		$("#clariprint_cart").dialog('close');
		alert("ok");
	},
	
	updateProductPrice: function(data, textStatus, jqXHR) {
	},
	
	onSolve:false,
	
	startButton:function(evt) {	
		evt.preventDefault();
		evt.stopPropagation();
		this.updatePrice();
	},
	updatePrice:function() {
		$('#product_customization_id').val('');
		this.getPrice(this.element.serialize());
	},
	
	startMarketplace:function(evt) {	
		evt.preventDefault();
		evt.stopPropagation();
		
		this.element.find('.progress').show();
		$('#add_to_cart').hide();
		$('#short_description_content').hide().html();
		if (this.validators != null) {
			var i = 0;
			var errors = false;
			for(var i = 0; i < this.validators.length; i++) {
				var v = this.validators[i](this);
				if (v != "OK") {
					$('#short_description_content').append('<div class="error">' + v + '</div>');
					errors = true;
				}
			}
			if (errors) {
				$('#our_price_display').html(Clariprint_lang_ConfigError);
				return false;
				}
		}
		this.onSolve = true;
		this.startSolve();
		return false;
	},
	
	checkForm: function() {
		Clariprint.log('checkForm onSolve ?'+ this.onSolve);
		if (this.onSolve) return;
		var ser = this.element.serialize();
		if (ser != this.hash)
		{
			
			Clariprint.log('need refresh');
			this.element.trigger('CL:change');
			if (this.options.live) {
				$(this.options.div_short_description).hide().html('');
				this.hash = ser;
				if (this.getPrice(this.element.serialize()))
					this.hash = ser;
			} else {
				this.displayNeedRefresh();
				this.hash = ser;
			}
		}
	},

	displayNeedRefresh: function() {
		this.element.find('.solve .alert').hide();
		this.$price.hide();
		this.addToCartButton.hide();
		Clariprint.log('displayNeedRefresh');
		
		$('#our_price_display').html(Clariprint_lang_needRefresh);
		this.addToCartButton.hide();
		if (!this.options.live)
		{
			this.element.find('.solve .alert-warning').show();
			this.element.find('.solve .result').hide();
		}
		
	},
	
	getPrice: function(product)
	{
		$('button.add-to-cart').unbind('click').click(jQuery.proxy(this.showSave,this));
		this.$price.hide();
		this.addToCartButton.hide();

		
		this.element.find('.solve .alert').hide();
		this.element.find('.progress').show();
		this.progress.show();
//		$('#add_to_cart').hide();
		$(this.options.div_short_description).html("");
		if (this.validators != null) {
			var i = 0;
			var errors = false;
			for(var i = 0; i < this.validators.length; i++) {
				var v = this.validators[i](this);
				if (v != "OK") {
					this.element.find('.solve .alert-danger').show();
					$(this.options.div_short_description).append('<div class="alert alert-warning" role="alert">' + v + '</div>');
					errors = true;
				}
			}
			if (errors) {
				$('#our_price_display').html(Clariprint_lang_ConfigError);
				this.element.find('.solve .alert-warning').show();
				return false;
			}
		}
		$('#our_price_display').html("Calcul du prix ..");

		this.onSolve = true;
		var sid = $('#clariprint_solver_id');
		jQuery.ajax({
				url: '/index.php',
				type: 'POST',
				data: {
					'fc': 'module',
					'module' : 'clariprint',
					'controller' : 'solver',
					'ajax' : 1,
					'action' : 'PriceRequest',
					'content' : 'urlencoded',
					'product' :  product,
					'id_server' : (sid.length ? sid.val() : null)
				},
				dataType: 'json',
				error: jQuery.proxy(this.getPriceError,this),
				success: jQuery.proxy(this.getPriceDone,this)
				//complete: jQuery.proxy(this.getPriceError,this)
			});
		return true;	
	},
	getPriceError: function( jqXHR,  textStatus,  errorThrown ) {
		this.element.find('.progress').hide();
		Clariprint.log(this);
		this.onSolve = false;
//		$('#add_to_cart').hide();
		this.element.find('.solve .alert-danger').show();
		this.addToCartButton.hide();
		$('#our_price_display').html(Clariprint_lang_ConfigError);
	},
	
	
	getPriceDone: function(data, textStatus, jqXHR) {
		this.element.find('.progress').hide();
		this.progress.hide();
		$('button.add-to-cart').unbind('click').click(jQuery.proxy(this.showSave,this));
		
		if (data && data.success && data.response > 0 ) {
			this.lastresponse  = data;
			$(this.options.div_short_description).html(data.html);
			$('#clarprint_html_description').val($(this.options.div_short_description).html());
			
			this.setPrice(data.responseTxt);
//			$('#our_price_display').html(data.responseTxt);
//			$('span[itemprop="price"]').html(data.responseTxt);
//			$('#add_to_cart input').unbind('click').click(jQuery.proxy(this.addToCart,this));
//			$('#add_to_cart button').unbind('click').click(jQuery.proxy(this.addToCart,this));
//			$('#add_to_cart').show();
			Clariprint.log('Show addToCartButton');
			this.addToCartButton.show();
			$('#clariprint_solver_uid').val(data.uid);
			
			if (!this.options.live) {
				this.element.find('.solve .result .price').html(data.responseTxt);
				this.element.find('.solve .result .description').html(data.html);
				this.element.find('.solve .result').show();
				this.element.find('.solve .error').hide();
			}
			$('#clariprint-save-customization').show();
//			$('.product-prices').show();
			$(this.options.div_short_description).show();
//			$('.product-add-to-cart').show();
			
		} else {
			$('#clariprint-save-customization').hide();
			this.element.find('.solve .alert').hide();
			this.element.find('.solve .error').show();

			this.element.find('.solve .alert-danger').show();
			$(this.options.div_short_description).html(Clariprint_lang_SolverError).show();
//			$('#add_to_cart').hide();
		}
		this.onSolve = false;
	},
	
	session_id:null,
	needRestartSolve:false,

	progressbar:null,
	progresstxt:null,

	startSolve:function() {
		this.addToCartButton.hide();
		if (this.progressbar == null) {
			if (this.element.find('progress')) {
				this.progressbar = this.element.find('progress');
				
			} else {
				this.progressbar = this.element.find('.mp_progressbar').progressbar({
					value: false
				});
				this.progresstxt = this.element.find('.mp_progress_txt');
			}
			
		}
		this.progressbar.show();
		
		this.hash = this.element.serialize();
		if (this.session_id) {
			this.callAjax({
						action:'FreeRFQSession', 
						session_id: this.session_id},
			{
				success:$.proxy(this.successFreeRFQSession,this)
			});
		}
		
		this.GetNewProjectId('test','mon groupe','mon code');
	},
	
	callAjax:function(datas,opts) {
		var mdatas = {
					fc: 'module',
					id_server : ($('#id_solving_marketplace').val() != "" ? $('#id_solving_marketplace').val() : 'default'),
					module: 'clariprint',
					controller: 'solver',
					ajax: 1,
					content: 'urlencoded',
				};
		$.extend(mdatas,datas);
		var mopts = {
				url: baseDir,
				type: 'POST',
				data: mdatas,
				dataType: 'json'};
		
		$.extend(mopts,opts);
		$.ajax(mopts);
	},
	
	message:function(txt) {
		if (this.output)
		{
			this.progresstxt.html( txt  );
		} else if (window.console) {
			Clariprint.log(txt);
		}
	},
	GetNewProjectId: function(reference,group,code) {
		this.progressbar.progressbar('option','value',1);
		this.message('Création du projet');
		this.callAjax({action:'GetNewProjectId',reference:reference,group:group,code:code},
					{
						success:$.proxy(this.successGetNewProjectId,this),
						error:$.proxy(this.errorRFQ,this)
					});
					this.onSolve = false;
	},
	project_id:null,
	successGetNewProjectId:function( datas,  state,  ajaxOptions) {
		this.onSolve = false;
		Clariprint.log('onGetNewProjectId');
		this.project_id = datas.project_id;
		this.message('Projet créé ' + this.project_id);
		this.onSolve = false;
		this.SolveRFQ();
	},

	errorRFQ:function( event,  jqXHR,  ajaxOptions,  data) {
		this.onSolve = false;
		Clariprint.log('onGetNewProjectId');
		this.project_id = data.project_id;
		this.onSolve = false;
		this.progressbar.hide();
	},

	SolveRFQ: function() {
		this.progressbar.progressbar('option','value',2);
		this.message('Envoie des données techniques ');
		this.callAjax({action:'SolveRFQ', project:this.element.serialize(), project_id:this.project_id},
			{
				success:$.proxy(this.successSolveRFQ,this)
			});
	},

	successSolveRFQ: function(datas,  state,  ajaxOptions) {
		if (datas.status == 1) {
			if (datas.info) this.message(datas.info);
			if (datas.summary) this.message(datas.summary);
			this.session_id = datas.session_id;
			setTimeout($.proxy(this.FetchRFQ, this), this.rfqDelay);
			this.countFetch = 0;
		} else {
			if (datas.info) this.message(datas.info);
			if (datas.summary) this.message(datas.summary);
			this.stopRFQ();
		}
	},

	countFetch:0,
	maxFetch:40, /* 40s */
	FetchRFQ: function() {
		this.progressbar.progressbar('option','value',this.progressbar.progressbar('option','value') + 1);
		this.message('Recherche des resultats');
		this.callAjax({action:'FetchRFQ', session_id: this.session_id},
			{
				success:$.proxy(this.successFetchRFQ,this)
			});
	},
	divResults:null,
	createResultsStore:function() {
		this.divResults = this.element.find('.ResultsStore');
		if (this.divResults.length = 0) this.divResults = this.element.append('<div class="ResultsStore"></div>');
	},
	addResults:function(results) {
		if (this.divResults == null)  this.createResultsStore();
		this.divResults.html();
		for(i = 0; i < results.length ; i++) {
			var r = results[i];
			var x = $('<div class="Result"/>');
			x.append('<div class="dealer"/>').append(r.lineName);
			x.append('<div class="cost"/>').append(r.total);
			x.append('<div class="thousandmore"/>').append(r.thousandMore);
			x.attr('ResultId',r.lineId);
			this.divResults.append(x);
		}
	},
	resultatsTable:null,
	setResultsTable:function(results) {
		if (this.resultatsTable == null) this.resultatsTable = this.element.find('table.results');
		results.sort(function(a, b){return a.total - b.total;});
		Clariprint.log('setResultsTable');
		Clariprint.log(this.resultatsTable);
		var tb = this.resultatsTable.find('tbody');
		tb.html('');
		for(i = 0; i < results.length ; i++) {
			var r = results[i];
			var tr = $('<TR class="result"/>');
			Clariprint.log(r);
			tr.attr('ResultId',r.lineId);
			tr.append('<td class="dealer">'+ r.lineName + '</td>');
			tr.append('<td class="cost">' + r.total.toFixed(2) + '</td>');
			tr.append('<td class="thousandmore">' +  r.thousandMore.toFixed(2) + '</td>');
			tr.append('<td><button class="create_offer btn btn-success">Créer une offre</button></td>');
			tr.append('<td><button class="add_to_cart btn btn-success">AJouter au panier</button></td>');
			tb.append(tr);
		}
	},
	successFetchRFQ: function(datas,  state,  ajaxOptions) {
		this.message('Analyse des resultats');
		Clariprint.log(datas);
		if (datas.status == 1) {
			this.countFetch++;
			this.setResultsTable(datas.resultats);
			if (this.countFetch < this.maxFetch)
				setTimeout($.proxy(this.FetchRFQ, this), this.rfqDelay);
			else this.stopRFQ();
		} else if (datas.status == 2) {
			Clariprint.log('Success !!!');
			this.setResultsTable(datas.resultats);
			this.stopRFQ();
		}
	},

	stopRFQ:function() {
		this.message('Stop');
		this.FreeRFQSession();
		this.onSolve = false;
		this.session_id = null;
		this.progressbar.hide();
	},

	FreeRFQSession: function() {
		this.callAjax({action:'FreeRFQSession', session_id: this.session_id},
			{
				success:$.proxy(this.successFreeRFQSession,this)
			});
	},
	successFreeRFQSession: function(datas,  state,  ajaxOptions) {
		Clariprint.log('successFreeRFQSession');
		if (this.needRestartSolve) {
			Clariprint.log('RestartSolve');
			this.session_id = null;
			this.startSolve();
			return;
		}
	},

	MoreDetailsForResultId: function(session_id,options) {
		this.callAjax({action:'MoreDetailsForResultId', session_id:this.session_id, options:options},
			{
				success:$.proxy(this.successMoreDetailsForResultId,this)
			});
	},
	successMoreDetailsForResultId: function(event, jqXHR, ajaxOptions, data) {
		Clariprint.log('successMoreDetailsForResultId');
	}
});

Clariprint.updateQuantity = function(v) {$('#clariprint_quantity').val(v); };


jQuery.widget("custom.clariprint_colors",{
	cyan:null,
	magenta:null,
	yellow:null,
	black:null,
	four:null,
	$without:null,
	_create: function() {
		this.$without = $(this.element).find('input[role=no-colors]');
		this.cyan = $(this.element).find('input[value=cyan]');
		this.magenta = $(this.element).find('input[value=magenta]');
		this.yellow = $(this.element).find('input[value=yellow]');
		this.black = $(this.element).find('input[value=black]');
		this.four = $(this.element).find('input[value=4-color]');
		if (this.cyan) 		$(this.cyan).on('click',$.proxy(this.checkVals,this));
		if (this.magenta) 	$(this.magenta).on('click',$.proxy(this.checkVals,this));
		if (this.yellow)		$(this.yellow).on('click',$.proxy(this.checkVals,this));
		if (this.black)		$(this.black).on('click',$.proxy(this.checkVals,this));
		if (this.four)		$(this.four).on('click',$.proxy(this.checkFour,this));
		if (this.$without)		this.$without.on('click',$.proxy(this.without,this));
		
	},
	$defaultselection:null,
	without:function(evt)
	{
		if (this.$without.is(':checked'))
		{
			this.$defaultselection = this.element.find('input.CLColor:checked');
			this.element.find('input.CLColor').prop('checked',false).prop('disabled',true);
			
		} else {
			this.element.find('input.CLColor').prop('disabled',false); 
			if (this.$defaultselection) this.$defaultselection.prop('checked',true);
		}
	},
	
	checkVals: function(evt) {
		Clariprint.log(this);
		Clariprint.log("checkVals");
		if (this.cyan && this.magenta &&  this.yellow && this.black) {
			this.four.prop('checked',(this.cyan.prop('checked') && 
				 this.magenta.prop('checked') && 
				 this.yellow.prop('checked') && 
				 this.black.prop('checked')));
			 this.four.change(); 
		 }
	},
	checkFour: function(evt) {
		Clariprint.log("checkFour");
		if (this.cyan && this.magenta &&  this.yellow && this.black) {
			var x = this.four.prop('checked');
			if (this.cyan) this.cyan.prop('checked',x).change();
			if (this.magenta) this.magenta.prop('checked',x).change();
			if (this.yellow) this.yellow.prop('checked',x).change();
			if (this.black) this.black.prop('checked',x).change();
		}
	}
});


Clariprint.msgPaperError = 'Paper error';
jQuery.widget( "custom.paper", {
	quality:null,
	brand:null,
	color:null,
	weight:null,


	process:null,
	label:null,
	onSearch:false,
	
	onInit:false,
	onpaperadmin:0,
	
	_create: function() {

		if (this.element.hasClass('PaperWidgetAdmin'))
		{
			this.onInit = true;
			this.onpaperadmin = 1;
		}
		Clariprint.log('create clariprint.paper');
		this.quality = $(this.element).find('.quality');
		this.brand = $(this.element).find('.brand');
		this.color = $(this.element).find('.paper-color');
		this.weight = $(this.element).find('.weight');
		
		Clariprint.log('create clariprint.paper 2');
		
		this.qualitiesFilter = $(this.element).find('.PaperQualitiesFilter');
		this.brandsFilter = $(this.element).find('.PaperBrandsFilter');
		this.colorsFilter = $(this.element).find('.PaperColorsFilter');
		this.weightsFilter = $(this.element).find('.PaperWeightsFilter');

		this.processes =  $(this.element).find('.DefaultsPaperProcess');
		this.processes.change(jQuery.proxy(this.onChangeProcess,this));
		
		this.iso = $(this.element).closest('div.ClariprintPapers').find('.PaperISO');
		this.label = $(this.element).closest('div.ClariprintPapers').find('.ClariprintLabel');

		this.groupQualitiesFilter = $(this.element).find('.GroupPaperQualitiesFilter');
		this.groupBrandsFilter = $(this.element).find('.GroupPaperBrandsFilter');
		this.groupColorsFilter = $(this.element).find('.GroupPaperColorsFilter');
		this.groupWeightsFilter = $(this.element).find('.GroupPaperWeightsFilter');

		Clariprint.log('create clariprint.paper 3');
		
		$(this.element).find('.clariprin_reset_paper').on('click',jQuery.proxy(this.reset,this));
		
		$(this.quality).on('change',jQuery.proxy(this.onChange,this));
		$(this.brand).on('change',jQuery.proxy(this.onChange,this));
		$(this.color).on('change',jQuery.proxy(this.onChange,this));
		$(this.weight).on('change',jQuery.proxy(this.onChange,this));
		$(this.iso).on('change',jQuery.proxy(this.onChange,this));
		$(this.label).on('change',jQuery.proxy(this.onChange,this));

		this.onChange(null);
		this.element.closest('form.ClariprintSolverWidget').solver('addValidator', $.proxy(this.validate,this));
		Clariprint.log('init clariprint.paper ok CLDBG');
	},

	validate: function() {
		if (this.onSearch) return Clariprint.msgWaiting;
		if (this.onError) return Clariprint.msgPaperError;
		return "OK";
	},
	
	
	getProcesses: function() {
		if ($('input.processes').length > 0)
			return $.makeArray($('input.processes').map(function(i,e){ if (e.checked) return e.value; else return null;}));
		return this.element.attr('clariprint_paper_process');
	},
	
	reset: function() {
		this.onSearch = true;
		this.onError = false;
		jQuery.ajax('/index.php',{
				'data' : {
					'fc': 'module',
					'module' : 'clariprint',
					'controller' : 'paper',
					'ajax' : 1,
					'action' : 'paperselector',
					'process' : this.element.attr('clariprint_paper_process'),
					'processes' : this.getProcesses(),
					'iso' : this.iso.val(),
					'label' : (this.label.is(':checked') ? this.label.val() : ''),
					'qualitiesFilter' : (this.qualitiesFilter ? this.qualitiesFilter.val() : null),
					'brandsFilter' : (this.brandsFilter ? this.brandsFilter.val() : null),
					'colorsFilter' : (this.colorsFilter ? this.colorsFilter.val() : null),
					'weightsFilter' : (this.weightsFilter ? this.weightsFilter.val() : null)
				},
				'dataType' : 'json'})
				 .done(jQuery.proxy(this.ajaxDone,this))
				 .fail(function() {
					alert( "error" );
				 });
		
		
	},
	errorHandler: function() {
		this.onError = true;
		this.onSearch = false;
	},

	populateSelect: function(select,options,empty,filter) {
		Clariprint.log('populateSelect');
		var current = select.val();
		var html = '';
		var selected = false;
		if (empty) html = '<option value="">-</option>';
		for(var i =0; i < options.length; i++) {
			var val = options[i];
			if (filter == null || filter.length == 0 || filter.indexOf(val.value) >= 0)
			{
				html += '<option value="' +val.value + '"';
				if (val.value == current) {selected = true; html += ' selected="true"';} 
				html += '>' + val.txt + '</option>';
			}
		}
//		if (select == false) html += '<option selected="true" value="'+ current + '">!! '+ current+ '</option>';
		select.html(html);
	},

	populateMultipleSelect: function(select,options) {
		Clariprint.log('populateMultipleSelect');
		var currents = new Array();
		select.find('option:selected').each(
				function(index,element) { 
					currents.push( $(element).attr('value') ); });
		
		Clariprint.log(currents);
		var html = '';
		var selected = false;
		for(var i =0; i < options.length; i++) {
			var val	 = options[i];
			html += '<option value="' +val.value + '"';
			if ($.inArray(val.value,currents) >= 0) {
					selected = true; html += ' selected="true"';
				} 
			html += '>' + val.txt + '</option>';
		}
		select.html(html);
	},


	ajaxDone: function(data, textStatus, jqXHR) {
		if (this.onInit)
		{
			this.populateMultipleSelect(this.qualitiesFilter,data.allqualities);
			this.populateMultipleSelect(this.brandsFilter,data.allbrands);
			this.populateMultipleSelect(this.colorsFilter,data.allcolors);
			this.populateMultipleSelect(this.weightsFilter,data.allweights);

			this.populateMultipleSelect(this.groupQualitiesFilter,data.allqualities);
			this.populateMultipleSelect(this.groupBrandsFilter,data.allbrands);
			this.populateMultipleSelect(this.groupColorsFilter,data.allcolors);
			this.populateMultipleSelect(this.groupWeightsFilter,data.allweights);
			
			this.onInit = false;
		}
		if (this.quality.is('SELECT'))
			this.populateSelect(this.quality,data.qualities,false, (this.qualitiesFilter ? this.qualitiesFilter.val() : null));
		if (this.brand.is('SELECT'))
			this.populateSelect(this.brand,data.brands,true,(this.brandsFilter ? this.brandsFilter.val() : null));
		if (this.color.is('SELECT'))
			this.populateSelect(this.color,data.colors,true,(this.colorsFilter ? this.colorsFilter.val() : null));
		if (this.weight.is('SELECT'))
			this.populateSelect(this.weight,data.weights,false,(this.weightsFilter ? this.weightsFilter.val() : null));

		this.onError = false;
		this.onSearch = false;
	},
	
	filters : function() {
	},
	
	onChangeProcess: function() {
		if (this.onpaperadmin) this.onInit = true;
		this.onChange(null);
	},
	
	onChange: function(evt) {
		
		Clariprint.log('clariprint.paper::onChange');
		jQuery.ajax({
				url:'/index.php',
				type: 'POST',
				data: {
					fc: 'module',
					module: 'clariprint',
					controller: 'paper',
					ajax: 1,
					sequential:1,
					'initpapers': (this.onInit ? '1'  : ''),
					'onpaperadmin': this.onpaperadmin,
					action : 'paperselector',
					'processes' : this.getProcesses(),
					'quality' : this.quality.val(),
					'brand' : this.brand.val(),
					'color' : this.color.val(),
					'weight' : this.weight.val(),
					'iso' : this.iso.val(),
					'label' : (this.label.is(':checked') ? this.label.val() : ''),
					
					'qualitiesFilter' : (this.qualitiesFilter ? this.qualitiesFilter.val() : null),
					'brandsFilter' : (this.brandsFilter ? this.brandsFilter.val() : null),
					'colorsFilter' : (this.colorsFilter ? this.colorsFilter.val() : null),
					'weightsFilter' : (this.weightsFilter ? this.weightsFilter.val() : null),
					kfgdspokgmds:false
					},
				dataType : 'json'})
				 .done(jQuery.proxy(this.ajaxDone,this))
				 .fail(function() {
					alert( "error" );
				 });
		
	},
	onSuccess: function(resp) {
	}
});




jQuery(function($){
	$("input.CLInt").clint();
	$("input.CLFloat").clfloat();

	$('.NoSubmit').keydown(Clariprint.noSubmit);
//	$('.clariprint_radio').buttonset();
	
	if (typeof(tinySetup) !== 'undefined')
		tinySetup({editor_selector :"clariprint_rte"});
/*	$('.clariprint_save_project').saveproject(); */
});


Clariprint.noSubmit = function(evt) {
	if (evt.which == 13) { evt.preventDefault(); $(evt.target).change(); }
}
/*
jQuery.widget('clariprint.saveproject',{
	_create: function() {
	
		jQuery(this.element).button({
			text: true,
			icons: {
				primary: 'ui-icon-disk'
			}
		}).click(jQuery.proxy(this.add,this));
	},
	
	add: function(evt) {
		evt.stopPropagation();
		evt.preventDefault();

		Clariprint.log('cici');
		return false;
	}
});
*/

jQuery.widget('clariprint.delivery',{
	productkey:null,
	_create: function() {
		this.element.find('a.delete').click($.proxy(this.del,this));
		if (Clariprint.ui == 'jqueryui') this.element.find('a.delete').button();
	},
	del: function(evt) {
		Clariprint.log("Clariprint del");
		$(evt.currentTarget).parents("tr.delivery").first().remove();
		evt.stopPropagation();
		return false;
		},
	check: function() {
			
		}
});
Clariprint.setupDeliveries = function() {
	var deli = $('div.deliveries .active:checked');
	
	if (deli.length == 0)
	{
		$('div.deliveries input.active').first().prop('checked', true);
	}
	
	
	$('table.deliveries').delivery();
	
	if (Clariprint.ui == 'jqueryui') $('a.add_delivery').button();

	$('a.add_delivery').click(function(evt){
		var btn = $(evt.target);
		
		$.ajax({
			url:'index.php',
			data:{
				fc: 'module',
				ajax: 1,
				delivery_mode: 'multiple',
				action: 'DeliveryItem',
				module:'clariprint',
				controller:'product',
				product_key: btn.closest("table.deliveries").attr("productkey")
			}
		}).done(function(data){
			var item = $(data);
			$('table.deliveries tbody').append(item);
			$('[role=delivery_selector]').deliveryselector();
			if (Clariprint.ui == 'jqueryui') item.find('a.delete').button();
			item.find('a.delete').on('click',function(evt){
				$(evt.target).closest('tr').remove();
			});
		});
	});
	if ($('table.deliveries input.checkbox').length == 0)
	{
	}
}

jQuery.widget("custom.models", { 
	options: {
		auto: false
	},
	productdiv:null,
	_create: function() {
		this.productdiv = this.element.parents('div.clariprint_product');
		if (this.options.auto == true) {
			Clariprint.log('models auto update');
			var prox = jQuery.proxy(this.update,this);
			if (this.options.auto == false)
			{
				this.element.parents('div.clariprint_product').find('#clariprint_quantity').change(prox);
				this.element.find('input.model_quantity').change(prox);
			}
			this.update();

		} else {
			Clariprint.log('models event');
			$(document).on('CL:change',jQuery.proxy(this.update,this));
			this.update();
		}
	},
	
	add: function(idx,item) {
		var val = jQuery(item).val();
		if (jQuery.isNumeric(val)) this.quantity += parseInt(val);
	},
	
	update: function() {
		Clariprint.log('models.update()');
		this.quantity = parseInt(this.element.parents('div.clariprint_product').find('#clariprint_quantity').val());
		this.element.find('input.model_quantity').each(jQuery.proxy(this.add,this));
		this.element.find('td.total_printed').html(this.quantity);
		if (this.options.auto == true) 
			setInterval(jQuery.proxy(this.update,this),2000);
	}
});


// jQuery(function() { jQuery('div.models').models({ auto: false }); });


Clariprint.pantones = null;
Clariprint.msgColorNeedCoated = 'The choosen color need a coated paper';
Clariprint.msgColorNeedUnCoated = 'The choosen color need an uncoated paper (offset)';
Clariprint.msgColorUnknown = 'Unknown PMS code';
Clariprint.msgWaiting = 'Waiting PMS validation';

jQuery.widget("custom.colorselector", {
	kind_selector:null,
	back_check:null,
	front_check:null,
	current:null,
	onSearch:false,
	paper_iso:null,
	paper_iso_default:null,
	
	updatePaperISO: function(val) {
		if (val == '') val = this.paper_iso_default;

		if (val != this.paper_iso.val()) {
			this.paper_iso.val(val);
			Clariprint.log("Update this.paper_iso : "+ val);
			this.paper_iso.change();
		}
	},
	
	
	_create: function(options) {
//		this.element.keyup(jQuery.proxy(this.check,this));
		var key = this.element.attr('color_item');
		this.kind_selector = jQuery(key + '_class');
		this.kind_selector.change(jQuery.proxy(this.checkKind,this));
		this.back_check = jQuery(key + '_front');
		this.front_check = jQuery(key + '_back');
		this.back_check.change(jQuery.proxy(this.onCheck,this));
		this.front_check.change(jQuery.proxy(this.onCheck,this));
		this.paper_iso = this.element.closest('form.ClariprintSolverWidget').find('.PaperISO');
		this.paper_iso_default = this.paper_iso.attr('default');
//		this.check();
		this.element.closest('form.ClariprintSolverWidget').solver('addValidator', $.proxy(this.validate,this));
		var _pwid = this;
		
		$(this.element).autocomplete(
				{
				source: function( request, response ) {
					var term = request.term;
					request.fc = 'module';
					request.q = term;
					request.module = 'clariprint';
					request.controller = 'colors';
					request.ajax = 1;
					request.action = 'search';
					request.colorkind =  $(this.element.attr('kind-selector')).val();
					$.ajax({
						type:'POST',
						dataType: "json",
						data:request,
						success: response
					});
					/*$.getJSON(
						'',
						request,
						function( data, status, xhr ) {
							response( data );
					});*/
				},
				
				
				minChars: 2,
				max: 50,
				width: 500,
				selectFirst: false,
				scroll: false,
				cacheLength: 0,
				dataType: 'json',
				
				select: function(event, ui) {
					ui.item.value = ui.item.name;
					$(this).css('background-color',ui.item.hex);
					return true;
				}
			});
			$(this.element).data('uiAutocomplete')._renderItem = function( ul, item ) {
					return $( "<li>" )
						.attr( "data-value", item.name )
						.append( $( "<a>" ).text( item.name ))
						.appendTo( ul );
				};
		
	},
	
	validate: function() {
		if (this.onSearch) return Clariprint.msgWaiting;
		if (this.back_check.prop('checked') || this.front_check.prop('checked'))
		{
			
			if (this.current == null) {
				
				return Clariprint.msgColorUnknown;
			}
		}
		return "OK";
	},
	
	onCheck: function() {
		if (this.back_check.prop('checked') || this.front_check.prop('checked'))
		{
			this.check();
		} else {
			this.updatePaperISO('');
		}
	},
	
	getKind: function() {
		
		return this.kind_selector.val();
	},
	
	checkKind: function() {
		var color = this.element.val();
		var kind = this.kind_selector.val();
		Clariprint.log('checkKind');
		if (color != '') {
			this.onSearch = true;
			jQuery.ajax('/index.php',{
					'data' : {
						'fc': 'module',
						'module' : 'clariprint',
						'controller' : 'colors',
						'ajax' : 1,
						'action' : 'colorCheck',
						'kind' : kind,
						'color' : color
					},
					'dataType' : 'json'})
					 .done(jQuery.proxy(this.ajaxDone,this))
					 .fail(function() {
						alert( "error" );
					 });
		} else {
			this.element.css('background-color','');
			this.element.attr('label','');
		}
	},
	check: function() {
		var color = this.element.val();
		
		if (color != '') {
			this.onSearch = true;
			jQuery.ajax('/index.php',{
					'data' : {
						'fc': 'module',
						'module' : 'clariprint',
						'controller' : 'colors',
						'ajax' : 1,
						'action' : 'colorCheck',
						'color' : color
					},
					'dataType' : 'json'})
					 .done(jQuery.proxy(this.ajaxDone,this))
					 .fail(function() {
						alert( "error" );
					 });
		} else {
			this.element.css('background-color','');
			this.element.attr('label','');
		}
		
	},
	ajaxDone: function(data, textStatus, jqXHR) {
		if (data.success) {
			
			this.element.css('background-color',data.color.hex);
			this.element.css('color','#'+data.color.contrast);
			this.element.attr('title',data.color.name);
			this.element.css('border-color','');
			this.kind_selector.val(data.color.class);
			this.current = data.color;
			this.updatePaperISO(this.current.coat);
		} else {
			this.current = null;
			this.element.css('color','');
			this.element.css('background-color','');
			this.element.css('border-color','red');
			this.element.attr('title','unknown');
			this.updatePaperISO('');
		}
		this.onSearch = false;
	}
});

$.widget('clariprint.paperlist', {
	select:null,
	_create:function(){
		this.select = this.element.find('select');
		this.select.change($.proxy(this.update,this));
		this.element.find('input[type=radio]').click($.proxy(this.upradio,this));
	},
	upradio:function(evt)
	{
		var v = this.element.find('input[type=radio]:checked').val();
		var vs = v.split(';');
		this.element.find('input.quality').val(vs[0]);
		this.element.find('input.brand').val(vs[1]);
		this.element.find('input.paper_color').val(vs[2]);
		this.element.find('input.weight').val(vs[3]);
	},
	update:function(evt) {
		var v = this.select.val();
		var vs = v.split(';');
		this.element.find('input.quality').val(vs[0]);
		this.element.find('input.brand').val(vs[1]);
		this.element.find('input.paper_color').val(vs[2]);
		this.element.find('input.weight').val(vs[3]);
	}
});

jQuery.widget( "custom.paper2", {
	quality:null,
	brand:null,
	color:null,
	weight:null,
	process:null,
	label:null,
	onSearch:false,
	init:true,
	searchbox:null,
	
	btn_selected: 'list-group-item-success',
	btn_allowed: '',
	btn_error: 'list-group-item-danger',
	btn_disabled: 'disabled',
	

	_create: function() {
		Clariprint.log('Setup Paper2');
		
		this.qualities = $(this.element).find('ul.qualities');
		this.brands = $(this.element).find('ul.brands');
		this.colors = $(this.element).find('ul.colors');
		this.weights = $(this.element).find('ul.weights');

		this.processes = $(this.element).find('.processes');

		this.quality = $(this.element).find('input.quality');
		this.brand = $(this.element).find('input.brand');
		this.color = $(this.element).find('input.paper-color');
		this.weight = $(this.element).find('input.weight');

		this.iso = $(this.element).closest('div.ClariprintPapers').find('.PaperISO');
		this.label = $(this.element).closest('div.ClariprintPapers').find('.ClariprintLabel');
		this.searchbox = $(this.element).find('input.searchbox');

		this.qualitiesFilter = $(this.element).find('.PaperQualitiesFilter');
		this.brandsFilter = $(this.element).find('.PaperBrandsFilter');
		this.colorsFilter = $(this.element).find('.PaperColorsFilter');
		this.weightsFilter = $(this.element).find('.PaperWeightsFilter');
		this.label = $(this.element).closest('div.ClariprintPapers').find('.ClariprintLabel');

		this.element.find('.processes').change($.proxy(this.onChange,this));
		
		this.element.find('.revert').click($.proxy(this.revert,this));
		
		Clariprint.log('Setup searchbox');
		
		this.searchbox.autocomplete(
				{
				url:'/index.php',
					
				source: $.proxy(this.autoCompleteSource,this),
					
				minChars: 2,
				max: 50,
				width: 500,
				selectFirst: false,
				resultsClass: 'clariprint_paper_search_results',
				scroll: false,
				cacheLength: 0,
				
				parse: function(data) {
					var mytab = new Array();
					for (var i = 0; i < data.length; i++)
						mytab[mytab.length] = { data: data[i], value: (data[i].quality + ' : ' + data[i].brand + ' : ' + data[i].color + ' : ' + data[i].weight).trim() };
					return mytab;
				},
				extraParams: {
					fc: 'module',
					module : 'clariprint',
					controller : 'paper',
					ajax : 1,
					action : 'search',
					process : this.element.attr('clariprint_paper_process'),
					processes : $.proxy(this.getProcesses,this),
					iso : this.iso.val(),
					label : $.proxy(this.getLabel,this), // (this.label.is(':checked') ? this.label.val() : ''),
					qualitiesFilter : (this.qualitiesFilter ? this.qualitiesFilter.val() : null),
					brandsFilter : (this.brandsFilter ? this.brandsFilter.val() : null),
					colorsFilter : (this.colorsFilter ? this.colorsFilter.val() : null),
					weightsFilter : (this.weightsFilter ? this.weightsFilter.val() : null)
				},
				select: $.proxy(this.setPaper,this)
				
				});
		this.searchbox.data('uiAutocomplete')._renderItem = function( ul, item ) {
					return $( "<li>" )
						.attr( "data-value", item.name )
						.append( $( "<a>" ).text( item.quality + ' : ' + item.brand + ' : ' + item.color + ' : ' + item.weight ))
						.appendTo( ul );
				}; 
		this.onChange(null);
	},
	
			
			autoCompleteSource:function(request, response)
			{
					request.fc = 'module';
					request.module = 'clariprint';
					request.controller = 'paper';
					request.q = request.term;
					request.ajax = 1;
					request.action = 'search';
					request.process = this.element.attr('clariprint_paper_process');
					request.processes = this.getProcesses();
					request.iso = this.iso.val();
					request.label = this.getLabel(); // (this.label.is(' = checked') ? this.label.val() = '');
					request.qualitiesFilter = (this.qualitiesFilter ? this.qualitiesFilter.val() : null);
					request.brandsFilter = (this.brandsFilter ? this.brandsFilter.val() : null);
					request.colorsFilter = (this.colorsFilter ? this.colorsFilter.val() : null);
					request.weightsFilter = (this.weightsFilter ? this.weightsFilter.val() : null);
					$.ajax({
						dataType: "json",
						type: 'POST',
						data: request,
						success:function(data){ 
							console.log(data);
							response(data); 
					}});
			},
			
			search: function(evt) {
				var txt = this.searchbox.val();
				var rx = new RegExp('/*'+txt+'/');
				this.qualities.find('li').each(function(idx,x){ 
					x = $(x);
					if (x.html().match('/*'+txt+'/'))
						x.show();
					else x.hide();
				 });
				
			},

			getProcesses:function() {
				return this.element.find('.processes:checked').map(function(x,o) { return $(o).val(); }).get();
			},
			getLabel:function() {
				return (this.label.is(':checked') ? this.label.val() : '');
			},
			setPaper: function(event, ui) {
				Clariprint.log('setPaper ...');
				this.quality.val(ui.item.quality);
				this.brand.val(ui.item.brand);
				this.color.val(ui.item.color);
				this.weight.val(ui.item.weight);

				//this.element.find('ul.paper_property li.' + this.btn_selected).removeClass(this.btn_selected);

				this.onChange(null);
			},
			
			
			setPaperold: function(event, data, formatted) {
				Clariprint.log('setPaper ...'. data);
				this.quality.val(data.quality);
				this.brand.val(data.brand);
				this.color.val(data.color);
				this.weight.val(data.weight);

				this.element.find('ul.paper_property li.' + this.btn_selected).removeClass(this.btn_selected);

				this.onChange(null);
			},
			
			validate: function() {
				if (this.onSearch) return Clariprint.msgWaiting;
				if (this.onError) return Clariprint.msgPaperError;
				return "OK";
			},
			reset: function() {
				this.onSearch = true;
				this.onError = false;
				jQuery.ajax('/index.php',{
						'data' : {
							'fc': 'module',
							'module' : 'clariprint',
							'controller' : 'paper',
							'ajax' : 1,
							'initpapers' : (this.init ? 1 : ''),
							'action' : 'paperselector',
							'process' : this.element.attr('clariprint_paper_process'),
							'processes' : this.getProcesses(),
							'iso' : this.iso.val(),
							'label' : (this.label.is(':checked') ? this.label.val() : ''),
							'qualitiesFilter' : (this.qualitiesFilter ? this.qualitiesFilter.val() : null),
							'brandsFilter' : (this.brandsFilter ? this.brandsFilter.val() : null),
							'colorsFilter' : (this.colorsFilter ? this.colorsFilter.val() : null),
							'weightsFilter' : (this.weightsFilter ? this.weightsFilter.val() : null)
						},
						'dataType' : 'json'})
						 .done(jQuery.proxy(this.ajaxDone,this))
						 .fail(function() {
							alert( "error" );
						 });
		
		
			},
			revert: function(evt) {
				Clariprint.log('revert ..');
				this.quality.val(this.quality.attr('default-value'));
				this.weight.val(this.weight.attr('default-value'));
				this.color.val(this.color.attr('default-value'));
				this.brand.val(this.brand.attr('default-value'));
				this.element.find('ul.paper_property .list-group-item-success').removeClass('list-group-item-success');
				evt.preventDefault();
				this.onChange(null);
				
			},
			errorHandler: function() {
				this.onError = true;
				this.onSearch = false;
			},
			status:'init',
			clickButton: function(event) {
				var elem = $(event.target);
				var val = elem.attr('val');
				var par = elem.parent();
				
				if (elem.hasClass('list-group-item-success'))
				{
					val = '';
					if (Clariprint.ui == 'bootstrap')
					{
							
					}
/*					m.removeClass('btn-primary');
					m.removeClass('btn-danger'); */
//					elem.removeClass('list-group-item-success');
//					elem.removeClass('list-group-item-warning');
					
				} else if (val != '') {
					
//					$(par).find('li.list-group-item-success').removeClass('list-group-item-success');
//					elem.addClass('list-group-item-success');
				} else {
//					$(par).find('li.list-group-item-success').removeClass('list-group-item-success');
				}
				if (par.hasClass('qualities'))
					this.quality.val(val);
				else if (par.hasClass('brands'))
					this.brand.val(val);
				else if (par.hasClass('colors'))
					this.color.val(val);
				else if (par.hasClass('weights'))
					this.weight.val(val);
				else {
				}	
				this.onChange();
			},


			populateSelect: function(select,options,empty,filter,def) {
				Clariprint.log('populateSelect');
				Clariprint.log(select);
				if (this.init)
				{
					var current = def;
					var html = '';
					if (empty) {
						
							var bh = '<a class="list-group-item list-group-item-action" id="" val="" >-</a>';
							var btn =jQuery(bh);
							btn.click($.proxy(this.clickButton,this));
							select.append(btn);
					}
					for(var i =0; i < options.length; i++) {
						var val = options[i];
						if (filter == null || filter.length == 0 || filter.indexOf(val.value) >= 0)
						{
							
							var bh = '<a class="list-group-item list-group-item-action" id='+ escape(val.value) + ' val="' +val.value+ '" ';
							bh += '>' + val.txt +'</a>';
							var btn =jQuery(bh);
//							if (val == current) btn.addClass("btn-primary"); 
							btn.click($.proxy(this.clickButton,this));
							select.append(btn);
						}
					}
					
				} else {
					var current = def;
					select.find('a').addClass('disabled');
					select.find('a').removeClass('list-group-item-danger list-group-item-warning list-group-item-success list-group-item-danger');
					for(var i =0; i < options.length; i++) {
						var val = options[i];
						select.find("a[val='"+ val.value.replace(/'/g, "\\'") +"']").removeClass('disabled');
					}
					/*
	btn_selected: 'list-group-item-success',
	btn_allowed: '',
	btn_error: 'list-group-item-danger',
	btn_disabled: 'disabled',
					*/
					if (current != '' & current !== undefined) {
						select.find("a[val='"+ current.replace(/'/g, "\\'") +"']").addClass("list-group-item-success");
					}
					var badselectclass ='.list-group-item-success.disabled';
					var pb = select.find(badselectclass);
					if (pb.length > 0) {
						pb.removeClass('list-group-item-success disabled');
						pb.addClass('list-group-item-danger');
						this.onError = true;
						pb.scrollTo(pb);
					} 
				}
				var selected = select.find('.list-group-item-success');
				if (selected.length > 0) select.scrollTo(selected);
			},
			ajaxDone: function(data, textStatus, jqXHR) {
				this.onError = false;
				if (this.init)
				{
					if (this.qualities.is('UL'))
						this.populateSelect(this.qualities,data.allqualities,false, (this.qualitiesFilter ? this.qualitiesFilter.val() : null),this.quality.val());
					if (this.brands.is('UL'))
						this.populateSelect(this.brands,data.allbrands,true,(this.brandsFilter ? this.brandsFilter.val() : null),this.brand.val());
					if (this.colors.is('UL'))
						this.populateSelect(this.colors,data.allcolors,true,(this.colorsFilter ? this.colorsFilter.val() : null),this.color.val());
					if (this.weights.is('UL'))
						this.populateSelect(this.weights,data.allweights,false,(this.weightsFilter ? this.weightsFilter.val() : null),this.weight.val());
					this.init = false;
				}

				if (this.qualities.is('UL'))
					this.populateSelect(this.qualities,data.qualities,false, (this.qualitiesFilter ? this.qualitiesFilter.val() : null),this.quality.val());
				if (this.brands.is('UL'))
					this.populateSelect(this.brands,data.brands,true,(this.brandsFilter ? this.brandsFilter.val() : null),this.brand.val());
				if (this.colors.is('UL'))
					this.populateSelect(this.colors,data.colors,true,(this.colorsFilter ? this.colorsFilter.val() : null),this.color.val());
				if (this.weights.is('UL'))
					this.populateSelect(this.weights,data.weights,false,(this.weightsFilter ? this.weightsFilter.val() : null),this.weight.val());
				this.init = false;
				// this.onError = false;
				this.onSearch = false;
				this.element.find('.defaults').addClass('control-group');
				if (data.status == 'ok' & this.onError == false) {
					this.element.find('.defaults').removeClass('error');
					this.element.find('.defaults').addClass('success');
					this.element.find('.alerts .alert').hide();
					this.element.find('.alerts .alert-success').show();
				} else {
					this.onError = true;
					this.element.find('.defaults').addClass('error');
					this.element.find('.defaults').removeClass('success');
					this.element.find('.alerts .alert').hide();
					if (data.errors)
					{
						this.element.find('.alerts .alert-warning').text(data.errors);
					}
					this.element.find('.alerts .alert-warning').show();
				}
			},
	
			filters : function() {
			},
	
			onChange: function(evt) {
				Clariprint.log('clariprint.paper::onChange');
				jQuery.ajax('/index.php',{
						'type' : 'POST',
						'data' : {
							'fc': 'module',
							'module' : 'clariprint',
							'controller' : 'paper',
							'ajax' : 1,
							'action' : 'paperselector',
							'process' : this.element.attr('clariprint_paper_process'),
							'processes' : this.getProcesses(),

							'quality' : this.quality.val(),
							'brand' : this.brand.val(),
							'color' : this.color.val(),
							'weight' : this.weight.val(),
							'iso' : this.iso.val(),
							'label' : (this.label.is(':checked') ? this.label.val() : ''),
							'initpapers' : (this.init ? 1 : ''),
					
							'qualitiesFilter' : (this.qualitiesFilter ? this.qualitiesFilter.val() : null),
							'brandsFilter' : (this.brandsFilter ? this.brandsFilter.val() : null),
							'colorsFilter' : (this.colorsFilter ? this.colorsFilter.val() : null),
							'weightsFilter' : (this.weightsFilter ? this.weightsFilter.val() : null)
					
							},
						'dataType' : 'json'})
						 .done(jQuery.proxy(this.ajaxDone,this))
						 .fail(function() {
							alert( "error" );
						 });
		
			}, 
			onSuccess: function(resp) {
			}
		});

Clariprint.startClient = function() {
	Clariprint.log('Clariprint.startClient');
	// hide quantity selector
	jQuery('#quantity_wanted').val('1');
	jQuery('div.qty').hide();
	$('.ClariprintSolverWidget').solver();
//	$('.clariprint_products').claccordion({ 'header':'> .accordion_header', collapsible: true , active: false, 'heightStyle': "content" });
	$('.ClariprintSolverWidget .clariprint_product').each(function(i,e){ Clariprint.setupProduct(e); });
	$('.ClariprintSolverWidget > .clariprint_products.expandable > .card').expandablecard();
//	$('.ClariprintSolverWidget.clariprint-accordion').;
	Clariprint.setupDeliveries();
	$('.ClariprintSolverWidget').show();
	Clariprint.log('Client OK');
}

Clariprint.setupProduct = function(elem) {
	Clariprint.log('clariprint setup product');
	Clariprint.log(elem);
	var el = $(elem); 
	Clariprint.log('colorselector');
	el.find('input.clariprint_pms').colorselector();
	Clariprint.log('cards');
	if (el.closest('.clariprint_products').is('.expandable'))
	{
		el.find('> .card').expandablecard();
	}
	el.find('.ClariprintColorWidget').clariprint_colors();
	el.find('.card.expandable').expandablecard();
	Clariprint.log('paper1');
	el.find('.PaperWidget').paper();
	Clariprint.log('paper2');
	Clariprint.log(el.find('.PaperWidget2'));
	el.find('.PaperWidget2').paper2();
	
	el.find('div[role=paper-list-selector]').paperlist();
	
	el.find('[role="fold-selector"]').folding();
	if (typeof(tinySetup) !== 'undefined') tinySetup({editor_selector :"clariprint_rte"});

	el.find('[controller="radio-buttons"] button').click(function(evt) {
		evt.preventDefault();
		var but = $(evt.target);
		but.siblings('button').removeClass('btn-success');
		but.siblings('input').val(but.attr('value'));
		but.addClass('btn-success');
	});

	el.find('.nav-tabs a').click(function (e) { 
		e.preventDefault();
		$(this).tab('show');
	 });
	
	if (el.hasClass('clariprint_book')) el.book();
	if (el.hasClass('ClariprintFrontBook')) el.book();
	if (el.hasClass('clariprint_formbook')) el.formbook();
	el.find('.clariprint_book').book();
}

jQuery.widget('clariprint.projectController',{
	_create: function() {
		$('.cl_controlgroup').buttonset();
		Clariprint.setupProduct($('.clariprint_products'));
		this.element.find('.add_product').button().click($.proxy(this.addproduct,this));
		this.element.find('.add_model').button().click($.proxy(this.addmodel,this));
		$('#ClariprintAdminDelete').button({ icon:'ui-icon-trash' }).click($.proxy(this.deleteAllProducts,this));

		$('#clariprint_insert_product_before_me').progressbar({value:false});

		if ($('#clariprint_update_config').val() != '1')
			$('.ClariprintProducts').hide();
	},
	deleteAllProducts:function(evt) {
		evt.stopImmediatePropagation();
		evt.stopPropagation();
		$('#clariprint_update_config').val(-1);
		$('.clariprint_products > div.clariprint_product').prev().remove();
		$('.clariprint_products > div.clariprint_product').remove();
		$('.ClariprintProducts').hide();
		return false;
	},
	addproduct:function(evt) {
		evt.stopImmediatePropagation();
		evt.stopPropagation();
		$('#clariprint_insert_product_before_me').show();
		$('.ClariprintProducts').show();
		$('#clariprint_update_config').val(1);
		$.ajax({
				url: Clariprint.productController,
				data: {
					ajax: 1,
					product_id:$(evt.target).attr('product_id'),
					product_kind:$(evt.target).val(),
					action: 'AddProduct'
				},
				success: function(data) {
					var html = $(data);
					console.log('Add product, make it expandabkle ');

					$('#clariprint_insert_product_before_me').before(html);
					html.each(function(i,e){Clariprint.setupProduct(e);});
					$('.ClariprintProducts div.card.expandable').expandablecard();
					$('#clariprint_insert_product_before_me').hide();
				}
		});
		return false;
	},
	addmodel:function(evt) {
		evt.stopImmediatePropagation();
		evt.stopPropagation();
		$('.ClariprintProducts').show();
		$('#clariprint_update_config').val(1);
		$('#clariprint_insert_product_before_me').show();
		var $btn = $(evt.target);
		if (!$btn.is('button')) $btn = $btn.closest('button');
	
		var count = $('div.product_card').length ;
		$.ajax({
				url: Clariprint.productController,
				data: {
					ajax: 1,
					product_id:$btn.attr('product_id'),
					model_key:$btn.val(),
					product_index: count,
					action: 'AddProduct'
				},
				success: function(data) {
					console.log('addmodel sucess');
					var html = $(data);
					$('#clariprint_insert_product_before_me').before(html);
					html.each(function(i,e){Clariprint.setupProduct(e);});
					$('.ClariprintProducts div.card.expandable').expandablecard();
					$('#clariprint_insert_product_before_me').hide();
					$('.product_card .card-header .remove_product').click(function(evt){console.log(evt);
						console.log(evt.target);
						$(evt.target).closest('.product_card').remove();
					});
				}
		});
		return false;
	}
});




Clariprint.startAdmin = function(token) {

	$('#adminClariprintProduct').adminsolver();
	$(".PaperWidget").paper();
	$(".PaperWidget2").paper2();
	$(".folding_tabs").tabs();
	$(".ClariprintColorWidget").clariprint_colors();
	$('.ClariprintProducts div.card.expandable').expandablecard();
	$('.clariprint_product').each(function(i,e){ Clariprint.setupProduct(e);});
	$('#ClariprintUpdateConfiguration').button().click(function(evt){
		evt.stopImmediatePropagation();
		evt.stopPropagation();
		if (typeof(tinyMCE) !== 'undefined')
			tinyMCE.triggerSave();
		var datas = $('#form').serializeArray();
		datas.push({ name : 'ajax', value : '1'});
		datas.push({ name : 'token', value : token });
		datas.push({ name : 'controller', value: 'AdminClariprintProducts' });
		datas.push({ name : 'action', value: 'productUpdate' });
		$('#clariprint_solver_message').html('<span class="ui-icon ui-icon-refresh"></span>');

		$.ajax('index.php',
			{
				type: 'POST',
				async: true,
				dataType: 'json',
				data : datas
			})
			.done(function(a){
				$('#clariprint_solver_message').html(a.info);
				return true;
			});
		return false;
	});
	$('#adminClariprintProduct').show();
};

jQuery.widget('clariprint.folding',{
	_create: function() {
		this.element.find('.card').click($.proxy(this.setFold,this));
		var panel = this.element.find('.card-outline-primary').closest('[role="tabpanel"]')
		
		if (panel.length > 0) {
			var id = panel.attr('id');
			this.element.find('[role="tab"][href="#'+ id +'"]').tab('show');
		}
		
	},
	setFold:function(evt) {
		var target= $(evt.target).closest('.card');
		var selectedclass = 'card-outline-primary'; // 'card-inverse'
		this.element.find('.card').removeClass(selectedclass);
		$(target).addClass(selectedclass);
		
		this.element.find('input').val(target.attr('fold-index'));
		
		var width = Clariprint.parseFloat($(target).closest('.clariprint_product').find('.ClWidth').val());
		var height = Clariprint.parseFloat($(target).closest('.clariprint_product').find('.ClHeight').val());
		var hf = Clariprint.parseFloat($(target).attr('foldheight'));
		var hw = Clariprint.parseFloat($(target).attr('foldwidth'));
		$(target).closest('.clariprint_product').find('.ClOpenHeight').val(hf*height);
		$(target).closest('.clariprint_product').find('.ClOpenWidth').val(hw*width);
	}
});


jQuery.widget("custom.xlradio", {
	target:null,
	_create: function() {
		this.target = $(this.element.attr('target'));
		this.element.find('button').click($.proxy(this.onclick,this));
		var val = this.target.val();
		this.element.find('button[value=' + val +']').addClass('btn-primary');
	},
	onclick: function(event) {
		event.stopPropagation();
		var elem = $(event.target);
		this.element.find('button').removeClass('btn-primary');
		elem.addClass('btn-primary');
		this.target.val(elem.attr('value'));
		return false;
	}
	
});

$.when( $.ready ).then(function() {
	//	$('div.xl-radio').xlradio();

});

jQuery.widget('clariprint.book',{
	div:null,
	cover:null,
	pkey:null,
	controller: 'book',
	admin:false,
	fc:null,
	_create: function() {
		Clariprint.log('setup book');
		this.element.find('input.CLBookAddCover').click(jQuery.proxy(this.addCover,this));
		this.element.find('input.CLBookRemoveCover').click(jQuery.proxy(this.removeCover,this));
		this.div = this.element.find('div.components').first();
		this.cover = this.element.find('div.bookcover').first();
		this.element.find('.CLBookAddComponent').button().click(jQuery.proxy(this.addComponent,this));
		this.element.find('.CLBookAddComponentModel').button().click(jQuery.proxy(this.addComponentWithModel,this));

		this.element.find('input.CLBookRemoveCover').button();
		this.element.find('input.CLBookAddCover').button();
		this.element.find('.component > .card').expandablecard();
//		this.element.find('.cover > .card').expandablecard();
//		$('.ClariprintSolverWidget > .clariprint_products.expandable > .card').expandablecard();
		

		this.element.find('[role="clariprint-cover-activation"]').click(jQuery.proxy(this.coverActivation,this));
		this.coverSetup();
//		this.element.find('div.component').claccordion({header:'> h3'});
		this.pkey = this.element.attr('productkey');
		this.element.find('.addcomponents_before').progressbar({value:false});
		if (Clariprint.admin)
		{
			this.controller = 'adminclariprintProducts';
		}
	},
	
	coverSetup: function(evt) {
		if (this.element.find('[role="clariprint-no-cover"]').is(':checked'))
		{
			this.element.find('.bookcover').hide();
		}  else {
			this.element.find('.bookcover').show();
			
		}
	},
	coverActivation: function(evt) {
		var mode = this.element.find('[role="clariprint-cover-activation"]').is(':checked');
		if (mode) {
			this.element.find('.bookcover').show();
			this.element.find('.bookcover').find('input[type="radio"][value!="none"]').first().prop('checked',true);
		} else {
			this.element.find('.bookcover').hide();
			this.element.find('.bookcover').find('input[role="clariprint-no-cover"]').prop('checked',true);
		}
	},
	addCover: function(evt) {
		var src = jQuery(evt.target).attr('name');
		var kind = 'section';
		if (src == ('add_leaflet')) kind = 'leaflet';
		jQuery.ajax({
			url: Clariprint.productController,
			data: {
				kind: kind,
				ajax: 1,
				action: 'BookCover',
				index: 0,
				productkey:this.pkey
			}
			})
			.done(jQuery.proxy(this.ajaxCoverSuccess,this));
	},
	addComponentWithModel: function(evt) {
		var btn = $(evt.target).closest('button');
		jQuery.ajax({
			method:'POST',
			url: Clariprint.productController,
			data: {
				id_product: btn.attr('product_id'),
				book: btn.attr('book'),
				model: btn.attr('model'),
				ajax: 1,
				action: 'BookComponentModel',
				index: 0
			}
			})
			.done(jQuery.proxy(this.ajaxComponentSuccess,this));
		return false;
	},
	addComponent: function(evt) {
		var kind = jQuery(evt.target).parents("div").first().attr('componentkind');
		this.element.find('.addcomponents_before').show();
		
		jQuery.ajax({
			method:'POST',
			url: Clariprint.productController,
			data: {
				kind: kind,
				ajax: 1,
				action: 'BookComponent',
				index: 0,
				productkey:this.pkey
			}
			})
			.done(jQuery.proxy(this.ajaxComponentSuccess,this));
		return false;
	},
	ajaxComponentSuccess : function(resp) {
		var x = $(resp)
		x.hide();
		this.div.append(x);
		this.element.find('.addcomponents_before').hide();
		this.element.find('div.addcomponents_before').before(x);
		
		this.element.find('div.card.expandable').expandablecard();
		x.each(function(i,e){Clariprint.setupProduct(e);});
		x.find('[role=remove-component]').click($.proxy(this.removeComponent,this));
		x.slideDown();
		
	},	
	removeComponent: function(evt) {
		evt.stopPropagation();
		$(evt.target).closest('div.card').slideUp(300, function(){ $(this).remove(); }); // .remove();
	},
	
	ajaxCoverSuccess: function(resp) {
		var x = $(resp)
		this.cover.append(x);
		x.each(function(i,e){Clariprint.setupProduct(e);});
		this.element.claccordion('refresh');
		this.element.find('div.card.expandable').expandablecard();
		this.element.find("input.CLBookAddCover").hide();
		this.element.find("input.CLBookRemoveCover").show();
		
	},
	removeCover: function(evt) {
		this.cover.find('div.cover').remove();
		this.element.find("input.CLBookAddCover").show();
		this.element.find("input.CLBookRemoveCover").hide();
	}
});


$.widget('clariprint.formbook',$.clariprint.book,{
	_create:function() {
		this._super();
	}
});



$.widget('clariprint.claccordion', {
	options: {
		header:'> h3'
	},
	_create:function() {
		this.element.addClass('ui-accordion ui-helper-reset ui-widget-content');
		this.refresh();
	},
	
	click:function(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		var x = $(evt.target).closest('.ui-accordion-header');
		
		if (x.next().is(':visible'))
		{
			x.addClass('ui-corner-all').removeClass('ui-corner-top');
			x.find('span.ui-accordion-header-icon').removeClass('ui-icon-triangle-1-s').addClass('ui-icon-triangle-1-e');
			x.next().hide();
		} else
		{
			x.addClass('ui-corner-top').removeClass('ui-corner-all');
			x.find('span.ui-accordion-header-icon').removeClass('ui-icon-triangle-1-e').addClass('ui-icon-triangle-1-s');
			x.next().show();
		}
	},
	refresh:function() {
		this.element.find(this.options.header).not('.ui-accordion-header').find('span.remove_product').click($.proxy(this.remove,this));
		
		this.element.find(this.options.header).not('.ui-accordion-header')
				.addClass('ui-accordion-header bg-dark')
				.prepend('<span class="ui-accordion-header-icon ui-icon ui-icon-triangle-1-e"></span>')
				.click($.proxy(this.click,this))
				.next().addClass('ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom').hide();
	},
	remove:function(evt) {
		Clariprint.log('remove');
		evt.stopPropagation();
		evt.preventDefault();
		if (confirm('Remove element ?'))
		{
			var x = $(evt.target).closest('.ui-accordion-header');
			x.next().remove();
			x.remove();
		}
	}
	
});

$.widget('clariprint.expandablecard', {
	options: {
		body : '> .card-block'
	},
	_create:function() {
//		this.element.find('> div.card-header .remove_product').click($.proxy(this.remove,this));
		this.signal = $('<i class="material-icons">keyboard_arrow_right</i>');
		this.element.find('> .card-header').prepend(this.signal);
		
		this.element.find(this.options.body).addClass('p-l-1');
		this.element.find('> .card-header').click($.proxy(this.click,this));
		this.element.find(this.options.body).hide();
		if (this.element.hasClass('removable'))
		{
			var ic = $('<i class="remove_product material-icons float-right" style="float: right">delete</i>');
			ic.click($.proxy(this.remove,this));
			this.element.find('> .card-header').append(ic);
		}
			
		
	},
	
	click:function(evt) {
		evt.stopPropagation();
		evt.preventDefault();
		if (this.element.find(this.options.body).is(':visible'))
		{
			this.signal.html('keyboard_arrow_right');
			this.element.find(this.options.body).hide();
		} else {
			this.signal.html('keyboard_arrow_down');
			this.element.find(this.options.body).show();
		}
	},
	refresh:function() {
	},
	remove:function(evt) {
		Clariprint.log('remove');
		evt.stopPropagation();
		evt.preventDefault();
		if (confirm('Remove element ?'))
		{
			this.element.remove();
		}
	}
});


	$('button[role=Show3D]').click(function(){
		console.log('Show3D');
		$('div[role=ParametricOutput]').html('loading ....');
		var product = $(this).closest('form').serialize();

		$.ajax({
			url: '/index.php',
			type: 'POST',
			'data' : {
				'fc': 'module',
				'module' : 'clariprint',
				'controller' : 'solver',
				'ajax' : 1,
				'action' : 'GetParametric3D',
				'content' : 'urlencoded',
				'product' :  product

			},
			success:function(content)
			{
				$('div[role=ParametricOutput]').html(content);
			},
			error:function()
			{
				$('div[role=ParametricOutput]').html('Error');
			}
		});


	});
	$('button[role=ShowModel]').click(function(){
		console.log('ShowModel');
		$('div[role=ParametricOutput]').html('loading ....');
		var product = $(this).closest('form').serialize();

		$.ajax({
			url: '/index.php',
			type: 'POST',
			dataType: "json",
			'data' : {
				'fc': 'module',
				'module' : 'clariprint',
				'controller' : 'solver',
				'ajax' : 1,
				'action' : 'GetParametricSVG',
				'content' : 'urlencoded',
				'product' :  product

			},
			success:function(content)
			{
				$('div[role=ParametricOutput]').html('');
				console.log('content',content);
				console.log(content.length);
				$(content).each(function(idx,e) { 
					console.log(idx,e);
					$('div[role=ParametricOutput]').append(e);
				}); 
			},
			error:function()
			{
				$('div[role=ParametricOutput]').html('Error');
			}
		});

	});
	jQuery.widget("clariprint.parametric",{
		_create:function()
		{
		}
	});

};




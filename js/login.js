$(document).ready(function(){
	//FORM
	$("#login-form")
        .on('init.field.fv', function(e, data) {
            // data.fv      --> The FormValidation instance
            // data.field   --> The field name
            // data.element --> The field element
            var $parent = data.element.parents('.form-group'),
                $icon   = $parent.find('.form-control-feedback[data-fv-icon-for="' + data.field + '"]');
            // You can retrieve the icon element by
            // $icon = data.element.data('fv.icon');
            $icon.on('click.clearing', function() {
                if ( $icon.hasClass('glyphicon-remove') ) {
                    data.fv.resetField(data.element);
                }
            });
        })
		
		.formValidation({
			framework: 'bootstrap',
			icon: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				usr: {
					validators: {
						notEmpty: {
							message: 'O c&oacute;digo do usu&aacute;rio &eacute; obrigat&oacute;rio'
						},
						stringLength: {
							min: 7,
							max: 30,
							message: 'O c&oacute;digo do usu&aacute;rio deve ter entre 7 e 30 caracteres'
						},
						regexp: {
							regexp: new RegExp("^[a-zA-Z0-9.]+$"),
							message: 'O c&oacute;digo do usu&aacute;rio s&oacute; pode conter letras, o ponto, e n&uacute;meros'
						}
					}
				},
				psw: {
					validators: {
						notEmpty: {
							message: 'A senha &eacute; obrigat&oacute;ria'
						},
						stringLength: {
							min: 7,
							max: 30,
							message: 'A senha deve conter 7 e 30 caracteres'
						}
					}
				}
			}
		})
		
		.on('success.form.fv', function(e) {
            // Prevent form submission
            e.preventDefault();
        })	
	
		.submit( function() {
			var parameter = {
				page: jQuery('#page').val(),
				username: jQuery('#usr').val(),
				password: $.sha1(jQuery('#psw').val().toLowerCase())
			};
			jsLIB.ajaxCall({
				waiting : true,
				url: jsLIB.rootDir+'rules/login.php',
				data: { MethodName : 'login', data : parameter },
				success: function(data){
					if ( data.login == true ) {
						window.location.replace(data.page);
					} else {
						loginError();
					}
				},
				error: loginError 
			});
		});
	
	$("#myBtnLogin").click(function(){
		$("#myLoginModal").modal();
	});
	
	$("#myBtnLogout").click(function(){
		jsLIB.ajaxCall({
			waiting : true,
			url: jsLIB.rootDir+'rules/login.php',
			data: { MethodName : 'logout' },
			success: function(dt){
				window.location.replace( jsLIB.rootDir+'index.php' );
			}
		});
	});
});

function loginError( jqxhr, errorMessage ){
	BootstrapDialog.show({
		title: 'Erro',
		message: 'Acesso negado!',
		type: BootstrapDialog.TYPE_DANGER,
		size: BootstrapDialog.SIZE_SMALL,
		draggable: true,
		closable: true,
		closeByBackdrop: false,
		closeByKeyboard: false,
		buttons: [{
			label: 'Fechar',
			cssClass: 'btn-danger',
			action: function(dialogRef){
				dialogRef.close();
			}
		}]	
	});	
}
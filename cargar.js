$(window).on('load', function() { // makes sure the whole site is loaded 
    $('#status').fadeOut(); // will first fade out the loading animation 
    $('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website. 
    $('body').delay(350).css({'overflow':'visible'});
});

$(document).ready(function(){

    // slider
    $("#slider-testimonios").owlCarousel({
        items:1, 
        margin: 20,
        nav: true,
        dots: false,
        autoplay:true, 
        loop:true, 
        animateOut: 'fadeOut'
    });

    // carousel elecciones
    $("#slider-elecciones").owlCarousel({
        items:3, 
        margin: 20,
        nav: true,
        dots: false,
        autoplay:true, 
        loop:true, 
        animateOut: 'fadeOut',
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:true
            },
            600:{
                items:3,
                nav:false
            },
            1000:{
                items:3,
                nav:true,
                loop:false
            }
        }

    });   

	$('.enviar').click(function(){

	    // Envio los datos de todos los campos del html
	    var name           = $('input[name=nombres]');
	    var lastname   	   = $('input[name=apellidos]');
	    var email          = $('input[name=email]'); 
	    var ast            = $('input[name=asunto]');
	    var comment        = $('textarea[name=mensaje]');       
	    var returnError    = false;

	    var formData	   = $('#fcontacto').serialize();
	    
	    // Simple validacion para saber si el usuario ingreso algun valor
	    // Agrego un control de errores con js, pero tambien procesando con un archivo PHP.
	    // Si encuentra el error, se agrega y resalta la clase .error a los campos de texto y al textarea.
	    if (name.val()=='') 
	    {
	        name.addClass('error');
	        returnError = true;
	    } else name.removeClass('error');

	    if (lastname.val()=='') 
	    {
	        lastname.addClass('error');
	        returnError = true;
	    } else lastname.removeClass('error');
	    
	    if (email.val()=='') 
	    {
	        email.addClass('error');
	        returnError = true;
	    } else email.removeClass('error');

	    if (ast.val()=='') 
	    {
	        ast.addClass('error');
	        returnError = true;
	    } else ast.removeClass('error');  	    
	    
	    if (comment.val()=='') 
	    {
	        comment.addClass('error');
	        returnError = true;
	    } else comment.removeClass('error');

	    
	    // A continuacion se resalta todos los campos que contengan errores.
	    if(returnError == true)
	    {
	        return false;   
	    }
	            
	    // Se inicia el ajax
	    $.ajax({

	        // Colocamos la url y el archivo enviar.php para que realize el proceso de envio.
	        url: ajaxurl,
	        
	        // el metodo que se usara es POST
	        type: "POST",

	        // colocamos la variable data para enviar los datos del formulario.       
            data: formData + '&action=enviarMail',
	        
	        // No almacenar los temporales en la pagina
	        cache: false,
	        
	        //success
	        success: function(data){            
	            
	            $('#estado').fadeOut("fast",function()
	            {
	                $('#estado').html(data);
	            });
	            
	            $('#estado').fadeIn("slow");
	            $("#fcontacto").find('input[type=text], textarea').val("");

	        }

	    });
	    
	    return false;

	});

    //////////////////////////////////////////////////////////////////////////////////////

    $('#ventana_suscripcion').on('show.bs.modal', function (e) {
        var email = $('#email').val();
        
        //Pass Values in modal
        var value = $('#Email').val(email);
        $('input[name=email_2]').attr('name');
        
    });

    // cuanndo le doy clic en cerrar la ventana modal, se limpia el valor del input text
    // que he ingresado
	$('#ventana_suscripcion').on('hidden.bs.modal', function () {
	    $('input[name=correo]').val("");
	})    

    // Si el boton es submit para enviar la suscripcion del usuario, le doy clic.
    $('.enviar_suscripcion').click(function() {
        
        // Envio los datos de todos los campos del html
        var name           = $('input[name=nombres]');
        var company        = $('input[name=empresa]');
        var country        = $('input[name=ciudad_pais]');
        var returnError    = false;

    	var formsend       = $('#fsolicitud').serialize();        
        
        if (name.val()=='') 
        {
            name.addClass('error');
            returnError = true;
        } else name.removeClass('error');

        if (company.val()=='') 
        {
            company.addClass('error');
            returnError = true;
        } else company.removeClass('error');

        if (country.val()=='') 
        {
            country.addClass('error');
            returnError = true;
        } else country.removeClass('error');

        // A continuacion se resalta todos los campos que contengan errores.
        if(returnError == true)
        {
            return false;   
        }        
                
        // Se inicia el ajax
        $.ajax({
            // Colocamos la base url y el nombre de la ruta para que realize el proceso de envio de suscripcion.
            url: ajaxurl,
            
            // el metodo que se usara es POST
            type: "POST",

            // colocamos la variable data para enviar los datos del formulario.
            data: formsend + '&action=inscripcionSuscritos',   
            
            // No almacenar los temporales en la pagina
            cache: false,
            
            //success
            success: function(data){            
                
                $('#estado').fadeOut("fast",function()
                {
                    $('#estado').html(data);
                });
                
                $('#estado').fadeIn("slow");
                $("#fsolicitud").find('input[type=text]').val("");
                $('input:checkbox').removeAttr('checked');

            }

        });
        
        return false;
    
    });	

    // add coments with ajax

    var commentform=$('#commentform'); // find the comment form
    commentform.prepend('<div id="comment-status" ></div>'); // add info panel before the form to provide feedback or errors
    var statusdiv=$('#comment-status'); // define the infopanel

    commentform.submit(function(){
        //serialize and store form data in a variable
        var formdata=commentform.serialize();
        //Add a status message
        statusdiv.html('<p>Processing...</p>');
        //Extract action URL from commentform
        var formurl=commentform.attr('action');
        //Post Form with data
        $.ajax({
            url: ajaxurl,        	
            type: 'POST',
            data: formdata + '&action=inscripcionSuscritos',
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                statusdiv.html('<p class="ajax-error" >You might have left one of the fields blank, or be posting too quickly</p>');
            },
            success: function(data, textStatus){
                if(data == "success" || textStatus == "success"){
                    statusdiv.html('<p class="ajax-success" >Thanks for your comment. We appreciate your response.</p>');
                }else{
                    statusdiv.html('<p class="ajax-error" >Please wait a while before posting your next comment</p>');
                    commentform.find('textarea[name=comment]').val('');
                }
            }

        });

        return false;

    });   

    $("#menu_5").addClass("active"); 

    $('ul.nav li.dropdown').hover(function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(500);
    }, function() {
        $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(500);
    });    

});

// Menu dropdown
$(function() {
	$('.navbar .dropdown.show').hover(function() {
	  $(this).find('.dropdown-menu').stop(true, true).fadeIn(0);
	}, function() {
	  $(this).find('.dropdown-menu').stop(true, true).fadeOut(0);
	});
});

// Back to Top Button
var btn = $('#button-top');

$(window).scroll(function() {
  if ($(window).scrollTop() > 300) {
    btn.addClass('show');
  } else {
    btn.removeClass('show');
  }
});

btn.on('click', function(e) {
  e.preventDefault();
  $('html, body').animate({scrollTop:0}, '300');
});

// ... !

$('form#promo-code').submit(function(e) {

    e.preventDefault();


    $(document).ajaxStart(function(){
        $("button#send-promo").text('Chargement ...').attr("disabled", true);
    });

    $(document).ajaxComplete(function(){
        $("button#send-promo").text('Utiliser un code promo').attr("disabled", false);
    });

    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        async: true,
        dataType:"json",
        data: $(this).serialize(),
        success: function(data){
            if(data.status === 'errors') {
                $(".flash-errors").addClass('bounceInDown').css('display','block').children('span.flash-message').text(data.request);
            }
            if(data.status === 'success') {
                $(".flash-errors").addClass('bounceInDown').css({"display": "block", "background": "#4CAF50"}).children('span.flash-message')
                    .text('Chargement en cours un instant ...');

                    setTimeout(function() {

                        window.location.replace(data.redirect);

                    }, 1000);

            }
        },
        error: function(xhr, textStatus, errorThrown) {
            $(".flash-errors").addClass('bounceInDown').css('display','block').children('span.flash-message')
                .text('Merci de renseigner un code promo valide');
            $('input').keyup(function() {
                const value = $(this).val();
                if(value.length > 1) {
                    $(".flash-errors").css('display','none').children('span.flash-message').text('');
                }
            });

        }
    });

});

// register
$('form#needs-validation').submit(function(e) {

     e.preventDefault();

    $(document).ajaxStart(function(){
        $("button.login").text('Chargement ...').attr("disabled", true);
    });

    $(document).ajaxComplete(function(){
        $("button.login").text('Finaliser mon inscription').attr("disabled", false);
    });

    $.ajax({
        type: "POST",
        url: path,
        async: true,
        dataType:"json",
        data: $(this).serialize(),
        success: function(data){
            if(data.status === 'errors') {
                $(".flash-errors").addClass('bounceInDown').css('display','block').children('span.flash-message').text(data.request);
                $("form").addClass('was-validated');
            }
            if(data.status === 'success') {
                $(".flash-errors").addClass('bounceInDown').css('display','none').children('span.flash-message').text('');
                $(".wait").css("display", "block").addClass('bounceInDown');
                setTimeout(function () {
                    $(".wait").css("background", "rgba(9, 63, 78, 0.96)");
                    $(".wait-load").css("display", "none");
                    $(".wait-pay").css("display", "block");

                    $("#close-pay").css("display", "block").click(function () {
                        $(".wait").css("display", "none");
                        $(".wait-load").css("display", "none");
                    })
                }, 4000)

            }
        },
        error: function(xhr, textStatus, errorThrown) {
            $(".flash-errors").addClass('bounceInDown').css('display','block').children('span.flash-message')
                .text('Merci de compléter tous les champs.');
            $("form").addClass('was-validated');

            $('input').keyup(function() {
                const value = $(this).val();
                if(value.length > 1) {
                    $(".flash-errors").css('display','none').children('span.flash-message').text('');
                }
            });

        }
    });

});


// ---

$('#select_avatar').submit(function(e) {

    e.preventDefault();

    $(document).ajaxStart(function(){
        $("button.avatar-select").text('Chargement ...').attr("disabled", true);
    });

    $(document).ajaxComplete(function(){
        $("button.avatar-select").text('Sauvegarder l\'avatar').attr("disabled", false);
    });

    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        async: true,
        dataType:"json",
        data: $(this).serialize(),
        success: function(data){
            if(data.status === 'errors') {
                $(".flash-errors").addClass('bounceInDown').css('display','block').children('span.flash-message').text(data.request);
                $("form").addClass('was-validated');
            }
            if(data.status === 'success') {
                $(".flash-errors").addClass('bounceInDown').css({"display": "block", "background": "#4CAF50"}).children('span.flash-message')
                    .text('Votre avatar a bien été mis à jour');
                setTimeout(function () {

                    window.location.replace(data.redirect);

                }, 1000)

            }
        },
        error: function() {
            $(".flash-errors").addClass('bounceInDown').css('display','block').children('span.flash-message')
                .text('Vous devez sélectionner un avatar correctement');
            $("form").addClass('was-validated');
        }
    });

});
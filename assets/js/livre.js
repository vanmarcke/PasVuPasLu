/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/livre.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

$('form#editComment').click(function(e) {

    e.preventDefault();

    const dataId = $(this).data('id');

    $("#hidden-edit-"+dataId).addClass('d-none');
    $("#show-edit-"+dataId).addClass('d-block bounceInDown');

    $("#modal-wrapper-"+dataId).load($(this).attr('action'), {}, function () {
        $('.modal-backdrop').remove();
        $("#show-edit-"+dataId).model("show");
    });

});
$(document).ready(function() {
    const url = "{{ livre.video }}";
    const id = url.split("?v=")[1].split("&")[0];
    const link = "https://www.youtube.com/embed/" + id;
    $('#myIframe').attr('src', link);

    const trigger = $('.trigger');
    trigger.click(function(e) {
        e.preventDefault();
        var theModal = $(this).data("target");
        $(theModal + ' iframe').attr('src', link);
        $(theModal).on('hidden.bs.modal', function(e) {
            $(theModal + ' iframe').attr('src', '');
        });
    });
});

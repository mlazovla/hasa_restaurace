/* Layout scripts */

function restartBackgroundAnimation() {
    var ids = [];
    $(".background-slide").each( function (index) {
        ids[index] = $(this).attr('id');
    });

    var el;
    for (var i=0; i < ids.length; i++) {
        el = $('#'+ids[i]).detach();
        el.appendTo('body');
    }
}

function switchMainContent(id) {
    // changing content
    $( ".page" ).slideUp();
    $( "#page" + id ).slideDown();

    // toggle navbar
    if ($("#navbar").attr("class") == "navbar-collapse collapse in") {
        $('.navbar-toggle').click();
    }

    if ($("#main-content").css('left') != "0px") { // animate first page
        $( "#main-content" ).animate({
            left: "0%",
        }, 450, function() {
            // Animation complete.
        });
    } else { // animate content changing
        $( "#main-content" ).animate({
            left: "-95%",
        }, 250, function() {

            $( "#main-content" ).animate({
                left: "0%",
            }, 250, function() {
                // Animation complete.
            });
        });
    }
}

$(window).load( function() {


    $( "#loading" ).animate({
        top: "-100%",
    }, 600, function() {
        // Animation complete.
    });
    restartBackgroundAnimation();
});

$(document).ready( function() {
    $("#skipLoading").show();
    $( ".page" ).slideUp();

    $("#skipLoading").on('click', function () {
        $( "#loading" ).animate({
            top: "-100%",
        }, 600, function() {
            // Animation complete.
        });
        restartBackgroundAnimation();
    });

    /**
     * Animate the main content drifting from left side and change them
     */
    $( ".menu-link" ).click(function() {
        switchMainContent( $(this).data("pageid") );
    });

    $("a.navbar-brand").click(function() {
        $( ".page" ).slideUp();
        if ($("#navbar").attr("class") == "navbar-collapse collapse in") {
            $('.navbar-toggle').click();
        }
        $( "#main-content" ).animate({
            left: "-100%",
        }, 450, function() {
            // Animation complete.
        });
        restartBackgroundAnimation();
    });
});
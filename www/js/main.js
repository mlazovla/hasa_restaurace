/* Layout scripts */

$(window).load( function() {
    $( "#loading" ).animate({
        top: "-100%",
    }, 600, function() {
        // Animation complete.
    });
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
    });

    /**
     * Animate the main content drifting from left side and change them
     */
    $( ".menu-link" ).click(function() {

        // changing content
        $( ".page" ).slideUp();
        $( "#page" + $(this).data("pageid") ).slideDown();

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
    });
});
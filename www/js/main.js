/* Layout scripts */

/**
 * Animate the main content drifting from left side
 */
$( ".menu-link" ).click(function() {
    if ($("#main-content").css('left') != "0px") {
        $( "#main-content" ).animate({
            left: "0%",
        }, 450, function() {
            // Animation complete.
        });
    }
});
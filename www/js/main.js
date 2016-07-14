/* Layout scripts */

/**
 * Animate the main content drifting from left side and change them
 */
$( ".menu-link" ).click(function() {

    // changing content
    $( ".page" ).slideUp();
    $( "#page" + $(this).data("pageid") ).slideDown();

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
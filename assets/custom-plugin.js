jQuery(document).ready(function ($) {
    // Function to display the pop-up
    function displayPopup() {
        // Create pop-up HTML
        var popupHtml = '<div class="custom-plugin-popup">' +
            '<div class="custom-plugin-popup-content">' +
            '<h2>Welcome to Custom New Lead</h2>' +
            '<p>This is the netninja-style pop-up.</p>' +
            '<button id="close-popup">Close</button>' +
            '</div>' +
            '</div>';

        // Append pop-up HTML to body
        $('body').append(popupHtml);

        // Close pop-up when close button is clicked
        $('#close-popup').click(function () {
            $('.custom-plugin-popup').remove();
        });
    }

    // Event listener for Custom New Lead menu click
    $(document).on('click', '#toplevel_page_custom-new-lead', function () {
        // Display the pop-up
        displayPopup();
    });
});

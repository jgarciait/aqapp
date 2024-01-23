// Function to trigger translation to English
function translateToEnglish() {
    // Check if the Google Translate widget is available
    if (typeof google !== 'undefined' && google.translate) {
        // Translate the page to English
        google.translate.translate({ source: 'es', target: 'en' }, function(result) {
            if (result.translation) {
                // Replace the content of the page with the translated text
                document.body.innerHTML = result.translation;
            }
        });
    }
}

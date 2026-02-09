jQuery(function ($) {
    const elements = {
        postStatus: $('#post-body .misc-pub-post-status'),
        authorStatus: $('#post-body .misc-pub-post-author'),
        visibilityStatus: $('#post-body .misc-pub-visibility#visibility'),
        publishStatus: $('#post-body .misc-pub-curtime'),
        saveAction: $('#save-action')
    };

    function removeUnnecessaryElements() {
        elements.publishStatus.find('a').remove();
        elements.postStatus.find('a.edit-post-status').remove();
        elements.visibilityStatus.remove();
        elements.saveAction.remove();
    }

    function repositionAuthor() {
        elements.authorStatus.insertAfter(elements.postStatus);
    }

    function updatePublishedDate() {
        if (!isValidCreatedInLabel()) {
            return;
        }
        const publishedDate = elements.publishStatus.find('#timestamp b').text();
        elements.publishStatus.find('#timestamp').html(` ${createdInLabel}: <b>${publishedDate}</b>`);
    }

    function isValidCreatedInLabel() {
        return typeof createdInLabel === 'string' && createdInLabel.trim().length > 0;
    }
    removeUnnecessaryElements();
    repositionAuthor();
    updatePublishedDate();
});
$(function() {
 
    // Enable for all forms. 
    $('form').dirtyForms();
 
    // Enable for just forms of class 'sodirty'. 
    $('form.sodirty').dirtyForms();
 
    // Customize the title and message. 
    // Note that title is not supported by browser dialogs, so you should  
    // only set it if you are using a custom dialog or dialog module. 
    $('form').dirtyForms({ 
        dialog: { title: 'Wait!' }, 
        message: 'You forgot to save your details. If you leave now, they will be lost forever.' 
    });
 
    // Enable Debugging (non-minified file only). 
    $('form').dirtyForms({ debug: true });
 
    // Check if anything inside a div with CSS class watch is dirty. 
    if ($('div.watch').dirtyForms('isDirty')) {
        // There was something dirty inside of the div 
    }
 
    // Select all forms that are dirty, and set them clean. 
    // This will make them forget the current dirty state and any changes 
    // after this call will make the form dirty again. 
    $('form:dirty').dirtyForms('setClean');
 
    // Rescan to sync the dirty state with any dynamically added forms/fields 
    // or changes to the ignore state. This comes in handy when styling fields 
    // with CSS that are dirty. 
    $('form').dirtyForms('rescan');
 
    // Select all forms that are listening for changes. 
    $('form:dirtylistening');
 
    // Enable/disable the reset and submit buttons when the state transitions 
    // between dirty and clean. You will need to first set the initial button 
    // state to disabled (either in JavaScript or by setting the attributes in HTML). 
    $('form').find('[type="reset"],[type="submit"]').attr('disabled', 'disabled');
    $('form').on('dirty.dirtyforms clean.dirtyforms', function (ev) {
        var $form = $(ev.target);
        var $submitResetButtons = $form.find('[type="reset"],[type="submit"]');
        if (ev.type === 'dirty') {
            $submitResetButtons.removeAttr('disabled');
        } else {
            $submitResetButtons.attr('disabled', 'disabled');
        }
    });
 
    // Add a form dynamically and begin tracking it. 
    var $form = $('<form action="/" id="watched-form" method="post">' +
        '<input id="inputa" type="text" />' +
        '<button id="submita" type="submit" value="Submit">Submit</button>' +
        '</form>');
    $('body').append($form);
    $form.dirtyForms();
 
});
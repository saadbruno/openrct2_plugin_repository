// bootstrap tooltips
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})

// validation for submit button

$('#submit-form').on('submit', function(e) {
    e.preventDefault();

    // clears CSS class to perform validation
    $('#githubUrl').removeClass('is-invalid');

    var formData = new FormData(this);
    console.log(formData);


    $.ajax({
        url: "/",
        data: formData,
        processData: false,
        contentType: false,
        type: "POST",

        // this success doesn't mean it sucessfully added a new entry to th database. It just means the server got the POST succesfully, and will do validation
        success: function(data, textStatus, jqXHR) {
            console.log(data);
            // decides what to do with the reply

            // if the message was 'success, redirect to the new URL'
            if (data.status == 'success') {
                $('#githubUrl').addClass('is-valid');
                window.location = data.redirect; 
            } else if (data.status == 'error') {
                // if there was an error, adds the "is-invalid" class to the form, and edits the feedback message
                $('#githubUrl').addClass('is-invalid');
                $('#githubUrl').siblings('.invalid-feedback').html(data.message);
            }

        },
        // the server never got the data
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("failed");
            // if there was an error, adds the "is-invalid" class to the form, and edits the feedback message
            $('#githubUrl').addClass('is-invalid');
            $('#githubUrl').siblings('.invalid-feedback').html('Server error');
        }
    });

});
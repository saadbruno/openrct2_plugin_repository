// bootstrap tooltips
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

// validation for submit button

document.querySelector('#submit-form').addEventListener('submit', function (e) {
    e.preventDefault();

    // clears CSS class to perform validation
    document.querySelector('#githubUrl').classList.remove('is-invalid');
    document.querySelector('#submit-feedback').classList.remove('d-block');

    var formData = new FormData(this);
    console.log(formData);

    fetch("/",
        {
            body: formData,
            method: "post"
        })
        .then((response) => response.json())
        .then((data) => {
            console.log('Success:', data);
            // if the message was 'success, redirect to the new URL'
            if (data.status == 'success') {
                document.querySelector('#githubUrl').classList.add('is-valid');
                window.location = data.redirect;
            } else if (data.status == 'error') {
                console.log(data.message);
                // if there was an error, adds the "is-invalid" class to the form, and edits the feedback message
                document.querySelector('#githubUrl').classList.add('is-invalid');
                document.querySelector('#submit-feedback').innerHTML = data.message;
                document.querySelector('#submit-feedback').classList.add('d-block');
            }
        })
        .catch((error) => {
            console.error('Error:', error);
            // if there was an error, adds the "is-invalid" class to the form, and edits the feedback message
            document.querySelector('#githubUrl').classList.add('is-invalid');
            document.querySelector('#submit-feedback').innerHTML = 'Server error';
            document.querySelector('#submit-feedback').classList.add('d-block');
        });

});

// whenever the user changes the "results per page dropdown"
document.querySelector(`[name="results"]`).onchange = function(){
    let url = new URL(window.location.href);
    url.searchParams.set('results', this.value);
    window.location.href = url.toString();
};
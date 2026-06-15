document.addEventListener('DOMContentLoaded', function () {

    if (successMessage) {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: successMessage,
            confirmButtonText: "OK"
        });
    }

    if (errorMessage || hasErrors) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: errorMessage || 'Validation Failed. Please check the form.',
            confirmButtonText: 'OK'
        });
    }

});
document.addEventListener('DOMContentLoaded', function() {
    
    const togglePassword = document.querySelector('#togglePassword');
    const passwordField = document.querySelector('#password');

    if (togglePassword && passwordField) {
        togglePassword.addEventListener('click', function () {
            // Toggle the type attribute
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            
            // Toggle the eye icon
            // 'fa-eye-slash' is the closed eye, 'fa-eye' is the open eye
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }

});
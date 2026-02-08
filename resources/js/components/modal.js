// This file contains JavaScript related to modal functionality for the solar energy theme.

document.addEventListener('DOMContentLoaded', function() {
    // Function to open the authentication modal
    function openAuthModal(mode) {
        const modal = document.getElementById('auth-modal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent body scroll
        if (mode === 'register') {
            switchAuthMode(null);
        }
    }

    // Function to close the authentication modal
    function closeAuthModal() {
        const modal = document.getElementById('auth-modal');
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore body scroll
    }

    // Function to close modal on outside click
    function closeModalOnOutsideClick(event) {
        if (event.target.classList.contains('modal-overlay')) {
            closeAuthModal();
        }
    }

    // Function to switch between login and registration forms
    function switchAuthMode(event) {
        if (event) event.preventDefault();

        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const modalTitle = document.getElementById('modal-title');
        const switchText = document.getElementById('switch-text');

        if (loginForm.classList.contains('hidden')) {
            // Switch to login
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            modalTitle.textContent = 'Welcome Back';
            switchText.textContent = "Don't have an account?";
            event.target.textContent = 'Register here';
        } else {
            // Switch to register
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            modalTitle.textContent = 'Create Account';
            switchText.textContent = 'Already have an account?';
            event.target.textContent = 'Login here';
        }
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeAuthModal();
        }
    });

    // Event listeners for modal open and close
    document.querySelectorAll('[data-modal-open]').forEach(button => {
        button.addEventListener('click', function() {
            openAuthModal(this.dataset.modalOpen);
        });
    });

    document.querySelector('.modal-overlay').addEventListener('click', closeModalOnOutsideClick);
});
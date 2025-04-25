document.addEventListener('DOMContentLoaded', function() {
    let nameInput = document.getElementById('name');
    let emailInput = document.getElementById('email');

    [nameInput, emailInput].forEach(input => {
        input.addEventListener('input', function() {
            const errorDiv = document.getElementById(`${input.id}-error`);
            errorDiv.classList.add('d-none');
        });
    });

});

const elements = stripe.elements();

//  style for the card element
const style = {
    base: {
        color: '#495057',
        fontFamily: 'system-ui, -apple-system, "Segoe UI", Roboto, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
            color: '#6c757d'
        },
        ':-webkit-autofill': {
            color: '#495057'
        }
    },
    invalid: {
        color: '#dc3545',
        iconColor: '#dc3545'
    }
};

const card = elements.create('card', {
    style: style
});
card.mount('#card-element');

// showing real-time validation errors from the card element
card.addEventListener('change', function(event) {
    const displayError = document.getElementById('card-errors');
    if (event.error) {
        displayError.textContent = event.error.message;
    } else {
        displayError.textContent = '';
    }
});

const form = document.getElementById('payment-form');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    let nameInput = document.getElementById('name');
    let emailInput = document.getElementById('email');
    let isValid = true;

    if (nameInput.value.trim() === '') {
        document.getElementById('name-error').classList.remove('d-none');
        isValid = false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(emailInput.value.trim())) {
        document.getElementById('email-error').classList.remove('d-none');
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
        return;
    }

    // Show loading state
    const button = form.querySelector('button[type="submit"]');
    const buttonText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';

    const {
        token,
        error
    } = await stripe.createToken(card);

    if (error) {
        // Reset button
        button.disabled = false;
        button.innerHTML = buttonText;

        // Display error
        const errorElement = document.getElementById('card-errors');
        errorElement.textContent = error.message;
    } else {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'stripeToken';
        input.value = token.id;
        form.appendChild(input);
        form.submit();
    }
});





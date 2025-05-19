document.addEventListener('DOMContentLoaded', function () {
    let nameInput = document.getElementById('name');
    let emailInput = document.getElementById('email');

    [nameInput, emailInput].forEach(input => {
        input.addEventListener('input', function () {
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


let cardNumber = elements.create('cardNumber', {
    style: style
});
let cardExpiry = elements.create('cardExpiry', {
    style: style
});
let cardCvc = elements.create('cardCvc', {
    style: style
});

cardNumber.mount('#card-number');
cardExpiry.mount('#card-expiry');
cardCvc.mount('#card-cvc');


cardNumber.on('change', function (event) {
    const errorDiv = document.getElementById('card-number-error');
        errorDiv.textContent = '';
        errorDiv.classList.add('d-none');
    
});

cardExpiry.on('change', function (event) {
    const errorDiv = document.getElementById('card-expiry-error');
        errorDiv.textContent = '';
        errorDiv.classList.add('d-none');
});

// Listen for errors in CVC field
cardCvc.on('change', function (event) {
    const errorDiv = document.getElementById('card-cvc-error');
        errorDiv.textContent = '';
        errorDiv.classList.add('d-none');
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
    } = await stripe.createToken(cardNumber);

    if (error) {
        // Reset button
        button.disabled = false;
        button.innerHTML = buttonText;

      
        console.log(error);
        if (error.code === "incomplete_number" || error.code === "invalid_number") {
            let errorDiv = document.getElementById('card-number-error');
            errorDiv.textContent = error.message;
            errorDiv.classList.remove('d-none');
        } 
        if (error.code === 'invalid_expiry_year' || error.code === 'invalid_expiry_year_past' || error.code === 'invalid_expiry_year_future' || error.code === 'incomplete_expiry') {
            let errorDiv = document.getElementById('card-expiry-error');
            errorDiv.textContent = error.message;
            errorDiv.classList.remove('d-none');
        } 
         if (error.code === 'incomplete_cvc') {
            let errorDiv = document.getElementById('card-cvc-error');
            errorDiv.textContent = error.message;
            errorDiv.classList.remove('d-none');
        }else {
            const fallback = document.getElementById('card-errors');
            return;
            // fallback.textContent = error.message;
        }
    } else {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'stripeToken';
        input.value = token.id;
        form.appendChild(input);
        form.submit();
    }
});











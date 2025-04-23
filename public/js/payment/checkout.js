// document.addEventListener('DOMContentLoaded', function() {
//     let nameInput = document.getElementById('name');
//     let emailInput = document.getElementById('email');

//     [nameInput, emailInput].forEach(input => {
//         input.addEventListener('input', function() {
//             const errorDiv = document.getElementById(`${input.id}-error`);
//             errorDiv.classList.add('d-none');
//         });
//     });

// });

// const elements = stripe.elements();

// //  style for the card element
// const style = {
//     base: {
//         color: '#495057',
//         fontFamily: 'system-ui, -apple-system, "Segoe UI", Roboto, sans-serif',
//         fontSmoothing: 'antialiased',
//         fontSize: '16px',
//         '::placeholder': {
//             color: '#6c757d'
//         },
//         ':-webkit-autofill': {
//             color: '#495057'
//         }
//     },
//     invalid: {
//         color: '#dc3545',
//         iconColor: '#dc3545'
//     }
// };

// const card = elements.create('card', {
//     style: style
// });
// card.mount('#card-element');

// // showing real-time validation errors from the card element
// card.addEventListener('change', function(event) {
//     const displayError = document.getElementById('card-errors');
//     if (event.error) {
//         displayError.textContent = event.error.message;
//     } else {
//         displayError.textContent = '';
//     }
// });

// const form = document.getElementById('payment-form');

// form.addEventListener('submit', async (e) => {
//     e.preventDefault();
//     let nameInput = document.getElementById('name');
//     let emailInput = document.getElementById('email');
//     let isValid = true;

//     if (nameInput.value.trim() === '') {
//         document.getElementById('name-error').classList.remove('d-none');
//         isValid = false;
//     }

//     const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//     if (!emailPattern.test(emailInput.value.trim())) {
//         document.getElementById('email-error').classList.remove('d-none');
//         isValid = false;
//     }

//     if (!isValid) {
//         e.preventDefault();
//         return;
//     }

//     // Show loading state
//     const button = form.querySelector('button[type="submit"]');
//     const buttonText = button.innerHTML;
//     button.disabled = true;
//     button.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';

//     const {
//         token,
//         error
//     } = await stripe.createToken(card);

//     if (error) {
//         // Reset button
//         button.disabled = false;
//         button.innerHTML = buttonText;

//         // Display error
//         const errorElement = document.getElementById('card-errors');
//         errorElement.textContent = error.message;
//     } else {
//         const input = document.createElement('input');
//         input.type = 'hidden';
//         input.name = 'stripeToken';
//         input.value = token.id;
//         form.appendChild(input);
//         form.submit();
//     }
// });


document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('payment-form');
    const cardNumber = document.getElementById('card_number');
    const cardExpiry = document.getElementById('card_expiry');
    const cardCvc = document.getElementById('card_cvc');
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    
    cardNumber.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
        let formattedValue = '';
        
        for (let i = 0; i < value.length; i++) {
            if (i > 0 && i % 4 === 0) {
                formattedValue += ' ';
            }
            formattedValue += value[i];
        }
        
        e.target.value = formattedValue;
    });
    
    cardExpiry.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove non-digits
        
        if (value.length > 2) {
            e.target.value = value.substring(0, 2) + '/' + value.substring(2);
        } else {
            e.target.value = value;
        }
    });
    
    cardCvc.addEventListener('input', function(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        resetErrors();
        
        let isValid = true;
        
        if (!nameInput.value.trim()) {
            showError('name-error');
            nameInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!validateEmail(emailInput.value)) {
            showError('email-error');
            emailInput.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!cardNumber.value.trim() || cardNumber.value.replace(/\s/g, '').length < 15) {
            showError('card-number-error');
            cardNumber.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!validateExpiry(cardExpiry.value)) {
            showError('card-expiry-error');
            cardExpiry.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!cardCvc.value.trim() || cardCvc.value.length < 3) {
            showError('card-cvc-error');
            cardCvc.classList.add('is-invalid');
            isValid = false;
        }
        
        if (isValid) {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Processing...';
            submitBtn.disabled = true;
            
            form.submit();
        }
    });
    
    function resetErrors() {
        const errorElements = document.querySelectorAll('.text-danger');
        errorElements.forEach(el => el.classList.add('d-none'));
        
        const invalidInputs = document.querySelectorAll('.is-invalid');
        invalidInputs.forEach(el => el.classList.remove('is-invalid'));
    }
    
    function showError(id) {
        const errorElement = document.getElementById(id);
        if (errorElement) {
            errorElement.classList.remove('d-none');
        }
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }
    
    function validateExpiry(expiry) {
        if (!expiry || expiry.length !== 5) return false;
        
        const parts = expiry.split('/');
        if (parts.length !== 2) return false;
        
        const month = parseInt(parts[0], 10);
        const year = parseInt('20' + parts[1], 10);
        
        if (isNaN(month) || month < 1 || month > 12) return false;
        
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth = currentDate.getMonth() + 1; 
        
        if (isNaN(year) || year < currentYear) return false;
        if (year === currentYear && month < currentMonth) return false;
        
        return true;
    }
});
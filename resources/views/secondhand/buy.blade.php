<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase {{ $part->part_name }} - NextGen Computing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&family=Roboto:wght@400;500&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #0d1117 0%, #1a202c 100%);
            color: #e2e8f0;
        }
        .header {
            background: #161b22;
            border-bottom: 2px solid #e53e3e;
            box-shadow: 0 0 15px rgba(229, 62, 62, 0.3);
        }
        .header h1 {
            font-family: 'Orbitron', sans-serif;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 0 0 10px rgba(229, 62, 62, 0.5);
        }
        .card {
            background: #1f252d;
            border: 1px solid #2d3748;
            border-radius: 0.75rem;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 20px rgba(229, 62, 62, 0.3);
        }
        .card h3 {
            font-family: 'Orbitron', sans-serif;
            color: #e53e3e;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-primary {
            background: #e53e3e;
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0 10px rgba(229, 62, 62, 0.3);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #c53030;
            box-shadow: 0 0 15px rgba(229, 62, 62, 0.5);
        }
        .btn-primary:disabled {
            background: #6b2d2d;
            cursor: not-allowed;
        }
        .text-primary {
            color: #e53e3e;
        }
        input[type="text"],
        input[type="email"],
        select,
        input[readonly] {
            background: #2d3748;
            border: 1px solid #4a5568;
            color: #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.75rem;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        select:focus {
            border-color: #e53e3e;
            box-shadow: 0 0 10px rgba(229, 62, 62, 0.3);
            outline: none;
        }
        input[type="text"]::placeholder,
        input[type="email"]::placeholder {
            color: #a0aec0;
        }
        select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background: url('data:image/svg+xml;utf8,<svg fill="#e2e8f0" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>') no-repeat right 0.75rem center #2d3748;
        }
        input[type="radio"] {
            accent-color: #e53e3e;
        }
        #card-element {
            background: #2d3748;
            border: 1px solid #4a5568;
            border-radius: 0.5rem;
            padding: 0.75rem;
            color: #e2e8f0;
            min-height: 40px;
        }
        #card-element.StripeElement--focus {
            border-color: #e53e3e;
            box-shadow: 0 0 10px rgba(229, 62, 62, 0.3);
        }
        .payment-option-details {
            transition: all 0.3s ease;
            max-height: 0;
            overflow: hidden;
            opacity: 0;
        }
        .payment-option-details.active {
            max-height: 300px;
            opacity: 1;
            margin-top: 1rem;
        }
        .form-spinner {
            display: none;
            margin-left: 10px;
            vertical-align: middle;
        }
        .btn-loading .form-spinner {
            display: inline-block;
        }
        .card-detail-instruction {
            color: #a0aec0;
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
        }
        .error-border {
            border-color: #f56565 !important;
        }
        .error-message {
            color: #f56565;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <header class="header p-4 flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <a href="{{ route('secondhand.show', $part->id) }}" class="text-white hover:text-gray-300">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="text-sm text-gray-400">NEXTGEN COMPUTING</div>
        </div>
        <h1 class="text-2xl font-bold">Second-hand Market</h1>
        <div class="text-2xl">
            <i class="fas fa-user-circle text-primary"></i>
        </div>
    </header>

    <div class="min-h-screen p-6 flex items-center justify-center">
        <div class="w-full max-w-2xl">
            <div class="card p-8">
                <h3 class="text-xl font-semibold mb-6 text-center">Buy Components</h3>

                @if (session('error'))
                    <div class="bg-red-900/50 text-red-300 rounded-xl p-4 mb-6 text-center border border-red-700/50">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-900/50 text-red-300 rounded-xl p-4 mb-6 text-center border border-red-700/50">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('secondhand.buy', $part->id) }}" method="POST" id="payment-form" novalidate>
                    @csrf
                    @method('POST')

                    <div class="mb-6">
                        <label for="first_name" class="block text-gray-300 font-medium mb-2">Customer Name</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="first_name" id="first_name" class="w-full @error('first_name') error-border @enderror" value="{{ old('first_name', $customer->first_name ?? '') }}" placeholder="First Name" required>
                            <input type="text" name="last_name" id="last_name" class="w-full @error('last_name') error-border @enderror" value="{{ old('last_name', $customer->last_name ?? '') }}" placeholder="Last Name" required>
                        </div>
                        @error('first_name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                        @error('last_name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-gray-300 font-medium mb-2">Email</label>
                        <input type="email" name="email" id="email" class="w-full @error('email') error-border @enderror" value="{{ old('email', $customer->email ?? '') }}" placeholder="your.email@example.com" required>
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="phone_number" class="block text-gray-300 font-medium mb-2">Contact Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="w-full @error('phone_number') error-border @enderror" value="{{ old('phone_number', $customer->phone_number ?? '') }}" placeholder="07X XXX XXXX">
                        @error('phone_number')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="country" class="block text-gray-300 font-medium mb-2">Country</label>
                        <input type="text" name="country" id="country" class="w-full @error('country') error-border @enderror" value="{{ old('country', 'Sri Lanka') }}" readonly>
                        @error('country')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="province" class="block text-gray-300 font-medium mb-2">Province</label>
                        <select name="province" id="province" class="w-full @error('province') error-border @enderror" required>
                            <option value="">Select Province</option>
                            <option value="Central" {{ old('province') == 'Central' ? 'selected' : '' }}>Central</option>
                            <option value="Eastern" {{ old('province') == 'Eastern' ? 'selected' : '' }}>Eastern</option>
                            <option value="North Central" {{ old('province') == 'North Central' ? 'selected' : '' }}>North Central</option>
                            <option value="Northern" {{ old('province') == 'Northern' ? 'selected' : '' }}>Northern</option>
                            <option value="North Western" {{ old('province') == 'North Western' ? 'selected' : '' }}>North Western</option>
                            <option value="Sabaragamuwa" {{ old('province') == 'Sabaragamuwa' ? 'selected' : '' }}>Sabaragamuwa</option>
                            <option value="Southern" {{ old('province') == 'Southern' ? 'selected' : '' }}>Southern</option>
                            <option value="Uva" {{ old('province') == 'Uva' ? 'selected' : '' }}>Uva</option>
                            <option value="Western" {{ old('province') == 'Western' ? 'selected' : '' }}>Western</option>
                        </select>
                        @error('province')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="district" class="block text-gray-300 font-medium mb-2">District</label>
                        <select name="district" id="district" class="w-full @error('district') error-border @enderror" required>
                            <option value="">Select District</option>
                            <!-- Populated by JavaScript -->
                        </select>
                        @error('district')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="Zipcode" class="block text-gray-300 font-medium mb-2">Zip Code</label>
                        <input type="text" name="Zipcode" id="Zipcode" class="w-full @error('Zipcode') error-border @enderror" value="{{ old('Zipcode') }}" placeholder="00000">
                        @error('Zipcode')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-300 font-medium mb-2">Payment Option</label>
                        <div class="flex flex-wrap gap-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_option" value="Credit Card" class="mr-2 payment-option-radio" {{ old('payment_option', 'Credit Card') == 'Credit Card' ? 'checked' : '' }} required>
                                <span class="text-gray-300">Credit Card</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_option" value="Debit Card" class="mr-2 payment-option-radio" {{ old('payment_option') == 'Debit Card' ? 'checked' : '' }}>
                                <span class="text-gray-300">Debit Card</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="payment_option" value="PayPal" class="mr-2 payment-option-radio" {{ old('payment_option') == 'PayPal' ? 'checked' : '' }}>
                                <span class="text-gray-300">PayPal</span>
                            </label>
                        </div>
                        @error('payment_option')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="credit-card-section" class="payment-option-details active mb-6">
                        <label for="card-element" class="block text-gray-300 font-medium mb-2">Card Details</label>
                        <p class="card-detail-instruction">Enter your card information below. We accept Visa, Mastercard, and American Express.</p>
                        <div id="card-element" class="p-4"></div>
                        <div id="card-errors" class="error-message"></div>
                    </div>

                    <div id="paypal-section" class="payment-option-details mb-6">
                        <p class="text-gray-300 mb-2">You will be redirected to PayPal to complete your payment after clicking "Buy".</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-300 font-medium mb-2">Component Price</label>
                        <input type="text" class="w-full" value="{{ number_format($part->price, 2) }} LKR" readonly>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-300 font-medium mb-2">Verify Product (Extra 10% charges from product price)</label>
                        <div class="flex space-x-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="verify_product" value="1" class="mr-2" {{ old('verify_product', '1') == '1' ? 'checked' : '' }} required>
                                <span class="text-gray-300">Yes</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="verify_product" value="0" class="mr-2" {{ old('verify_product') == '0' ? 'checked' : '' }}>
                                <span class="text-gray-300">No</span>
                            </label>
                        </div>
                        <p class="text-gray-400 text-sm mt-1">We'll test the component to ensure it's working properly before shipping.</p>
                        @error('verify_product')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="shipping_charges_display" class="block text-gray-300 font-medium mb-2">Shipping Charges</label>
                        <input type="text" id="shipping_charges_display" class="w-full @error('shipping_charges') error-border @enderror" value="{{ old('shipping_charges', '0.00') }} LKR" readonly>
                        <input type="hidden" name="shipping_charges" id="shipping_charges" value="{{ old('shipping_charges', '0.00') }}">
                        @error('shipping_charges')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-300 font-medium mb-2">Total Cost</label>
                        <input type="text" id="total_cost" class="w-full" value="{{ number_format($part->price, 2) }} LKR" readonly>
                        <input type="hidden" id="total_cost_hidden" name="total_cost_hidden" value="{{ $part->price }}">
                    </div>

                    <input type="hidden" id="payment_method" name="payment_method">

                    <div class="text-center">
                        <button type="submit" class="btn-primary text-white py-3 px-8 rounded-full text-lg font-medium" id="submit-button">
                            Buy
                            <span class="form-spinner">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Configuration
            const componentPrice = {{ $part->price }};
            const shippingChargesByDistrict = {
                'Colombo': 300.00,
                'Gampaha': 350.00,
                'Kalutara': 350.00,
                'Kandy': 400.00,
                'Matale': 450.00,
                'Nuwara Eliya': 500.00,
                'Ampara': 600.00,
                'Batticaloa': 600.00,
                'Trincomalee': 600.00,
                'Anuradhapura': 550.00,
                'Polonnaruwa': 550.00,
                'Jaffna': 700.00,
                'Kilinochchi': 700.00,
                'Mannar': 700.00,
                'Mullaitivu': 700.00,
                'Vavuniya': 700.00,
                'Kurunegala': 450.00,
                'Puttalam': 500.00,
                'Kegalle': 400.00,
                'Ratnapura': 450.00,
                'Galle': 400.00,
                'Hambantota': 500.00,
                'Matara': 400.00,
                'Badulla': 500.00,
                'Moneragala': 550.00,
                'default': 500.00
            };
            const districtsByProvince = {
                'Central': ['Kandy', 'Matale', 'Nuwara Eliya'],
                'Eastern': ['Ampara', 'Batticaloa', 'Trincomalee'],
                'North Central': ['Anuradhapura', 'Polonnaruwa'],
                'Northern': ['Jaffna', 'Kilinochchi', 'Mannar', 'Mullaitivu', 'Vavuniya'],
                'North Western': ['Kurunegala', 'Puttalam'],
                'Sabaragamuwa': ['Kegalle', 'Ratnapura'],
                'Southern': ['Galle', 'Hambantota', 'Matara'],
                'Uva': ['Badulla', 'Moneragala'],
                'Western': ['Colombo', 'Gampaha', 'Kalutara']
            };

            // DOM Elements
            const form = document.getElementById('payment-form');
            const submitButton = document.getElementById('submit-button');
            const cardErrors = document.getElementById('card-errors');
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const shippingChargesDisplay = document.getElementById('shipping_charges_display');
            const shippingChargesInput = document.getElementById('shipping_charges');
            const totalCostInput = document.getElementById('total_cost');
            const totalCostHiddenInput = document.getElementById('total_cost_hidden');
            const verifyProductRadios = document.querySelectorAll('input[name="verify_product"]');
            const paymentOptionRadios = document.querySelectorAll('.payment-option-radio');
            const creditCardSection = document.getElementById('credit-card-section');
            const paypalSection = document.getElementById('paypal-section');

            // State
            let shippingCharges = parseFloat(shippingChargesInput.value) || shippingChargesByDistrict['default'];
            let isSubmitting = false;

            // Total Cost Calculation
            function calculateTotalCost() {
                const verifyProduct = document.querySelector('input[name="verify_product"]:checked')?.value === '1';
                const verifyCost = verifyProduct ? componentPrice * 0.10 : 0;
                const totalCost = componentPrice + verifyCost + shippingCharges;
                totalCostInput.value = totalCost.toFixed(2) + ' LKR';
                totalCostHiddenInput.value = totalCost.toFixed(2);
                shippingChargesDisplay.value = shippingCharges.toFixed(2) + ' LKR';
                shippingChargesInput.value = shippingCharges.toFixed(2);
            }

            // Populate Districts
            function populateDistricts(province) {
                districtSelect.innerHTML = '<option value="">Select District</option>';
                if (province && districtsByProvince[province]) {
                    districtsByProvince[province].forEach(district => {
                        const option = document.createElement('option');
                        option.value = district;
                        option.textContent = district;
                        districtSelect.appendChild(option);
                    });
                }
                shippingCharges = shippingChargesByDistrict['default'];
                calculateTotalCost();
            }

            // Toggle Payment Options
            function togglePaymentOptions() {
                const selectedOption = document.querySelector('input[name="payment_option"]:checked')?.value;
                creditCardSection.classList.toggle('active', selectedOption === 'Credit Card' || selectedOption === 'Debit Card');
                paypalSection.classList.toggle('active', selectedOption === 'PayPal');
            }

            // Initialize Form
            shippingChargesDisplay.value = shippingCharges.toFixed(2) + ' LKR';
            shippingChargesInput.value = shippingCharges.toFixed(2);
            calculateTotalCost();
            togglePaymentOptions();
            if (provinceSelect.value) {
                populateDistricts(provinceSelect.value);
                const oldDistrict = "{{ old('district') }}";
                if (oldDistrict) {
                    districtSelect.value = oldDistrict;
                    shippingCharges = shippingChargesByDistrict[oldDistrict] || shippingChargesByDistrict['default'];
                    calculateTotalCost();
                }
            }

            // Event Listeners
            provinceSelect.addEventListener('change', () => populateDistricts(provinceSelect.value));
            districtSelect.addEventListener('change', () => {
                const selectedDistrict = districtSelect.value;
                shippingCharges = shippingChargesByDistrict[selectedDistrict] || shippingChargesByDistrict['default'];
                calculateTotalCost();
            });
            verifyProductRadios.forEach(radio => radio.addEventListener('change', calculateTotalCost));
            paymentOptionRadios.forEach(radio => radio.addEventListener('change', togglePaymentOptions));

            // Stripe Setup
            const stripeKey = '{{ env('STRIPE_KEY') }}';
            let stripe, elements, cardElement;
            if (!stripeKey) {
                cardErrors.textContent = 'Payment system unavailable. Please contact support.';
                submitButton.disabled = true;
                return;
            }

            try {
                stripe = Stripe(stripeKey);
                elements = stripe.elements();
                cardElement = elements.create('card', {
                    style: {
                        base: {
                            color: '#e2e8f0',
                            fontFamily: '"Roboto", sans-serif',
                            fontSize: '16px',
                            '::placeholder': { color: '#a0aec0' },
                            iconColor: '#e53e3e'
                        },
                        invalid: {
                            color: '#f56565',
                            iconColor: '#f56565'
                        }
                    }
                });
                cardElement.mount('#card-element');
            } catch (e) {
                cardErrors.textContent = 'Failed to initialize payment system: ' + e.message;
                submitButton.disabled = true;
                return;
            }

            cardElement.on('change', event => {
                cardErrors.textContent = event.error ? event.error.message : '';
            });

            // Form Submission
            form.addEventListener('submit', async event => {
                event.preventDefault();
                if (isSubmitting) return;
                isSubmitting = true;
                submitButton.disabled = true;
                submitButton.classList.add('btn-loading');
                cardErrors.textContent = '';

                // Client-side Validation
                const requiredFields = form.querySelectorAll('input[required], select[required]');
                let isValid = true;
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('error-border');
                        isValid = false;
                    } else {
                        field.classList.remove('error-border');
                    }
                });

                if (!isValid) {
                    cardErrors.textContent = 'Please fill all required fields.';
                    resetSubmitButton();
                    return;
                }

                const paymentOption = document.querySelector('input[name="payment_option"]:checked')?.value;
                if (paymentOption === 'PayPal') {
                    // Redirect to PayPal route (handled server-side)
                    form.action = "{{ route('secondhand.buy.paypal', $part->id) }}";
                    form.submit();
                    return;
                }

                try {
                    const countryMapping = {
                        'Sri Lanka': 'LK',
                        'United States': 'US',
                        'United Kingdom': 'GB'
                    };
                    const countryCode = countryMapping[form.country.value] || form.country.value;

                    const { paymentMethod, error } = await stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElement,
                        billing_details: {
                            name: `${form.first_name.value} ${form.last_name.value}`,
                            email: form.email.value,
                            phone: form.phone_number.value || null,
                            address: {
                                country: countryCode,
                                state: form.province.value,
                                city: form.district.value,
                                postal_code: form.Zipcode.value || null
                            }
                        }
                    });

                    if (error) {
                        cardErrors.textContent = error.message || 'Invalid card details.';
                        resetSubmitButton();
                        return;
                    }

                    form.payment_method.value = paymentMethod.id;
                    form.submit();
                } catch (e) {
                    cardErrors.textContent = 'Payment processing error: ' + e.message;
                    resetSubmitButton();
                }
            });

            function resetSubmitButton() {
                isSubmitting = false;
                submitButton.disabled = false;
                submitButton.classList.remove('btn-loading');
            }
        });
    </script>

    @include('include.footer')
</body>
</html>
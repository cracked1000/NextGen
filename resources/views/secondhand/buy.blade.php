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
        }
        #card-element:focus {
            border-color: #e53e3e;
            box-shadow: 0 0 10px rgba(229, 62, 62, 0.3);
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

                <form action="{{ route('secondhand.buy', $part->id) }}" method="POST" id="payment-form">
                    @csrf

                    <div class="mb-6">
                        <label for="first_name" class="block text-gray-300 font-medium mb-2">Customer Name</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="first_name" id="first_name" class="w-full @error('first_name') border-red-500 @enderror" value="{{ old('first_name', $customer ? $customer->first_name : '') }}" placeholder="First Name" required>
                            <input type="text" name="last_name" id="last_name" class="w-full @error('last_name') border-red-500 @enderror" value="{{ old('last_name', $customer ? $customer->last_name : '') }}" placeholder="Last Name" required>
                        </div>
                        @error('first_name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        @error('last_name')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block text-gray-300 font-medium mb-2">Email</label>
                        <input type="email" name="email" id="email" class="w-full @error('email') border-red-500 @enderror" value="{{ old('email', $customer ? $customer->email : '') }}" required>
                        @error('email')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="phone_number" class="block text-gray-300 font-medium mb-2">Contact Number</label>
                        <input type="text" name="phone_number" id="phone_number" class="w-full @error('phone_number') border-red-500 @enderror" value="{{ old('phone_number', $customer ? $customer->phone_number : '') }}">
                        @error('phone_number')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="country" class="block text-gray-300 font-medium mb-2">Country</label>
                        <input type="text" name="country" id="country" class="w-full @error('country') border-red-500 @enderror" value="{{ old('country', 'Sri Lanka') }}" readonly>
                        @error('country')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="province" class="block text-gray-300 font-medium mb-2">Province</label>
                        <select name="province" id="province" class="w-full @error('province') border-red-500 @enderror">
                            <option value="" class="text-gray-300">Select Province</option>
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
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="district" class="block text-gray-300 font-medium mb-2">District</label>
                        <select name="district" id="district" class="w-full @error('district') border-red-500 @enderror">
                            <option value="">Select District</option>
                            <optgroup label="Central">
                                <option value="Kandy" {{ old('district') == 'Kandy' ? 'selected' : '' }}>Kandy</option>
                                <option value="Matale" {{ old('district') == 'Matale' ? 'selected' : '' }}>Matale</option>
                                <option value="Nuwara Eliya" {{ old('district') == 'Nuwara Eliya' ? 'selected' : '' }}>Nuwara Eliya</option>
                            </optgroup>
                            <optgroup label="Eastern">
                                <option value="Ampara" {{ old('district') == 'Ampara' ? 'selected' : '' }}>Ampara</option>
                                <option value="Batticaloa" {{ old('district') == 'Batticaloa' ? 'selected' : '' }}>Batticaloa</option>
                                <option value="Trincomalee" {{ old('district') == 'Trincomalee' ? 'selected' : '' }}>Trincomalee</option>
                            </optgroup>
                            <optgroup label="North Central">
                                <option value="Anuradhapura" {{ old('district') == 'Anuradhapura' ? 'selected' : '' }}>Anuradhapura</option>
                                <option value="Polonnaruwa" {{ old('district') == 'Polonnaruwa' ? 'selected' : '' }}>Polonnaruwa</option>
                            </optgroup>
                            <optgroup label="Northern">
                                <option value="Jaffna" {{ old('district') == 'Jaffna' ? 'selected' : '' }}>Jaffna</option>
                                <option value="Kilinochchi" {{ old('district') == 'Kilinochchi' ? 'selected' : '' }}>Kilinochchi</option>
                                <option value="Mannar" {{ old('district') == 'Mannar' ? 'selected' : '' }}>Mannar</option>
                                <option value="Mullaitivu" {{ old('district') == 'Mullaitivu' ? 'selected' : '' }}>Mullaitivu</option>
                                <option value="Vavuniya" {{ old('district') == 'Vavuniya' ? 'selected' : '' }}>Vavuniya</option>
                            </optgroup>
                            <optgroup label="North Western">
                                <option value="Kurunegala" {{ old('district') == 'Kurunegala' ? 'selected' : '' }}>Kurunegala</option>
                                <option value="Puttalam" {{ old('district') == 'Puttalam' ? 'selected' : '' }}>Puttalam</option>
                            </optgroup>
                            <optgroup label="Sabaragamuwa">
                                <option value="Kegalle" {{ old('district') == 'Kegalle' ? 'selected' : '' }}>Kegalle</option>
                                <option value="Ratnapura" {{ old('district') == 'Ratnapura' ? 'selected' : '' }}>Ratnapura</option>
                            </optgroup>
                            <optgroup label="Southern">
                                <option value="Galle" {{ old('district') == 'Galle' ? 'selected' : '' }}>Galle</option>
                                <option value="Hambantota" {{ old('district') == 'Hambantota' ? 'selected' : '' }}>Hambantota</option>
                                <option value="Matara" {{ old('district') == 'Matara' ? 'selected' : '' }}>Matara</option>
                            </optgroup>
                            <optgroup label="Uva">
                                <option value="Badulla" {{ old('district') == 'Badulla' ? 'selected' : '' }}>Badulla</option>
                                <option value="Moneragala" {{ old('district') == 'Moneragala' ? 'selected' : '' }}>Moneragala</option>
                            </optgroup>
                            <optgroup label="Western">
                                <option value="Colombo" {{ old('district') == 'Colombo' ? 'selected' : '' }}>Colombo</option>
                                <option value="Gampaha" {{ old('district') == 'Gampaha' ? 'selected' : '' }}>Gampaha</option>
                                <option value="Kalutara" {{ old('district') == 'Kalutara' ? 'selected' : '' }}>Kalutara</option>
                            </optgroup>
                        </select>
                        @error('district')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="Zipcode" class="block text-gray-300 font-medium mb-2">Zip Code</label>
                        <input type="text" name="Zipcode" id="Zipcode" class="w-full @error('Zipcode') border-red-500 @enderror" value="{{ old('Zipcode') }}">
                        @error('Zipcode')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-300 font-medium mb-2">Payment Option</label>
                        <div class="flex space-x-6">
                            <label class="flex items-center">
                                <input type="radio" name="payment_option" value="Credit Card" class="mr-2" {{ old('payment_option') == 'Credit Card' ? 'checked' : '' }} required>
                                <span class="text-gray-300">Credit Card</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="payment_option" value="Debit Card" class="mr-2" {{ old('payment_option') == 'Debit Card' ? 'checked' : '' }}>
                                <span class="text-gray-300">Debit Card</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="payment_option" value="PayPal" class="mr-2" {{ old('payment_option') == 'PayPal' ? 'checked' : '' }}>
                                <span class="text-gray-300">PayPal</span>
                            </label>
                        </div>
                        @error('payment_option')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="card-element" class="block text-gray-300 font-medium mb-2">Card Details</label>
                        <div id="card-element"></div>
                        <div id="card-errors" class="text-red-400 text-sm mt-1"></div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-300 font-medium mb-2">Component Price</label>
                        <input type="text" class="w-full" value="{{ number_format($part->price, 2) }} LKR" readonly>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-300 font-medium mb-2">Verify Product (Extra 10% charges from product price)</label>
                        <div class="flex space-x-6">
                            <label class="flex items-center">
                                <input type="radio" name="verify_product" value="1" class="mr-2" {{ old('verify_product', '1') == '1' ? 'checked' : '' }} required>
                                <span class="text-gray-300">Yes</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="verify_product" value="0" class="mr-2" {{ old('verify_product', '1') == '0' ? 'checked' : '' }}>
                                <span class="text-gray-300">No</span>
                            </label>
                        </div>
                        @error('verify_product')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="shipping_charges" class="block text-gray-300 font-medium mb-2">Shipping Charges</label>
                        <input type="text" name="shipping_charges" id="shipping_charges" class="w-full" value="{{ old('shipping_charges', '0.00') }}" readonly>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-300 font-medium mb-2">Total Cost</label>
                        <input type="text" id="total_cost" name="total_cost" class="w-full" value="{{ number_format($part->price, 2) }} LKR" readonly>
                        <input type="hidden" id="total_cost_hidden" name="total_cost_hidden" value="{{ $part->price }}">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn-primary text-white py-2 px-6 rounded-full" id="submit-button">Buy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Total Cost Calculation
            const verifyProductRadios = document.querySelectorAll('input[name="verify_product"]');
            const componentPrice = {{ $part->price }};
            const shippingChargesInput = document.getElementById('shipping_charges');
            const totalCostInput = document.getElementById('total_cost');
            const totalCostHiddenInput = document.getElementById('total_cost_hidden');

            const shippingCharges = 0.00;
            shippingChargesInput.value = shippingCharges.toFixed(2);

            function calculateTotalCost() {
                const verifyProduct = document.querySelector('input[name="verify_product"]:checked').value === '1';
                const verifyCost = verifyProduct ? componentPrice * 0.10 : 0;
                const totalCost = componentPrice + verifyCost + shippingCharges;
                totalCostInput.value = totalCost.toFixed(2) + ' LKR';
                totalCostHiddenInput.value = totalCost.toFixed(2);
            }

            verifyProductRadios.forEach(radio => {
                radio.addEventListener('change', calculateTotalCost);
            });

            calculateTotalCost();

            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
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

            provinceSelect.addEventListener('change', function () {
                const selectedProvince = this.value;
                districtSelect.innerHTML = '<option value="">Select District</option>';

                if (selectedProvince && districtsByProvince[selectedProvince]) {
                    districtsByProvince[selectedProvince].forEach(district => {
                        const option = document.createElement('option');
                        option.value = district;
                        option.textContent = district;
                        districtSelect.appendChild(option);
                    });
                }
            });

            if (provinceSelect.value) {
                provinceSelect.dispatchEvent(new Event('change'));
            }

            const stripe = Stripe('{{ env('STRIPE_KEY') }}');
            const elements = stripe.elements();
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        color: '#e2e8f0',
                        fontFamily: '"Roboto", sans-serif',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#a0aec0',
                        },
                    },
                    invalid: {
                        color: '#f56565',
                    },
                },
            });
            cardElement.mount('#card-element');

            const form = document.getElementById('payment-form');
            const cardErrors = document.getElementById('card-errors');
            const submitButton = document.getElementById('submit-button');

            form.addEventListener('submit', async (event) => {
                event.preventDefault();
                submitButton.disabled = true;
                submitButton.textContent = 'Processing...';

                console.log('Starting Stripe payment method creation');
                const startTime = performance.now();

                const { paymentIntent, error } = await stripe.createPaymentMethod({
                    type: 'card',
                    card: cardElement,
                    billing_details: {
                        name: `${form.first_name.value} ${form.last_name.value}`,
                        email: form.email.value,
                        phone: form.phone_number.value,
                        address: {
                            country: form.country.value,
                            state: form.province.value,
                            city: form.district.value,
                            postal_code: form.Zipcode.value,
                        },
                    },
                });

                const duration = performance.now() - startTime;
                console.log(`Stripe payment method creation completed in ${duration}ms`);

                if (error) {
                    console.error('Stripe payment method creation failed:', error);
                    cardErrors.textContent = error.message;
                    submitButton.disabled = false;
                    submitButton.textContent = 'Buy';
                } else {
                    console.log('Stripe payment method created successfully:', paymentIntent.id);
                    const paymentMethodId = paymentIntent.id;
                    const hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'payment_method');
                    hiddenInput.setAttribute('value', paymentMethodId);
                    form.appendChild(hiddenInput);
                    form.submit();
                }
            });
        });
    </script>   
    @include('include.footer')
</body>
</html>
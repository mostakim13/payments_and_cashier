<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /*body {*/
        /*    display: flex;*/
        /*    justify-content: center;*/
        /*    align-items: center;*/
        /*    height: 100vh;*/
        /*    background-color: #f8f9fa;*/
        /*}*/

        .subscription-form {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .subscription-form h2 {
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .subscription-form .form-group {
            margin-bottom: 1.5rem;
        }

        .subscription-form #card-element {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        .subscription-form #submit-button {
            width: 100%;
        }

        .subscription-form .error-message {
            color: #dc3545;
            margin-top: 10px;
        }
    </style>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    @include('layouts.navigation')

    <!-- Page Heading -->
    @if (isset($header))
        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
    @endif

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ env('STRIPE_KEY') }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const cardErrors = document.getElementById('card-errors');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        submitButton.disabled = true;

        const {paymentMethod, error} = await stripe.createPaymentMethod(
            'card', cardElement
        );

        if (error) {
            cardErrors.textContent = error.message;
            submitButton.disabled = false;
        } else {
            document.getElementById('payment-method').value = paymentMethod.id;
            form.submit();
        }
    });
</script>
</body>
</html>

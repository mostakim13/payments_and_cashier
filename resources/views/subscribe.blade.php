<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscribe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }

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
</head>
<body>

<div class="subscription-form">
    <h2>Subscribe</h2>
    <form action="{{ route('subscription.create') }}" method="POST" id="payment-form">
        @csrf
        <input type="hidden" name="paymentMethod" id="payment-method">

        <div class="form-group">
            <label for="card-element">Credit or Debit Card</label>
            <div id="card-element"></div>
            <div id="card-errors" class="error-message"></div>
        </div>

        <button type="submit" class="btn btn-primary" id="submit-button">Subscribe</button>
    </form>
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Card</title>
</head>
<body>
<form action="{{route('custom.card')}}" method="POST">
    @csrf
    <input type="text" name="card_number" placeholder="Card Number" required>
    <input type="text" name="exp_month" placeholder="Exp Month (MM)" required>
    <input type="text" name="exp_year" placeholder="Exp Year (YY)" required>
    <input type="text" name="cvc" placeholder="CVC" required>
    <button type="submit">Pay Now</button>
</form>

</body>
</html>
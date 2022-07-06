<!DOCTYPE html>
<html>
<body>

<h1>Thanh toan vnpay</h1>


<form action="{{route('checkout.vnpay.post')}}" method="post">
    @csrf
    <button type="submit">Thanh toan</button>
</form>


</body>
</html>

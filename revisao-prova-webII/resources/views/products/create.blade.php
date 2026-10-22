<h1> Cadastrar Produto </h1>
<form method="POST" action="{{ route('products.store') }}">
    @csrf

    Name:
    <input type="text" name="name"> <br>
    Description:
    <input type="text" name="description"> <br>
    Expiration Date:
    <input type="date" name="expiration_date"> <br>
    Value:
    <input type="number" name="value"> <br>

    <button type="submit">Cadastrar</button>

</form>
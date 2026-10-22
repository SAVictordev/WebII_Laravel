<h1>Editar Produto</h1>
<form method="POST" action="{{ route('products.update', $product) }}">
    @csrf
    @method('PUT')

    Name:
    <input name="name" value="{{ $product->name }}"> <br>
    Description:
    <input name="description" value="{{ $product->description }}"> <br>
    Expiration Date:
    <input type="date" name="expiration_date" value="{{ $product->expiration_date }}"> <br>
    Value:
    <input name="value" value="{{ $product->value }}"> <br>

    <button type="submit">Editar Produto</button>
</form>
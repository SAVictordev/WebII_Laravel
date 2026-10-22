@foreach ($products as $product)
    {{ $product->id }} -
    {{ $product->name }} -
    {{ $product->expiration_date }} -
    {{ $product->value }} -
    <a href="{{ route('products.edit', $product) }}">Editar</a> -
    <a href="{{ route('products.show', $product) }}">Detalhes</a>
    <form method="POST" action="{{ route('products.destroy', $product) }}">
        @csrf
        @method('DELETE')
        <button type="submit">Excluir</button>
    </form>
@endforeach
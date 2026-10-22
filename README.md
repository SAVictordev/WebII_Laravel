# Desenvolvimento para Web II — Laravel

Repositório com as atividades práticas desenvolvidas na disciplina **Desenvolvimento para Web II**, cujo foco é a construção de aplicações web em **PHP** utilizando o framework **Laravel**.

Cada pasta do repositório é um projeto Laravel independente, criado para praticar um conteúdo específico apresentado em aula. Juntas, elas mostram a evolução da disciplina: da modelagem do banco de dados com migrations até um CRUD completo seguindo a arquitetura MVC.

## Sumário

- [Tecnologias](#tecnologias)
- [Estrutura do repositório](#estrutura-do-repositório)
- [Conteúdos da disciplina](#conteúdos-da-disciplina)
  - [Atividade 01 — Migrations](#atividade-01--migrations)
  - [Atividade 02 — Alterando tabelas com migrations](#atividade-02--alterando-tabelas-com-migrations)
  - [Atividade 03 — Models, Eloquent e relacionamentos](#atividade-03--models-eloquent-e-relacionamentos)
  - [Revisão para a prova — CRUD com Resource Controller](#revisão-para-a-prova--crud-com-resource-controller)
- [Comandos Artisan utilizados](#comandos-artisan-utilizados)
- [Como executar um projeto](#como-executar-um-projeto)
- [Autor](#autor)

## Tecnologias

- **PHP 8.3+**
- **Laravel 13**
- **Composer**
- **SQLite** (banco padrão dos projetos)
- **Blade** (templates das views)
- **Git / GitHub**

## Estrutura do repositório

| Pasta | Conteúdo |
| --- | --- |
| [atividade01-migrations](atividade01-migrations) | Criação de tabela com migrations |
| [atividade02-migrations02](atividade02-migrations02) | Adição e renomeação de colunas em tabelas existentes |
| [atividade03-models](atividade03-models) | Models, Eloquent, mutators e relacionamentos 1:1 e 1:N |
| [revisao-prova-webII](revisao-prova-webII) | CRUD de produtos com Resource Controller e views Blade |

## Conteúdos da disciplina

### Atividade 01 — Migrations

Introdução ao Laravel e ao controle de versão do banco de dados com **migrations**.

- Criação da tabela `patients` com o *Schema Builder*.
- Tipos de coluna: `id()`, `string()` com tamanho, `date()` e `timestamps()`.
- Restrição de unicidade com `unique()` no campo `email`.
- Métodos `up()` (aplica a mudança) e `down()` (desfaz com `dropIfExists`).

```php
Schema::create('patients', function (Blueprint $table) {
    $table->id();
    $table->string('first_name', 100);
    $table->date('date_of_birth');
    $table->string('email', 100)->unique();
    $table->timestamps();
});
```

### Atividade 02 — Alterando tabelas com migrations

Evolução da estrutura de uma tabela já existente, sem recriá-la.

- Criação da tabela `books` com colunas `integer` e `decimal(11, 2)`.
- Nova migration usando `Schema::table()` para **adicionar** a coluna `isbn`.
- Migration para **renomear** a coluna `value` para `price` com `renameColumn()`.
- Reversão das alterações no `down()` com `dropColumn()` e a renomeação inversa.

### Atividade 03 — Models, Eloquent e relacionamentos

Uso do **Eloquent ORM** para representar tabelas como classes PHP e relacioná-las.

- Models `Book`, `BookDetail` e `Author`.
- Atributo `#[Fillable]` para definir os campos liberados para *mass assignment*.
- **Mutator** com `Attribute::make()` que remove hífens e espaços do ISBN antes de salvar.
- Chaves estrangeiras com `foreignId()->constrained()`, usando `cascadeOnDelete()` e `restrictOnDelete()`.
- Criação e teste de registros pelo terminal com `php artisan tinker`.

**Relacionamentos implementados:**

| Tipo | Relação | Métodos |
| --- | --- | --- |
| 1 para 1 | Um livro tem um detalhe | `Book::detail()` → `hasOne` / `BookDetail::book()` → `belongsTo` |
| 1 para N | Um autor tem vários livros | `Author::books()` → `hasMany` / `Book::author()` → `belongsTo` |

```php
public function author(): BelongsTo
{
    return $this->belongsTo(Author::class);
}

protected function isbn(): Attribute
{
    return Attribute::make(
        set: fn (string $value) => str_replace(['-', ' '], '', $value),
    );
}
```

### Revisão para a prova — CRUD com Resource Controller

Projeto que reúne os conteúdos anteriores em uma aplicação completa seguindo o padrão **MVC**: um CRUD de produtos.

#### Passo 1 — Criar o projeto

```bash
composer create-project laravel/laravel revisao-prova-webII
cd revisao-prova-webII
```

O projeto já vem configurado com **SQLite**, usando o arquivo `database/database.sqlite`.

#### Passo 2 — Criar o Model e a Migration

```bash
php artisan make:model Product -m
```

A opção `-m` cria junto a migration da tabela `products`. Nela foram definidas as colunas:

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('description');
    $table->date('expiration_date');
    $table->float('value');
    $table->timestamps();
});
```

#### Passo 3 — Liberar os campos no Model

Em `app/Models/Product.php`, os campos que podem ser preenchidos em massa (por `create()` e `update()`) foram liberados com o atributo `#[Fillable]`:

```php
#[Fillable([
    'name',
    'description',
    'expiration_date',
    'value'])]

class Product extends Model
{
    //
}
```

#### Passo 4 — Rodar a migration

```bash
php artisan migrate
```

#### Passo 5 — Criar o Resource Controller

```bash
php artisan make:controller ProductController --resource --model=Product
```

O Laravel gera o controller com os 7 métodos do CRUD. Com `--model`, os métodos já recebem o `Product` como parâmetro (*Route Model Binding*): o Laravel busca o produto pelo `id` da URL e o entrega pronto ao método.

#### Passo 6 — Registrar as rotas

Em `routes/web.php`, uma única linha cria todas as rotas do CRUD:

```php
use App\Http\Controllers\ProductController;

Route::resource('products', ProductController::class);
```

Para ver as rotas geradas, use `php artisan route:list`:

| Verbo | URI | Método | Ação |
| --- | --- | --- | --- |
| GET | `/products` | `index` | Lista os produtos |
| GET | `/products/create` | `create` | Formulário de cadastro |
| POST | `/products` | `store` | Salva um novo produto |
| GET | `/products/{product}` | `show` | Exibe um produto |
| GET | `/products/{product}/edit` | `edit` | Formulário de edição |
| PUT/PATCH | `/products/{product}` | `update` | Atualiza um produto |
| DELETE | `/products/{product}` | `destroy` | Remove um produto |

#### Passo 7 — Implementar os métodos do Controller

```php
public function index()
{
    $products = Product::all();
    return view('products.index', compact('products'));
}

public function create()
{
    return view('products.create');
}

public function store(Request $request)
{
    $product = Product::create($request->all());
    return redirect()->route('products.show', $product);
}

public function show(Product $product)
{
    return view('products.show', compact('product'));
}

public function edit(Product $product)
{
    return view('products.edit', compact('product'));
}

public function update(Request $request, Product $product)
{
    $product->update($request->all());
    return redirect()->route('products.show', $product->id);
}

public function destroy(Product $product)
{
    $product->delete();
    return redirect()->route('products.index');
}
```

- `compact('products')` envia a variável para a view.
- `Product::create()` e `$product->update()` usam os campos liberados no `#[Fillable]`.
- Depois de salvar, atualizar ou excluir, `redirect()->route(...)` leva o usuário para outra página.

#### Passo 8 — Criar as views Blade

As views ficam em `resources/views/products/`. Cada uma corresponde a um método do controller:

| View | Usada por | O que faz |
| --- | --- | --- |
| `index.blade.php` | `index` | Lista os produtos com `@foreach`, com links para editar, ver detalhes e um botão de excluir |
| `create.blade.php` | `create` | Formulário que envia um `POST` para `products.store` |
| `show.blade.php` | `show` | Exibe os dados de um produto |
| `edit.blade.php` | `edit` | Formulário já preenchido que envia para `products.update` |

Pontos importantes dos formulários:

- `@csrf` é obrigatório em todo formulário `POST`; ele gera o token de proteção contra CSRF.
- O HTML só aceita `GET` e `POST`, então a edição e a exclusão usam `@method('PUT')` e `@method('DELETE')`.
- O atributo `name` de cada `<input>` precisa ter o mesmo nome da coluna, pois `$request->all()` usa esses nomes.
- `route('products.update', $product)` gera a URL da rota a partir do nome dela.

```blade
<form method="POST" action="{{ route('products.update', $product) }}">
    @csrf
    @method('PUT')

    Name:
    <input name="name" value="{{ $product->name }}"> <br>
    ...
    <button type="submit">Editar Produto</button>
</form>
```

```blade
<form method="POST" action="{{ route('products.destroy', $product) }}">
    @csrf
    @method('DELETE')
    <button type="submit">Excluir</button>
</form>
```

#### Passo 9 — Testar a aplicação

```bash
php artisan serve
```

Acesse `http://localhost:8000/products/create` para cadastrar um produto e `http://localhost:8000/products` para ver a lista e testar a edição, os detalhes e a exclusão.

#### Resumo do fluxo MVC

1. O navegador acessa uma **rota** (`/products`).
2. A rota chama um método do **Controller** (`ProductController@index`).
3. O Controller usa o **Model** (`Product`) para consultar ou alterar o banco.
4. O Controller devolve uma **View** Blade com os dados, ou redireciona para outra rota.

## Comandos Artisan utilizados

```bash
# Criar um projeto
composer create-project laravel/laravel nome-do-projeto

# Migrations
php artisan make:migration create_patients_table
php artisan make:migration add_isbn_to_books_table
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh

# Models e Controllers
php artisan make:model Book -m
php artisan make:controller ProductController --resource

# Rotas e console interativo
php artisan route:list
php artisan tinker

# Servidor de desenvolvimento
php artisan serve
```

## Como executar um projeto

```bash
cd revisao-prova-webII        # ou outra pasta de atividade
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

A aplicação fica disponível em `http://localhost:8000` (no projeto de revisão, o CRUD está em `/products`).

## Autor

**Victor Alves Siqueira** — atividades desenvolvidas na disciplina **Desenvolvimento para Web II**.

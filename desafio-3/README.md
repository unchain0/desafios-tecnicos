# Task Manager — Desafio Técnico 3

Mini CRUD de tarefas desenvolvido com **Laravel**, **Blade** e **TailwindCSS**.

## Requisitos

- PHP 8.2+
- Composer
- Extensão PDO SQLite (`php-sqlite3`)

## Instalação

```bash
# 1. Instalar dependências
composer install

# 2. Copiar arquivo de configuração
cp .env.example .env

# 3. Gerar chave da aplicação
php artisan key:generate

# 4. Criar banco SQLite (se não existir)
touch database/database.sqlite

# 5. Executar migrations
php artisan migrate

# 6. (Opcional) Popular com dados de exemplo
php artisan db:seed
```

## Executando a Aplicação

```bash
php artisan serve
```

Acesse: **<http://localhost:8000>**

## Estrutura do Projeto

```txt
app/
├── Http/
│   ├── Controllers/
│   │   └── TaskController.php
│   └── Requests/
│       └── StoreTaskRequest.php
├── Models/
│   └── Task.php
├── Services/
│   └── TaskService.php
database/
├── migrations/
│   └── 2024_01_01_000000_create_tasks_table.php
├── seeders/
│   └── TaskSeeder.php
resources/views/
├── components/
│   ├── alert.blade.php
│   ├── button.blade.php
│   ├── card.blade.php
│   └── input.blade.php
├── layouts/
│   └── app.blade.php
├── tasks/
│   └── index.blade.php
routes/
└── web.php
tests/
├── Feature/
│   └── TaskTest.php
├── Unit/
│   └── TaskServiceTest.php
```

## Rotas Disponíveis

| Método | Rota                 | Descrição              |
| ------ | -------------------- | ---------------------- |
| GET    | `/tasks`             | Listar todas as tasks  |
| POST   | `/tasks`             | Criar nova task        |
| PATCH  | `/tasks/{id}/toggle` | Alternar status (done) |
| DELETE | `/tasks/{id}`        | Excluir task           |

## Testando via Navegador

1. Acesse `http://localhost:8000`
2. Digite o título da task e clique em "Adicionar"
3. Clique em "Concluir" para marcar como feita
4. Clique em "Reabrir" para desmarcar
5. Clique em "Excluir" para remover a task

## Funcionalidades

- **Criar task**: Formulário com validação (título obrigatório, máx. 255 caracteres)
- **Listar tasks**: Exibe todas as tasks ordenadas por data de criação
- **Toggle status**: Alterna entre pendente e concluída
- **Excluir task**: Remoção instantânea
- **Ações AJAX**: Operações instantâneas sem reload de página (Alpine.js)
- **Feedback visual**: Tasks concluídas aparecem riscadas
- **Mensagens de sucesso**: Feedback visual nas operações
- **Design responsivo**: Interface adaptada para mobile e desktop

## Testes Automatizados

```bash
php artisan test
```

## Arquitetura

O projeto segue boas práticas de arquitetura Laravel:

- **Skinny Controllers**: Controllers apenas recebem request e delegam para Services
- **Service Layer**: Lógica de negócio isolada em `App\Services\TaskService`
- **Form Requests**: Validação e sanitização em classes dedicadas
- **Blade Components**: UI componentizada e reutilizável
- **AJAX com Alpine.js**: Ações instantâneas sem reload de página

## Qualidade de Código

```bash
vendor/bin/pint
vendor/bin/pint --test
php artisan test
```

## CI/CD

O projeto inclui GitHub Actions (`.github/workflows/ci.yml`) que executa:

- Instalação de dependências
- Migrations
- Laravel Pint (code style)
- Testes automatizados

## Tecnologias

- **Laravel 12** - Framework PHP
- **Blade Components** - UI componentizada
- **Alpine.js** - Reatividade no frontend
- **TailwindCSS** - Framework CSS (via CDN)
- **SQLite** - Banco de dados
- **PHPUnit** - Testes automatizados
- **Laravel Pint** - Code style
- **GitHub Actions** - CI/CD

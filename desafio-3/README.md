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
├── Http/Controllers/
│   └── TaskController.php
├── Models/
│   └── Task.php
database/
├── migrations/
│   └── 2024_01_01_000000_create_tasks_table.php
resources/views/
├── layouts/
│   └── app.blade.php
├── tasks/
│   └── index.blade.php
routes/
└── web.php
```

## Rotas Disponíveis

| Método | Rota                 | Descrição              |
| ------ | -------------------- | ---------------------- |
| GET    | `/tasks`             | Listar todas as tasks  |
| POST   | `/tasks`             | Criar nova task        |
| PATCH  | `/tasks/{id}/toggle` | Alternar status (done) |
| DELETE | `/tasks/{id}`        | Excluir task           |

## Testando via cURL

### Listar tasks

```bash
curl http://localhost:8000/tasks
```

### Criar task

```bash
curl -X POST http://localhost:8000/tasks \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "title=Minha nova task" \
  -d "_token=$(curl -s http://localhost:8000/tasks | grep -oP 'name="_token" value="\K[^"]+')"
```

### Alternar status

```bash
curl -X PATCH http://localhost:8000/tasks/1/toggle \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -d "_token=$(curl -s http://localhost:8000/tasks | grep -oP 'name="_token" value="\K[^"]+')"
```

> **Nota:** Para testes mais simples, use o navegador ou Postman.

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
- **Excluir task**: Remoção com confirmação
- **Feedback visual**: Tasks concluídas aparecem riscadas
- **Mensagens flash**: Feedback de sucesso nas operações
- **Design responsivo**: Interface adaptada para mobile e desktop

## Testes Automatizados

```bash
php artisan test
```

Os testes cobrem:

- Listagem de tasks
- Criação com validação
- Toggle de status
- Exclusão de tasks
- Redirecionamento da raiz

## Tecnologias

- **Laravel 12** - Framework PHP
- **Blade** - Template engine
- **TailwindCSS** - Framework CSS (via CDN)
- **SQLite** - Banco de dados
- **PHPUnit** - Testes automatizados

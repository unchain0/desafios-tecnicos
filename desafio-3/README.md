# Desafio 3 - Task Manager

Mini CRUD de tarefas desenvolvido com **Laravel**, **Livewire** e **TailwindCSS**.

## Requisitos

- PHP 8.2+
- Composer
- Extensão PDO SQLite (`php-sqlite3`)

## Instalação

```bash
cd desafio-3

composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate
php artisan db:seed  # opcional

php artisan serve
```

Acesse: **<http://localhost:8000>**

## Estrutura do Projeto

```txt
app/
├── Livewire/
│   └── TaskManager.php
├── Models/
│   └── Task.php
├── Services/
│   └── TaskService.php
resources/views/
├── components/
│   ├── layouts/
│   │   └── app.blade.php
│   ├── task/
│   │   ├── form.blade.php
│   │   ├── item.blade.php
│   │   └── list.blade.php
│   ├── confirm-modal.blade.php
│   ├── empty-state.blade.php
│   ├── page-header.blade.php
│   ├── toast.blade.php
│   └── validation-error.blade.php
├── livewire/
│   └── task-manager.blade.php
routes/
└── web.php
tests/
├── Feature/
│   └── TaskTest.php
├── Unit/
│   └── TaskServiceTest.php
```

## Funcionalidades

- **Criar task**: Validação em tempo real (título obrigatório, máx. 255 caracteres)
- **Listar tasks**: Ordenadas por data de criação (mais recentes primeiro)
- **Toggle status**: Alterna entre pendente ⬜ e concluída ✅
- **Excluir task**: Modal de confirmação personalizado
- **Reatividade**: Operações instantâneas sem reload (Livewire)
- **Feedback visual**: Toast de sucesso com auto-dismiss
- **Design responsivo**: Interface adaptada para mobile e desktop

## Como Usar

1. Acesse `http://localhost:8000`
2. Digite o título e clique em **Adicionar**
3. Clique em **Concluir** para marcar como feita
4. Clique em **Reabrir** para desmarcar
5. Clique em **Excluir** → confirme no modal

## Arquitetura

- **Livewire**: Componente full-stack sem JavaScript manual
- **Service Layer**: Lógica de negócio em `TaskService`
- **Blade Components**: UI modular e reutilizável
- **Alpine.js**: Interações locais (modal, toast)

## Qualidade de Código

```bash
# Análise estática (PHPStan level 5)
composer phpstan

# Code style (Laravel Pint)
vendor/bin/pint

# Testes
composer test
```

## CI/CD

GitHub Actions (`.github/workflows/ci.yml`):

- Laravel Pint (code style)
- PHPStan (análise estática)
- Testes automatizados

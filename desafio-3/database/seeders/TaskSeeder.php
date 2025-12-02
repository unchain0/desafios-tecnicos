<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            ['title' => 'Configurar ambiente de desenvolvimento', 'done' => true],
            ['title' => 'Criar migration e model Task', 'done' => true],
            ['title' => 'Implementar controller com CRUD', 'done' => true],
            ['title' => 'Desenvolver interface com TailwindCSS', 'done' => false],
            ['title' => 'Escrever testes automatizados', 'done' => false],
            ['title' => 'Documentar projeto no README', 'done' => false],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}

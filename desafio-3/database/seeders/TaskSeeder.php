<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [
            ['title' => 'Documentar projeto no README', 'done' => true],
            ['title' => 'Adicionar autenticação e vincular tasks ao usuário logado', 'done' => false],
            ['title' => 'Implementar filtros e busca de tasks por status e título', 'done' => false],
            ['title' => 'Adicionar prioridades e datas de vencimento às tasks', 'done' => false],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}

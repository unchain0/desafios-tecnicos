<?php

use App\Livewire\TaskManager;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('tasks.index'));

Route::get('/tasks', TaskManager::class)->name('tasks.index');

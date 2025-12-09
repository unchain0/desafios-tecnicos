<div>
    <x-page-header icon="📋" title="Task Manager" subtitle="Gerencie suas tarefas de forma simples" />

    <x-toast :message="$message" />
    <x-validation-error field="title" />

    <x-task.form />
    <x-task.list :tasks="$tasks" />

    <x-confirm-modal title="Excluir Task"
        message="Tem certeza que deseja excluir esta task? Esta ação não pode ser desfeita." confirmText="Excluir"
        cancelText="Cancelar" />

    <div class="mt-8 text-center text-gray-400 text-sm">
        Laravel Task Manager — Desafio Técnico
    </div>
</div>

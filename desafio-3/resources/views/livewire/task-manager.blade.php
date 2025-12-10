<div class="space-y-6">
    <x-page-header
        icon="📋"
        title="Task Manager"
        subtitle="Gerencie suas tarefas de forma simples"
    />

    <x-toast :message="$message" />
    <x-validation-error field="title" />

    <x-task.form />
    <x-task.list :tasks="$tasks" />

    <x-confirm-modal
        title="Excluir Task"
        message="Tem certeza que deseja excluir esta task? Esta ação não pode ser desfeita."
        confirmText="Excluir"
        cancelText="Cancelar"
    />

    <footer class="mt-8 text-center text-gray-400 text-sm pb-4">
        Laravel Task Manager — Desafio Técnico
    </footer>
</div>

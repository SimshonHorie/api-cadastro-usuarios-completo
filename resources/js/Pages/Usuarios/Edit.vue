<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
    usuario: Object
});

const form = useForm({
    nome: props.usuario.nome,
    email: props.usuario.email,
    role: props.usuario.role,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.put(route('usuarios.update', props.usuario.id), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Editar Usuário" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Usuário: {{ usuario.nome }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="max-w-md mx-auto">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nome</label>
                            <input v-model="form.nome" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                            <div v-if="form.errors.nome" class="text-red-600 text-xs mt-1">{{ form.errors.nome }}</div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">E-mail</label>
                            <input v-model="form.email" type="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                            <div v-if="form.errors.email" class="text-red-600 text-xs mt-1">{{ form.errors.email }}</div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Cargo</label>
                            <select v-model="form.role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="user">Usuário Comum</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>

                        <div class="p-4 mb-4 bg-blue-50 border-l-4 border-blue-400 text-blue-700 text-sm">
                            Deixe a senha em branco caso não deseje alterá-la.
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nova Senha</label>
                            <input v-model="form.password" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                            <div v-if="form.errors.password" class="text-red-600 text-xs mt-1">{{ form.errors.password }}</div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Confirmar Nova Senha</label>
                            <input v-model="form.password_confirmation" type="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" />
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <Link :href="route('usuarios.index')" class="text-sm text-gray-600 underline">Cancelar</Link>
                            <button type="submit" :disabled="form.processing" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 disabled:opacity-50">
                                Atualizar Usuário
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
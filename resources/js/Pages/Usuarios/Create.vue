<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    nome: '',
    email: '',
});

const submit = () => {
    form.post(route('usuarios.store'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 flex items-center justify-center">
        <div class="bg-white p-8 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6 text-gray-800 text-center">Cadastro de Usuário</h2>
            
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-gray-700">Nome</label>
                    <input v-model="form.nome" type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500" required>
                    <div v-if="form.errors.nome" class="text-red-500 text-sm mt-1">{{ form.errors.nome }}</div>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700">E-mail</label>
                    <input v-model="form.email" type="email" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500" required>
                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
                </div>

                <button :disabled="form.processing" type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition">
                    {{ form.processing ? 'Salvando...' : 'Cadastrar' }}
                </button>
            </form>

            <div v-if="form.recentlySuccessful" class="mt-4 p-2 bg-green-100 text-green-700 rounded text-center">
                Usuário cadastrado com sucesso! (Verifique o log do RabbitMQ)
            </div>
        </div>
    </div>
</template>
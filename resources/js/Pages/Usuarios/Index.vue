<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    usuarios: Array
});

const page = usePage();
const authUser = computed(() => page.props.auth.user);

function deletarUsuario(id) {
    if (confirm('Tem certeza que deseja excluir?')) {
        router.delete(route('usuarios.destroy', id), {
            onSuccess: () => {
                console.log('Deletado com sucesso');
            },
            onError: (errors) => {
                console.error('Erro ao deletar:', errors);
            },
        });
    }
}
</script>

<template>
    <Head title="Gestão de Usuários" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Gestão de Usuários</h2>
                
                <Link 
                    v-if="authUser?.role === 'admin'"
                    :href="route('usuarios.create')" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow-sm text-sm transition"
                >
                    + Novo Usuário
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <div v-if="$page.props.flash.message" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">
                    <span class="block sm:inline">{{ $page.props.flash.message }}</span>
                </div>

                <div v-if="$page.props.flash.error" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm">
                    <span class="block sm:inline">{{ $page.props.flash.error }}</span>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="p-3 text-sm font-semibold text-gray-600 uppercase">Nome</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 uppercase">E-mail</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 uppercase">Cargo</th>
                                <th class="p-3 text-sm font-semibold text-gray-600 uppercase text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="props.usuarios && props.usuarios.length > 0">
                                <tr v-for="user in props.usuarios" :key="user.id" class="border-b hover:bg-gray-50 transition">
                                    <td class="p-3 text-gray-700">{{ user.nome || user.name }}</td>
                                    <td class="p-3 text-gray-700">{{ user.email }}</td>
                                    <td class="p-3 text-gray-700">
                                        <span :class="user.role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700'" class="px-2 py-1 rounded text-xs font-bold uppercase">
                                            {{ user.role }}
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex items-center justify-center space-x-4">
                                            <template v-if="authUser?.role === 'admin'">
                                                
                                                <Link 
                                                    :href="route('usuarios.edit', user.id)" 
                                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium transition"
                                                >
                                                    Editar
                                                </Link>

                                                <button 
                                                    v-if="user.id !== authUser?.id"
                                                    @click="deletarUsuario(user.id)" 
                                                    class="text-red-600 hover:text-red-800 text-sm font-medium transition"
                                                >
                                                    Excluir
                                                </button>
                                                <span v-else class="text-gray-400 text-xs italic">Sua Conta</span>
                                            </template>
                                            
                                            <template v-else>
                                                <span class="text-gray-400 text-xs italic">Sem Permissão</span>
                                            </template>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <tr v-else>
                                <td colspan="4" class="p-4 text-center text-gray-500">
                                    Nenhum usuário encontrado.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
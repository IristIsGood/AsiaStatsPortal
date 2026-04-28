<template>
  <div class="max-w-md mx-auto mt-16">
    <div class="bg-white rounded-xl shadow p-8">
      <h1 class="text-2xl font-bold text-gray-800 mb-2">Sign In</h1>
      <p class="text-sm text-gray-500 mb-6">Admin access for data management</p>

      <div v-if="error" class="bg-red-50 text-red-600 text-sm p-3 rounded mb-4">
        {{ error }}
      </div>

      <div class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
          <input v-model="form.email" type="email" placeholder="admin@test.com"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input v-model="form.password" type="password" placeholder="password123"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <button @click="login"
          class="w-full bg-blue-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-blue-700 transition">
          {{ loading ? 'Signing in...' : 'Sign In' }}
        </button>
      </div>

      <p class="text-xs text-gray-400 mt-4 text-center">
        Public data is accessible without login
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

const router   = useRouter();
const auth     = useAuthStore();
const form     = ref({ email: '', password: '' });
const error    = ref('');
const loading  = ref(false);

async function login() {
    loading.value = true;
    error.value   = '';
    try {
        await auth.login(form.value.email, form.value.password);
        router.push('/admin');
    } catch (e) {
        error.value = 'Invalid email or password.';
    } finally {
        loading.value = false;
    }
}
</script>
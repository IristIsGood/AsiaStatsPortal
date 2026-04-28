<template>
  <div class="min-h-screen bg-gray-50">
    <nav class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <span class="text-blue-600 font-bold text-xl">🌏 Asia Stats Portal</span>
      </div>
      <div class="flex items-center gap-6 text-sm">
        <router-link to="/"           class="text-gray-600 hover:text-blue-600">Dashboard</router-link>
        <router-link to="/indicators" class="text-gray-600 hover:text-blue-600">Indicators</router-link>
        <router-link to="/explorer"   class="text-gray-600 hover:text-blue-600">Data Explorer</router-link>

        <!-- 已登录：显示 Admin 和用户名 -->
        <template v-if="auth.isLoggedIn">
          <router-link to="/admin" class="text-purple-600 font-medium hover:text-purple-700">
            Admin
          </router-link>
          <span class="text-xs text-gray-400">{{ auth.user?.name }}</span>
          <button @click="logout" class="text-red-500 hover:text-red-600 text-xs">Logout</button>
        </template>

        <!-- 未登录：显示 Login -->
        <template v-else>
          <router-link to="/login" class="text-gray-600 hover:text-blue-600">Login</router-link>
        </template>
      </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-8">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { useAuthStore } from './stores/auth';
import { useRouter } from 'vue-router';

const auth   = useAuthStore();
const router = useRouter();

function logout() {
    auth.logout();
    router.push('/login');
}
</script>
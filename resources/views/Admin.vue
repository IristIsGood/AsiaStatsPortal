<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-800">Admin Panel</h1>
        <p class="text-sm text-gray-500 mt-1">
          Logged in as <span class="font-medium text-blue-600">{{ auth.user?.name }}</span>
          <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
            {{ auth.user?.role }}
          </span>
        </p>
      </div>
      <button @click="logout"
        class="px-4 py-2 text-sm bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
        Logout
      </button>
    </div>

    <!-- Add Indicator Form -->
    <div class="bg-white rounded-xl shadow p-6 mb-6">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">Add New Indicator</h2>

      <div v-if="success" class="bg-green-50 text-green-600 text-sm p-3 rounded mb-4">
        {{ success }}
      </div>
      <div v-if="error" class="bg-red-50 text-red-600 text-sm p-3 rounded mb-4">
        {{ error }}
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-xs font-medium text-gray-500 mb-1">Code</label>
          <input v-model="form.code" placeholder="e.g. GDP_GR"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-500 mb-1">Name</label>
          <input v-model="form.name_en" placeholder="e.g. GDP Growth Rate"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-500 mb-1">Unit</label>
          <input v-model="form.unit" placeholder="e.g. percent"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-500 mb-1">Category</label>
          <select v-model="form.category"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Select category</option>
            <option>Economy</option>
            <option>Demographics</option>
            <option>Labour</option>
            <option>Social</option>
            <option>Health</option>
            <option>Environment</option>
            <option>Technology</option>
          </select>
        </div>
      </div>

      <button @click="addIndicator"
        class="mt-4 px-6 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
        {{ saving ? 'Saving...' : 'Add Indicator' }}
      </button>
    </div>

    <!-- Add Data Point Form -->
<div class="bg-white rounded-xl shadow p-6 mb-6">
  <h2 class="text-lg font-semibold text-gray-700 mb-4">Submit Data Point for Validation</h2>

  <div class="grid grid-cols-3 gap-4">
    <div>
      <label class="block text-xs font-medium text-gray-500 mb-1">Indicator</label>
      <select v-model="dpForm.indicator_id"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Select indicator</option>
        <option v-for="ind in indicators" :key="ind.id" :value="ind.id">
          {{ ind.code }} — {{ ind.name_en }}
        </option>
      </select>
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-500 mb-1">Country</label>
      <select v-model="dpForm.region_id"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Select country</option>
        <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
      </select>
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-500 mb-1">Year</label>
      <select v-model="dpForm.year"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">Select year</option>
        <option v-for="y in [2024,2025,2026]" :key="y" :value="y">{{ y }}</option>
      </select>
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-500 mb-1">Value</label>
      <input v-model="dpForm.value" type="number" placeholder="e.g. 480.5"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
    </div>
    <div>
      <label class="block text-xs font-medium text-gray-500 mb-1">Source</label>
      <input v-model="dpForm.source" placeholder="e.g. World Bank"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
    </div>
  </div>

  <button @click="addDataPoint"
    class="mt-4 px-6 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">
    {{ savingDp ? 'Submitting...' : 'Submit for Validation' }}
  </button>
</div>

    <!-- Pending Validations -->
    <div class="bg-white rounded-xl shadow p-6">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">
        Pending Validations
        <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
          {{ pending.length }}
        </span>
      </h2>

      <div v-if="pending.length === 0" class="text-sm text-gray-400 text-center py-8">
        No pending validations
      </div>

      <div v-for="dp in pending" :key="dp.id"
        class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
        <div>
          <p class="text-sm font-medium text-gray-800">
            Data Point #{{ dp.id }} — {{ dp.value }} ({{ dp.year }})
          </p>
          <p class="text-xs text-gray-500">Source: {{ dp.source }}</p>
        </div>
        <div class="flex gap-2">
          <button @click="approve(dp.id)"
            class="px-3 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700">
            Approve
          </button>
          <button @click="reject(dp.id)"
            class="px-3 py-1 text-xs bg-red-500 text-white rounded hover:bg-red-600">
            Reject
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import axios from 'axios';

const router  = useRouter();
const auth    = useAuthStore();
const pending    = ref([]);
const indicators = ref([]);
const regions    = ref([]);
const success = ref('');
const error   = ref('');
const saving  = ref(false);
const savingDp = ref(false);

const form = ref({
    code: '', name_en: '', unit: '', category: ''
});

const dpForm = ref({
    indicator_id: '',
    region_id: '',
    year: '',
    value: '',
    source: '',
});

onMounted(async () => {
    const [pendingRes, indRes, regRes] = await Promise.all([
        axios.get('/api/v1/validations/pending'),
        axios.get('/api/v1/indicators'),
        axios.get('/api/v1/regions'),
    ]);
    pending.value    = pendingRes.data;
    indicators.value = indRes.data;
    regions.value    = regRes.data.filter(r => r.level === 'national');
});

async function addIndicator() {
    saving.value  = true;
    success.value = '';
    error.value   = '';
    try {
        await axios.post('/api/v1/indicators', form.value);
        success.value = 'Indicator added successfully!';
        form.value = { code: '', name_en: '', unit: '', category: '' };
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to add indicator.';
    } finally {
        saving.value = false;
    }
}

async function addDataPoint() {
    savingDp.value = true;
    success.value  = '';
    error.value    = '';
    try {
        const res = await axios.post('/api/v1/data-points', {
            ...dpForm.value,
            status: 'pending',
        });
        success.value = 'Data point submitted for validation!';
        pending.value.push(res.data);
        dpForm.value = { indicator_id: '', region_id: '', year: '', value: '', source: '' };
    } catch (e) {
        error.value = e.response?.data?.message || 'Failed to submit data point.';
    } finally {
        savingDp.value = false;
    }
}

async function approve(id) {
    await axios.post(`/api/v1/validations/${id}/approve`);
    pending.value = pending.value.filter(dp => dp.id !== id);
    success.value = 'Data point approved!';
}

async function reject(id) {
    await axios.post(`/api/v1/validations/${id}/reject`, { reason: 'Rejected by admin' });
    pending.value = pending.value.filter(dp => dp.id !== id);
    success.value = 'Data point rejected.';
}

function logout() {
    auth.logout();
    router.push('/login');
}
</script>
<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Data Explorer</h1>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow p-4 mb-6 grid grid-cols-3 gap-4">
      <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Indicator</label>
        <select v-model="filters.indicator_id"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">All Indicators</option>
          <option v-for="ind in indicators" :key="ind.id" :value="ind.id">
            {{ ind.code }} — {{ ind.name_en }}
          </option>
        </select>
      </div>
      <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Country</label>
        <select v-model="filters.region_id"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">All Countries</option>
          <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
        </select>
      </div>
      <div>
        <label class="block text-xs font-medium text-gray-500 mb-1">Year</label>
        <select v-model="filters.year"
          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">All Years</option>
          <option v-for="y in [2020,2021,2022,2023]" :key="y" :value="y">{{ y }}</option>
        </select>
      </div>
    </div>

    <!-- Export buttons -->
    <div class="flex gap-2 mb-4">
      <a :href="exportUrl('csv')" class="px-3 py-1.5 text-sm bg-green-600 text-white rounded hover:bg-green-700">
        Export CSV
      </a>
      <a :href="exportUrl('xml')" class="px-3 py-1.5 text-sm bg-orange-500 text-white rounded hover:bg-orange-600">
        Export XML
      </a>
      <a :href="exportUrl('json')" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
        Export JSON
      </a>
    </div>

    <!-- Results table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="text-left px-4 py-3 text-gray-600 font-medium">Country</th>
            <th class="text-left px-4 py-3 text-gray-600 font-medium">Indicator</th>
            <th class="text-left px-4 py-3 text-gray-600 font-medium">Year</th>
            <th class="text-right px-4 py-3 text-gray-600 font-medium">Value</th>
            <th class="text-left px-4 py-3 text-gray-600 font-medium">Source</th>
            <th class="text-left px-4 py-3 text-gray-600 font-medium">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="dp in filtered" :key="dp.id"
            class="border-b border-gray-100 hover:bg-blue-50 transition">
            <td class="px-4 py-3 text-gray-800">{{ regionName(dp.region_id) }}</td>
            <td class="px-4 py-3 font-mono text-blue-600">{{ indicatorCode(dp.indicator_id) }}</td>
            <td class="px-4 py-3 text-gray-600">{{ dp.year }}</td>
            <td class="px-4 py-3 text-right font-medium text-gray-800">{{ Number(dp.value).toLocaleString() }}</td>
            <td class="px-4 py-3 text-gray-500">{{ dp.source }}</td>
            <td class="px-4 py-3">
              <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-700">
                {{ dp.status }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const indicators = ref([]);
const regions    = ref([]);
const dataPoints = ref([]);

const filters = ref({
  indicator_id: '',
  region_id: '',
  year: '',
});

onMounted(async () => {
  const [indRes, regRes, dpRes] = await Promise.all([
    axios.get('/api/v1/indicators'),
    axios.get('/api/v1/regions'),
    axios.get('/api/v1/data-points'),
  ]);
  indicators.value = indRes.data;
  regions.value    = regRes.data.filter(r => r.level === 'national');
  dataPoints.value = dpRes.data;
});

const filtered = computed(() =>
  dataPoints.value.filter(dp => {
    if (filters.value.indicator_id && dp.indicator_id !== filters.value.indicator_id) return false;
    if (filters.value.region_id    && dp.region_id    !== filters.value.region_id)    return false;
    if (filters.value.year         && dp.year         !== filters.value.year)          return false;
    return true;
  })
);

function regionName(id) {
  return regions.value.find(r => r.id === id)?.name ?? id;
}

function indicatorCode(id) {
  return indicators.value.find(i => i.id === id)?.code ?? id;
}

function exportUrl(format) {
  const params = new URLSearchParams({ format });
  if (filters.value.indicator_id) params.append('indicator_id', filters.value.indicator_id);
  if (filters.value.region_id)    params.append('region_id',    filters.value.region_id);
  if (filters.value.year)         params.append('year',         filters.value.year);
  return `/api/v1/data-points?${params.toString()}`;
}
</script>
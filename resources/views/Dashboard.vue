<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

    <!-- Stats cards -->
    <div class="grid grid-cols-3 gap-4 mb-8">
      <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500">Total Indicators</p>
        <p class="text-3xl font-bold text-blue-600 mt-1">{{ indicators.length }}</p>
      </div>
      <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500">Countries Covered</p>
        <p class="text-3xl font-bold text-green-600 mt-1">{{ regions.length }}</p>
      </div>
      <div class="bg-white rounded-xl shadow p-5">
        <p class="text-sm text-gray-500">Data Points</p>
        <p class="text-3xl font-bold text-purple-600 mt-1">{{ dataPoints.length }}</p>
      </div>
    </div>

    <!-- Chart -->
    <div class="bg-white rounded-xl shadow p-6 mb-8">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">GDP by Country (2023, USD billion)</h2>
      <Bar v-if="chartData" :data="chartData" :options="chartOptions" />
    </div>

    <!-- Recent indicators table -->
    <div class="bg-white rounded-xl shadow p-6">
      <h2 class="text-lg font-semibold text-gray-700 mb-4">All Indicators</h2>
      <table class="w-full text-sm">
        <thead class="border-b border-gray-200">
          <tr>
            <th class="text-left py-2 text-gray-500 font-medium">Code</th>
            <th class="text-left py-2 text-gray-500 font-medium">Name</th>
            <th class="text-left py-2 text-gray-500 font-medium">Category</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ind in indicators" :key="ind.id" class="border-b border-gray-50">
            <td class="py-2 font-mono text-blue-600">{{ ind.code }}</td>
            <td class="py-2 text-gray-700">{{ ind.name_en }}</td>
            <td class="py-2 text-gray-500">{{ ind.category }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Bar } from 'vue-chartjs';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  BarElement,
  Title,
  Tooltip,
  Legend,
} from 'chart.js';
import axios from 'axios';

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const indicators = ref([]);
const regions    = ref([]);
const dataPoints = ref([]);

onMounted(async () => {
  const [indRes, regRes, dpRes] = await Promise.all([
    axios.get('/api/v1/indicators'),
    axios.get('/api/v1/regions'),
    axios.get('/api/v1/data-points'),
  ]);
  indicators.value = indRes.data;
  regions.value = Array.isArray(regRes.data) 
  ? regRes.data.filter(r => r.level === 'national')
  : (regRes.data.data ?? []).filter(r => r.level === 'national');
  dataPoints.value = dpRes.data;
});

const chartData = computed(() => {
  if (!dataPoints.value.length || !regions.value.length) return null;

  const gdp2023 = dataPoints.value.filter(dp =>
    dp.year === 2023 && dp.indicator_id === 1
  );

  const labels = gdp2023.map(dp => {
    const region = regions.value.find(r => r.id === dp.region_id);
    return region ? region.name : dp.region_id;
  });

  return {
    labels,
    datasets: [{
      label: 'GDP 2023 (USD billion)',
      data: gdp2023.map(dp => dp.value),
      backgroundColor: [
        '#3B82F6','#10B981','#F59E0B','#EF4444',
        '#8B5CF6','#06B6D4','#F97316','#84CC16',
      ],
      borderRadius: 6,
    }],
  };
});

const chartOptions = {
  responsive: true,
  plugins: { legend: { display: false } },
  scales: { y: { beginAtZero: true } },
};
</script>
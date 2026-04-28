<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-800">Indicators</h1>
      <div class="flex gap-2">
        <a :href="exportUrl('csv')" class="px-3 py-1.5 text-sm bg-green-600 text-white rounded hover:bg-green-700">
          Export CSV
        </a>
        <a :href="exportUrl('xml')" class="px-3 py-1.5 text-sm bg-orange-500 text-white rounded hover:bg-orange-600">
          Export XML
        </a>
      </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-xl shadow p-4 mb-6 flex gap-4">
      <input v-model="search" placeholder="Search indicators..."
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm flex-1 focus:outline-none focus:ring-2 focus:ring-blue-500" />
      <select v-model="categoryFilter"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="">All Categories</option>
        <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
      </select>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
      <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
          <tr>
            <th class="text-left px-4 py-3 text-gray-600 font-medium">Code</th>
            <th class="text-left px-4 py-3 text-gray-600 font-medium">Name</th>
            <th class="text-left px-4 py-3 text-gray-600 font-medium">Unit</th>
            <th class="text-left px-4 py-3 text-gray-600 font-medium">Category</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="ind in filtered" :key="ind.id"
            class="border-b border-gray-100 hover:bg-blue-50 transition">
            <td class="px-4 py-3 font-mono text-blue-600 font-medium">{{ ind.code }}</td>
            <td class="px-4 py-3 text-gray-800">{{ ind.name_en }}</td>
            <td class="px-4 py-3 text-gray-500">{{ ind.unit }}</td>
            <td class="px-4 py-3">
              <span class="px-2 py-0.5 rounded-full text-xs font-medium"
                :class="categoryColor(ind.category)">
                {{ ind.category }}
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
const search = ref('');
const categoryFilter = ref('');

onMounted(async () => {
  const res = await axios.get('/api/v1/indicators');
  indicators.value = res.data;
});

const categories = computed(() =>
  [...new Set(indicators.value.map(i => i.category))]
);

const filtered = computed(() =>
  indicators.value.filter(i => {
    const matchSearch = i.name_en.toLowerCase().includes(search.value.toLowerCase()) ||
                        i.code.toLowerCase().includes(search.value.toLowerCase());
    const matchCat = !categoryFilter.value || i.category === categoryFilter.value;
    return matchSearch && matchCat;
  })
);

function exportUrl(format) {
  return `/api/v1/indicators?format=${format}`;
}

function categoryColor(cat) {
  const colors = {
    Economy:      'bg-blue-100 text-blue-700',
    Demographics: 'bg-purple-100 text-purple-700',
    Labour:       'bg-yellow-100 text-yellow-700',
    Social:       'bg-pink-100 text-pink-700',
    Health:       'bg-green-100 text-green-700',
    Environment:  'bg-teal-100 text-teal-700',
    Technology:   'bg-orange-100 text-orange-700',
  };
  return colors[cat] || 'bg-gray-100 text-gray-700';
}
</script>
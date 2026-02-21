<!--
╔══════════════════════════════════════════════════════════════╗
║  VUE COMPONENT: RedisDemo                                    ║
║  Purpose: Interactive Redis operations demo                  ║
║  Learning: AJAX calls, Vue reactivity, Redis commands        ║
╚══════════════════════════════════════════════════════════════╝

Usage in Blade:
  <redis-demo />
-->

<template>
  <div class="space-y-6">
    <!-- Redis Connection Status -->
    <div
      class="flex items-center gap-3 p-4 rounded-xl"
      :class="connected ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200'"
    >
      <div
        class="w-3 h-3 rounded-full animate-pulse"
        :class="connected ? 'bg-green-500' : 'bg-red-500'"
      ></div>
      <span :class="connected ? 'text-green-700' : 'text-red-700'" class="font-medium">
        {{ connected ? 'Redis Connected' : 'Redis Disconnected' }}
      </span>
      <span v-if="redisInfo" class="text-sm text-green-600">
        (v{{ redisInfo.redis_version }} | Memory: {{ redisInfo.used_memory }})
      </span>
      <button
        @click="checkConnection"
        class="ml-auto text-sm px-3 py-1 rounded-lg bg-white border shadow-sm hover:bg-gray-50 transition"
      >
        🔄 Check
      </button>
    </div>

    <!-- Demo Cards Grid -->
    <div class="grid md:grid-cols-2 gap-6">

      <!-- Demo 1: String Operations -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-5 py-3">
          <h3 class="text-white font-bold text-sm">📝 Demo 1: String SET/GET</h3>
          <p class="text-blue-100 text-xs mt-0.5">The most basic Redis operation</p>
        </div>
        <div class="p-5 space-y-3">
          <div>
            <label class="text-xs font-medium text-gray-500 uppercase">Key</label>
            <input v-model="stringKey" class="w-full mt-1 px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="demo:greeting" />
          </div>
          <div>
            <label class="text-xs font-medium text-gray-500 uppercase">Value</label>
            <input v-model="stringValue" class="w-full mt-1 px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500" placeholder="Hello Redis!" />
          </div>
          <button @click="demoStrings" :disabled="loading.strings" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition disabled:opacity-50">
            {{ loading.strings ? 'Running...' : '▶ Run SET & GET' }}
          </button>
          <div v-if="results.strings" class="bg-gray-50 rounded-lg p-3 text-sm">
            <pre class="text-gray-700 whitespace-pre-wrap">{{ JSON.stringify(results.strings, null, 2) }}</pre>
          </div>
        </div>
      </div>

      <!-- Demo 2: Cache Remember -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-5 py-3">
          <h3 class="text-white font-bold text-sm">⚡ Demo 2: Cache Remember</h3>
          <p class="text-purple-100 text-xs mt-0.5">Cache expensive operations</p>
        </div>
        <div class="p-5 space-y-3">
          <p class="text-sm text-gray-600">
            Click multiple times to see the speed difference between first load (DB) and cached load (Redis).
          </p>
          <button @click="demoCache" :disabled="loading.cache" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition disabled:opacity-50">
            {{ loading.cache ? 'Running...' : '▶ Run Cache Demo' }}
          </button>
          <div v-if="results.cache" class="bg-gray-50 rounded-lg p-3 text-sm">
            <div class="flex items-center gap-2 mb-2">
              <span class="text-2xl">{{ results.cache.from_cache ? '⚡' : '🐢' }}</span>
              <span class="font-bold" :class="results.cache.from_cache ? 'text-green-600' : 'text-orange-600'">
                {{ results.cache.time_ms }}ms
              </span>
            </div>
            <p class="text-gray-600">{{ results.cache.explanation }}</p>
          </div>
        </div>
      </div>

      <!-- Demo 3: Redis Lists -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-green-600 px-5 py-3">
          <h3 class="text-white font-bold text-sm">📋 Demo 3: Lists (Queue)</h3>
          <p class="text-green-100 text-xs mt-0.5">RPUSH / LPOP — like a queue</p>
        </div>
        <div class="p-5 space-y-3">
          <div>
            <label class="text-xs font-medium text-gray-500 uppercase">Value to Push</label>
            <input v-model="listValue" class="w-full mt-1 px-3 py-2 border rounded-lg text-sm focus:ring-2 focus:ring-green-500" placeholder="Task item..." />
          </div>
          <div class="flex gap-2">
            <button @click="demoList('push')" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-green-700 transition">
              ➕ Push
            </button>
            <button @click="demoList('pop')" class="flex-1 px-4 py-2 bg-orange-600 text-white rounded-lg text-sm font-medium hover:bg-orange-700 transition">
              ➖ Pop
            </button>
          </div>
          <div v-if="results.lists" class="bg-gray-50 rounded-lg p-3 text-sm">
            <p class="font-medium text-gray-700 mb-1">Queue ({{ results.lists.list_length }} items):</p>
            <div class="flex flex-wrap gap-1">
              <span v-for="(item, i) in results.lists.all_items" :key="i" class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                {{ item }}
              </span>
              <span v-if="results.lists.all_items.length === 0" class="text-gray-400 italic">Empty</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Demo 4: Hashes -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-5 py-3">
          <h3 class="text-white font-bold text-sm">🗂️ Demo 4: Hashes</h3>
          <p class="text-amber-100 text-xs mt-0.5">Store objects as hash fields</p>
        </div>
        <div class="p-5 space-y-3">
          <p class="text-sm text-gray-600">Creates a user profile hash and increments the login counter atomically.</p>
          <button @click="demoHashes" :disabled="loading.hashes" class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-medium hover:bg-amber-700 transition disabled:opacity-50">
            {{ loading.hashes ? 'Running...' : '▶ Run Hash Demo' }}
          </button>
          <div v-if="results.hashes" class="bg-gray-50 rounded-lg p-3 text-sm">
            <div v-for="(val, key) in results.hashes.profile" :key="key" class="flex justify-between py-1 border-b border-gray-200 last:border-0">
              <span class="font-medium text-gray-600">{{ key }}</span>
              <span class="text-gray-800">{{ val }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Demo 5: Counter -->
      <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden md:col-span-2">
        <div class="bg-gradient-to-r from-rose-500 to-rose-600 px-5 py-3">
          <h3 class="text-white font-bold text-sm">🔢 Demo 5: Atomic Counter</h3>
          <p class="text-rose-100 text-xs mt-0.5">Thread-safe increment — perfect for page views</p>
        </div>
        <div class="p-5">
          <div class="flex items-center gap-6">
            <button @click="demoCounter" class="px-6 py-3 bg-rose-600 text-white rounded-xl text-lg font-bold hover:bg-rose-700 transition shadow-lg shadow-rose-200">
              Click Me! +1
            </button>
            <div v-if="results.counter" class="text-center">
              <div class="text-4xl font-black text-rose-600">{{ results.counter.count }}</div>
              <div class="text-sm text-gray-500">Total Clicks</div>
            </div>
            <p class="text-sm text-gray-600 flex-1">
              Each click calls Redis INCR — atomically increments a counter. Even with thousands of concurrent requests, the count stays accurate.
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Flush All Demo Data -->
    <div class="text-center">
      <button @click="flushDemos" class="px-6 py-2 bg-gray-800 text-white rounded-lg text-sm font-medium hover:bg-gray-900 transition">
        🗑️ Reset All Demo Data
      </button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'RedisDemo',

  data() {
    return {
      connected: false,
      redisInfo: null,
      stringKey: 'demo:greeting',
      stringValue: 'Hello from Redis!',
      listValue: '',
      loading: {
        strings: false,
        cache: false,
        hashes: false,
      },
      results: {
        strings: null,
        cache: null,
        lists: null,
        hashes: null,
        counter: null,
      },
    };
  },

  mounted() {
    this.checkConnection();
  },

  methods: {
    async checkConnection() {
      try {
        const res = await axios.get('/redis-demo/info');
        this.connected = res.data.connected;
        this.redisInfo = res.data;
      } catch (e) {
        this.connected = false;
      }
    },

    async demoStrings() {
      this.loading.strings = true;
      try {
        const res = await axios.post('/redis-demo/strings', {
          key: this.stringKey,
          value: this.stringValue,
        });
        this.results.strings = res.data;
      } catch (e) {
        this.results.strings = { error: e.response?.data?.message || e.message };
      }
      this.loading.strings = false;
    },

    async demoCache() {
      this.loading.cache = true;
      try {
        const res = await axios.post('/redis-demo/cache');
        this.results.cache = res.data;
      } catch (e) {
        this.results.cache = { error: e.response?.data?.message || e.message };
      }
      this.loading.cache = false;
    },

    async demoList(action) {
      try {
        const res = await axios.post('/redis-demo/lists', {
          action,
          value: this.listValue || 'Item ' + new Date().toLocaleTimeString(),
        });
        this.results.lists = res.data;
        this.listValue = '';
      } catch (e) {
        this.results.lists = { error: e.response?.data?.message || e.message };
      }
    },

    async demoHashes() {
      this.loading.hashes = true;
      try {
        const res = await axios.post('/redis-demo/hashes');
        this.results.hashes = res.data;
      } catch (e) {
        this.results.hashes = { error: e.response?.data?.message || e.message };
      }
      this.loading.hashes = false;
    },

    async demoCounter() {
      try {
        const res = await axios.post('/redis-demo/counter');
        this.results.counter = res.data;
      } catch (e) {
        this.results.counter = { error: e.response?.data?.message || e.message };
      }
    },

    async flushDemos() {
      if (!confirm('Reset all demo data in Redis?')) return;
      try {
        await axios.post('/redis-demo/flush');
        this.results = { strings: null, cache: null, lists: null, hashes: null, counter: null };
        alert('All demo data cleared!');
      } catch (e) {
        alert('Error: ' + (e.response?.data?.message || e.message));
      }
    },
  },
};
</script>

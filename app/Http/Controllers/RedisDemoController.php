<?php

namespace App\Http\Controllers;

/**
 * ╔══════════════════════════════════════════════════════════════╗
 * ║  CONTROLLER: RedisDemoController                             ║
 * ║  Purpose: Interactive page to LEARN Redis commands           ║
 * ║  Learning: Redis strings, lists, hashes, sets, TTL, pub/sub ║
 * ╚══════════════════════════════════════════════════════════════╝
 *
 * This controller demonstrates various Redis operations
 * so you can understand how Redis works under the hood.
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class RedisDemoController extends Controller
{
    /**
     * Show the Redis demo page.
     * Route: GET /redis-demo
     */
    public function index()
    {
        return view('redis.demo');
    }

    /**
     * Demo 1: Basic String Operations
     * Redis SET and GET — the most fundamental operations.
     *
     * Route: POST /redis-demo/strings
     */
    public function strings(Request $request)
    {
        $key   = $request->input('key', 'demo:greeting');
        $value = $request->input('value', 'Hello from Redis!');
        $ttl   = $request->input('ttl', 60); // Time to live in seconds

        // SET: Store a value in Redis
        Redis::setex($key, $ttl, $value);

        // GET: Retrieve the value
        $stored = Redis::get($key);

        // TTL: Check remaining time to live
        $remaining = Redis::ttl($key);

        return response()->json([
            'operation'   => 'String SET/GET',
            'key'         => $key,
            'value_set'   => $value,
            'value_got'   => $stored,
            'ttl_seconds' => $remaining,
            'explanation' => "We stored '{$value}' with key '{$key}' that expires in {$ttl} seconds.",
        ]);
    }

    /**
     * Demo 2: Cache Remember Pattern
     * The most common Redis pattern in Laravel applications.
     *
     * Route: POST /redis-demo/cache
     */
    public function cacheDemo()
    {
        // Simulate an expensive operation (like a complex DB query)
        $startTime = microtime(true);

        $data = Cache::remember('demo:expensive_query', 30, function () {
            // This simulates a slow database query
            // In real life, this might be an API call or complex aggregation
            usleep(100000); // Sleep 100ms to simulate slow query

            return [
                'users_count'   => random_int(1000, 9999),
                'revenue'       => '$' . number_format(random_int(10000, 99999), 2),
                'generated_at'  => now()->toDateTimeString(),
            ];
        });

        $elapsed = round((microtime(true) - $startTime) * 1000, 2);

        return response()->json([
            'operation'   => 'Cache Remember',
            'data'        => $data,
            'time_ms'     => $elapsed,
            'from_cache'  => $elapsed < 50, // If fast, it was from cache
            'explanation' => $elapsed < 50
                ? "⚡ Data served from Redis cache in {$elapsed}ms (no DB hit!)"
                : "🐢 First request: queried DB in {$elapsed}ms, now cached for 30 seconds.",
        ]);
    }

    /**
     * Demo 3: Redis Lists (Queue-like structure)
     * LPUSH, RPUSH, LPOP, LRANGE
     *
     * Route: POST /redis-demo/lists
     */
    public function lists(Request $request)
    {
        $action = $request->input('action', 'push');
        $value  = $request->input('value', 'Task ' . now()->format('H:i:s'));
        $listKey = 'demo:task_queue';

        $result = [];

        if ($action === 'push') {
            // RPUSH: Add to the end of the list (like a queue)
            Redis::rpush($listKey, $value);
            $result['pushed'] = $value;
        } elseif ($action === 'pop') {
            // LPOP: Remove from the front (FIFO - First In, First Out)
            $popped = Redis::lpop($listKey);
            $result['popped'] = $popped ?? 'List is empty!';
        }

        // LRANGE: Get all items in the list
        $allItems = Redis::lrange($listKey, 0, -1);
        $length   = Redis::llen($listKey);

        return response()->json([
            'operation'   => "List {$action}",
            'result'      => $result,
            'all_items'   => $allItems,
            'list_length' => $length,
            'explanation' => "Redis Lists work like queues. RPUSH adds to end, LPOP removes from front.",
        ]);
    }

    /**
     * Demo 4: Redis Hashes (like PHP associative arrays)
     * HSET, HGET, HGETALL
     *
     * Route: POST /redis-demo/hashes
     */
    public function hashes()
    {
        $hashKey = 'demo:user_profile';

        // HMSET: Set multiple hash fields at once
        Redis::hmset($hashKey, [
            'name'       => 'John Doe',
            'email'      => 'john@example.com',
            'login_count' => 42,
            'last_seen'  => now()->toDateTimeString(),
        ]);

        // HINCRBY: Atomically increment a hash field (thread-safe!)
        Redis::hincrby($hashKey, 'login_count', 1);

        // HGETALL: Get the entire hash
        $profile = Redis::hgetall($hashKey);

        // HGET: Get a single field
        $name = Redis::hget($hashKey, 'name');

        // Set 5-minute expiry
        Redis::expire($hashKey, 300);

        return response()->json([
            'operation'   => 'Hash Operations',
            'profile'     => $profile,
            'single_get'  => "HGET name = {$name}",
            'explanation' => "Hashes are perfect for storing objects. Each field can be accessed/updated independently.",
        ]);
    }

    /**
     * Demo 5: Redis Counter (Atomic Increment)
     * Perfect for page views, likes, rate limiting
     *
     * Route: POST /redis-demo/counter
     */
    public function counter()
    {
        $counterKey = 'demo:page_views';

        // INCR: Atomically increment by 1 (thread-safe!)
        $count = Redis::incr($counterKey);

        // Set expiry if this is a new counter
        if ($count === 1) {
            Redis::expire($counterKey, 3600); // Expires in 1 hour
        }

        return response()->json([
            'operation'   => 'Atomic Counter',
            'count'       => $count,
            'explanation' => "INCR is atomic — even with thousands of concurrent requests, the count is always accurate. Current count: {$count}",
        ]);
    }

    /**
     * Demo 6: Clear all demo keys
     * Route: POST /redis-demo/flush
     */
    public function flush()
    {
        // Delete specific demo keys (never flush the entire Redis in production!)
        $keys = ['demo:greeting', 'demo:expensive_query', 'demo:task_queue',
                 'demo:user_profile', 'demo:page_views'];

        $deleted = 0;
        foreach ($keys as $key) {
            $deleted += Redis::del($key);
        }

        // Also clear the cache demo key
        Cache::forget('demo:expensive_query');

        return response()->json([
            'operation'   => 'Flush Demo Keys',
            'deleted'     => $deleted,
            'explanation' => "Deleted {$deleted} demo keys from Redis. All demos are reset.",
        ]);
    }

    /**
     * Demo 7: Get Redis server info
     * Route: GET /redis-demo/info
     */
    public function info()
    {
        try {
            $info = Redis::info();

            return response()->json([
                'connected'        => true,
                'redis_version'    => $info['Server']['redis_version'] ?? 'Unknown',
                'used_memory'      => $info['Memory']['used_memory_human'] ?? 'Unknown',
                'connected_clients' => $info['Clients']['connected_clients'] ?? 'Unknown',
                'total_keys'       => $info['Keyspace'] ?? [],
                'uptime_days'      => isset($info['Server']['uptime_in_days']) ? $info['Server']['uptime_in_days'] . ' days' : 'Unknown',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'connected' => false,
                'error'     => $e->getMessage(),
                'help'      => 'Make sure Redis server is running. On Windows: redis-server.exe',
            ], 500);
        }
    }
}

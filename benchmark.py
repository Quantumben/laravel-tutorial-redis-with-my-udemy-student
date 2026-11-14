import time
import statistics
import requests

BASE_URL = "http://127.0.0.1:8000/api"

endpoints = {
    "MySQL Direct": f"{BASE_URL}/todos-uncached",
    "Redis Cached": f"{BASE_URL}/todos",
}

for name, url in endpoints.items():
    times = []

    for i in range(20):
        start = time.perf_counter()

        response = requests.get(url, timeout=30)
        response.raise_for_status()

        elapsed = (time.perf_counter() - start) * 1000

        times.append(elapsed)

    print(f"\n{name}")
    print(f"Average: {statistics.mean(times):.2f} ms")
    print(f"Median:  {statistics.median(times):.2f} ms")
    print(f"Fastest: {min(times):.2f} ms")
    print(f"Slowest: {max(times):.2f} ms")
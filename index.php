<!-- index.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental - Home</title>
    <style>
        /* Amazing internal CSS: Modern, real-looking, responsive with gradients, shadows, animations */
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: linear-gradient(to bottom, #f0f4f8, #d9e2ec); color: #333; }
        header { background: #007bff; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        h1 { margin: 0; font-size: 2.5em; animation: fadeIn 1s ease-in; }
        .search-bar { max-width: 800px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .search-bar form { display: flex; flex-wrap: wrap; justify-content: space-between; }
        .search-bar input, .search-bar button { padding: 12px; margin: 10px; border: 1px solid #ddd; border-radius: 5px; flex: 1; min-width: 200px; }
        .search-bar button { background: #28a745; color: white; border: none; cursor: pointer; transition: background 0.3s; }
        .search-bar button:hover { background: #218838; }
        .featured { display: flex; flex-wrap: wrap; justify-content: center; margin: 20px; }
        .car-card { background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin: 15px; padding: 15px; width: 300px; text-align: center; transition: transform 0.3s; }
        .car-card:hover { transform: scale(1.05); }
        .car-card img { width: 100%; border-radius: 8px; }
        .filters { margin: 20px; padding: 20px; background: #f8f9fa; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .filters h3 { text-align: center; }
        .filter-group { display: flex; flex-wrap: wrap; justify-content: center; }
        .filter-group select, .filter-group input { margin: 10px; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @media (max-width: 768px) { .search-bar form { flex-direction: column; } .car-card { width: 90%; } }
    </style>
</head>
<body>
    <header>
        <h1>Welcome to Car Rental</h1>
    </header>
    <section class="search-bar">
        <form id="searchForm" method="GET" action="search.php">
            <input type="text" name="location" placeholder="Pickup Location (e.g., New York)" required>
            <input type="date" name="start_date" required>
            <input type="date" name="end_date" required>
            <button type="submit">Search Cars</button>
        </form>
    </section>
    <section class="filters">
        <h3>Filters</h3>
        <div class="filter-group">
            <!-- Filters will be passed via JS on submit -->
            <select id="car_type">
                <option value="">Car Type</option>
                <option value="1">Sedan</option>
                <option value="2">SUV</option>
                <option value="3">Hatchback</option>
            </select>
            <input type="number" id="min_price" placeholder="Min Price">
            <input type="number" id="max_price" placeholder="Max Price">
            <select id="fuel_type">
                <option value="">Fuel Type</option>
                <option value="1">Petrol</option>
                <option value="2">Diesel</option>
                <option value="3">Electric</option>
            </select>
            <select id="brand">
                <option value="">Brand</option>
                <option value="1">Toyota</option>
                <option value="2">Mercedes</option>
                <option value="3">Ford</option>
                <option value="3">BMW</option>
                <option value="3">Rolls Royce</option>
 
 
            </select>
        </div>
    </section>
    <section class="featured">
        <h2>Featured Cars</h2>
        <!-- Static featured cars for demo -->
        <div class="car-card">
            <img src="https://example.com/images/camry.jpg" alt="Toyota Camry">
            <h3>Toyota Camry</h3>
            <p>$50/day</p>
        </div>
        <div class="car-card">
            <img src="https://example.com/images/Benz A Class.jpg" alt="Mercedes Benz A Class">
            <h3>Mercedes Benz A Class</h3>
            <p>$110/day</p>
        </div>
        <div class="car-card">
            <img src="https://example.com/images/explorer.jpg" alt="Ford Explorer">
            <h3>Ford Explorer</h3>
            <p>$70/day</p>
        </div>
        <div class="car-card">
            <img src="https://example.com/images/explorer.jpg" alt=" X5">
            <h3>BMW X5</h3>
            <p>$100/day</p>
        </div>
        <div class="car-card">
            <img src="https://example.com/images/explorer.jpg" alt="Cullinan ">
            <h3>Rolls Royce Cullinan</h3>
            <p>$160/day</p>
 
        </div>
    </section>
    <script>
        // JS for appending filters to search URL and redirection
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const url = new URL(form.action, window.location.href);
            const params = new URLSearchParams(new FormData(form));
            params.append('car_type', document.getElementById('car_type').value);
            params.append('min_price', document.getElementById('min_price').value);
            params.append('max_price', document.getElementById('max_price').value);
            params.append('fuel_type', document.getElementById('fuel_type').value);
            params.append('brand', document.getElementById('brand').value);
            window.location.href = `${form.action}?${params.toString()}`;
        });
    </script>
</body>
</html>

<?php
// search.php
include 'db.php';
$location = isset($_GET['location']) ? $conn->real_escape_string($_GET['location']) : '';
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$car_type = isset($_GET['car_type']) ? (int)$_GET['car_type'] : 0;
$min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : 0;
$max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : PHP_FLOAT_MAX;
$fuel_type = isset($_GET['fuel_type']) ? (int)$_GET['fuel_type'] : 0;
$brand = isset($_GET['brand']) ? (int)$_GET['brand'] : 0;
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';
 
$sql = "SELECT c.id, c.model, b.name as brand, t.name as type, f.name as fuel, l.name as location, c.price_per_day, c.image_url, c.description, c.rating
        FROM cars c
        JOIN brands b ON c.brand_id = b.id
        JOIN car_types t ON c.type_id = t.id
        JOIN fuel_types f ON c.fuel_id = f.id
        JOIN locations l ON c.location_id = l.id
        WHERE l.name LIKE '%$location%'
        AND c.price_per_day >= $min_price AND c.price_per_day <= $max_price
        AND NOT EXISTS (
            SELECT 1 FROM bookings bk
            WHERE bk.car_id = c.id
            AND bk.status != 'cancelled'
            AND (bk.start_date <= '$end_date' AND bk.end_date >= '$start_date')
        )";
 
if ($car_type > 0) $sql .= " AND c.type_id = $car_type";
if ($fuel_type > 0) $sql .= " AND c.fuel_id = $fuel_type";
if ($brand > 0) $sql .= " AND c.brand_id = $brand";
 
if ($sort == 'price_asc') $sql .= " ORDER BY c.price_per_day ASC";
elseif ($sort == 'price_desc') $sql .= " ORDER BY c.price_per_day DESC";
elseif ($sort == 'rating') $sql .= " ORDER BY c.rating DESC";
 
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Listings</title>
    <style>
        /* Amazing internal CSS: Consistent with home, card grids, hover effects */
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: linear-gradient(to bottom, #f0f4f8, #d9e2ec); color: #333; }
        header { background: #007bff; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .listings { display: flex; flex-wrap: wrap; justify-content: center; margin: 20px; }
        .car-card { background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin: 15px; padding: 15px; width: 300px; text-align: center; transition: transform 0.3s; }
        .car-card:hover { transform: scale(1.05); }
        .car-card img { width: 100%; border-radius: 8px; }
        .sort { text-align: center; margin: 20px; }
        .sort select { padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; transition: background 0.3s; }
        button:hover { background: #0056b3; }
        @media (max-width: 768px) { .car-card { width: 90%; } }
    </style>
</head>
<body>
    <header>
        <h1>Available Cars</h1>
    </header>
    <div class="sort">
        <label for="sort">Sort by:</label>
        <select id="sort">
            <option value="price_asc" <?php if($sort=='price_asc') echo 'selected'; ?>>Price Low to High</option>
            <option value="price_desc" <?php if($sort=='price_desc') echo 'selected'; ?>>Price High to Low</option>
            <option value="rating" <?php if($sort=='rating') echo 'selected'; ?>>Best Rated</option>
        </select>
    </div>
    <section class="listings">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="car-card">
                    <img src="<?php echo $row['image_url']; ?>" alt="<?php echo $row['model']; ?>">
                    <h3><?php echo $row['model']; ?> (<?php echo $row['brand']; ?>)</h3>
                    <p>Type: <?php echo $row['type']; ?>, Fuel: <?php echo $row['fuel']; ?></p>
                    <p>Location: <?php echo $row['location']; ?></p>
                    <p>Price: $<?php echo $row['price_per_day']; ?>/day</p>
                    <p>Rating: <?php echo $row['rating']; ?>/5</p>
                    <p><?php echo substr($row['description'], 0, 100); ?>...</p>
                    <button onclick="bookCar(<?php echo $row['id']; ?>, '<?php echo $start_date; ?>', '<?php echo $end_date; ?>')">Book Now</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No cars available for the selected criteria.</p>
        <?php endif; ?>
    </section>
    <script>
        // JS for sort redirection and book redirection
        document.getElementById('sort').addEventListener('change', function() {
            const params = new URLSearchParams(window.location.search);
            params.set('sort', this.value);
            window.location.href = `?${params.toString()}`;
        });
 
        function bookCar(carId, start, end) {
            window.location.href = `book.php?car_id=${carId}&start_date=${start}&end_date=${end}`;
        }
    </script>
</body>
</html>
<?php $conn->close(); ?>

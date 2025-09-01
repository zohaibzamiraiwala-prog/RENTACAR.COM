<?php
// book.php
include 'db.php';
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_id = (int)$_POST['car_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $customer_name = $conn->real_escape_string($_POST['name']);
    $customer_email = $conn->real_escape_string($_POST['email']);
    $customer_phone = $conn->real_escape_string($_POST['phone']);
 
    // Calculate total price
    $sql_car = "SELECT price_per_day FROM cars WHERE id = $car_id";
    $car_result = $conn->query($sql_car);
    if ($car_result->num_rows > 0) {
        $car = $car_result->fetch_assoc();
        $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
        $total_price = $car['price_per_day'] * $days;
 
        // Check availability again
        $sql_check = "SELECT 1 FROM bookings WHERE car_id = $car_id AND status != 'cancelled' AND (start_date <= '$end_date' AND end_date >= '$start_date')";
        $check_result = $conn->query($sql_check);
        if ($check_result->num_rows == 0) {
            $sql_insert = "INSERT INTO bookings (car_id, customer_name, customer_email, customer_phone, start_date, end_date, total_price, status) 
                           VALUES ($car_id, '$customer_name', '$customer_email', '$customer_phone', '$start_date', '$end_date', $total_price, 'confirmed')";
            if ($conn->query($sql_insert)) {
                $confirmation = "Booking confirmed! Your total is $$total_price.";
            } else {
                $confirmation = "Error: " . $conn->error;
            }
        } else {
            $confirmation = "Car is no longer available.";
        }
    } else {
        $confirmation = "Car not found.";
    }
} else {
    $car_id = isset($_GET['car_id']) ? (int)$_GET['car_id'] : 0;
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
 
    $sql = "SELECT c.model, b.name as brand, c.price_per_day, c.image_url, c.description 
            FROM cars c JOIN brands b ON c.brand_id = b.id WHERE c.id = $car_id";
    $result = $conn->query($sql);
    $car = $result->num_rows > 0 ? $result->fetch_assoc() : null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Car</title>
    <style>
        /* Amazing internal CSS: Form styling, responsive, elegant */
        body { font-family: 'Arial', sans-serif; margin: 0; padding: 0; background: linear-gradient(to bottom, #f0f4f8, #d9e2ec); color: #333; }
        header { background: #007bff; color: white; padding: 20px; text-align: center; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .booking-form { max-width: 600px; margin: 20px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
        .booking-form input { display: block; width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        .booking-form button { background: #28a745; color: white; border: none; padding: 12px; cursor: pointer; width: 100%; border-radius: 5px; transition: background 0.3s; }
        .booking-form button:hover { background: #218838; }
        .car-details { text-align: center; margin-bottom: 20px; }
        .car-details img { width: 100%; max-width: 400px; border-radius: 8px; }
        .confirmation { text-align: center; color: #28a745; font-size: 1.2em; }
        @media (max-width: 768px) { .booking-form { padding: 15px; } }
    </style>
</head>
<body>
    <header>
        <h1>Book Your Car</h1>
    </header>
    <?php if (isset($confirmation)): ?>
        <div class="confirmation"><?php echo $confirmation; ?></div>
        <script>
            // JS redirection after confirmation
            setTimeout(() => { window.location.href = 'index.php'; }, 3000);
        </script>
    <?php elseif ($car): ?>
        <section class="car-details">
            <img src="<?php echo $car['image_url']; ?>" alt="<?php echo $car['model']; ?>">
            <h2><?php echo $car['model']; ?> (<?php echo $car['brand']; ?>)</h2>
            <p><?php echo $car['description']; ?></p>
            <p>Price: $<?php echo $car['price_per_day']; ?>/day</p>
            <p>Dates: <?php echo $start_date; ?> to <?php echo $end_date; ?></p>
        </section>
        <section class="booking-form">
            <form method="POST">
                <input type="hidden" name="car_id" value="<?php echo $car_id; ?>">
                <input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
                <input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <input type="tel" name="phone" placeholder="Your Phone">
                <button type="submit">Confirm Booking</button>
            </form>
        </section>
    <?php else: ?>
        <p>Invalid car selection.</p>
    <?php endif; ?>
</body>
</html>
<?php $conn->close(); ?>

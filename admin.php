<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

$conn = new mysqli("localhost", "root", "", "cardekho");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$edit_car = null;
if (isset($_POST['edit'])) {
    $edit_id = $_POST['id'];
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_car = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: login.php');
        exit;
    }

    if (isset($_POST['section'])) {
        $section = $_POST['section'];

        if ($section == 'header') {
            $nav_items = json_encode(explode(',', $_POST['nav_items']));
            $site_name = $_POST['site_name'];
            $logo_path = '';

            if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
                $logo_path = 'uploads/' . basename($_FILES['logo']['name']);
                move_uploaded_file($_FILES['logo']['tmp_name'], $logo_path);
            }

            $stmt = $conn->prepare("REPLACE INTO header (id, logo_path, nav_items, site_name) VALUES (1, ?, ?, ?)");
            $stmt->bind_param("sss", $logo_path, $nav_items, $site_name);
            $stmt->execute();
            $stmt->close();
        }

        if ($section == 'banner') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $image_path = '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image_path = 'uploads/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
            }

            $stmt = $conn->prepare("REPLACE INTO banner (id, title, description, image_path) VALUES (1, ?, ?, ?)");
            $stmt->bind_param("sss", $title, $description, $image_path);
            $stmt->execute();
            $stmt->close();
        }

        if ($section == 'car') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $category = $_POST['category'];
            $mileage = $_POST['mileage'];
            $year = $_POST['year'];
            $fuel_type = $_POST['fuel_type'];
            $body_type = $_POST['body_type'];
            $badge = $_POST['badge'];
            $image_path = '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image_path = 'uploads/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $image_path);
            }

            if (isset($_POST['car_id']) && !empty($_POST['car_id'])) {

                $car_id = $_POST['car_id'];
                if ($image_path) {
                    $stmt = $conn->prepare("UPDATE cars SET name=?, price=?, image_path=?, mileage=?, year=?, fuel_type=?, body_type=?, badge=?, category=? WHERE id=?");
                    $stmt->bind_param("sssssssssi", $name, $price, $image_path, $mileage, $year, $fuel_type, $body_type, $badge, $category, $car_id);
                } else {
                    $stmt = $conn->prepare("UPDATE cars SET name=?, price=?, mileage=?, year=?, fuel_type=?, body_type=?, badge=?, category=? WHERE id=?");
                    $stmt->bind_param("ssssssssi", $name, $price, $mileage, $year, $fuel_type, $body_type, $badge, $category, $car_id);
                }
            } else {

                $stmt = $conn->prepare("INSERT INTO cars (name, price, image_path, mileage, year, fuel_type, body_type, badge, category) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssss", $name, $price, $image_path, $mileage, $year, $fuel_type, $body_type, $badge, $category);
            }
            $stmt->execute();
            $stmt->close();
        }

        if ($section == 'footer') {
            $brand_description = $_POST['brand_description'];
            $contact_items = json_encode(explode(',', $_POST['contact_items']));
            $footer_columns = json_encode(explode(',', $_POST['footer_columns']));

            $stmt = $conn->prepare("REPLACE INTO footer (id, brand_description, contact_items, footer_columns) VALUES (1, ?, ?, ?)");
            $stmt->bind_param("sss", $brand_description, $contact_items, $footer_columns);
            $stmt->execute();
            $stmt->close();
        }
    }


    if (isset($_POST['delete'])) {
        $table = $_POST['table'];
        $id = $_POST['id'];

        $stmt = $conn->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}


$header = $conn->query("SELECT * FROM header LIMIT 1")->fetch_assoc();
$banner = $conn->query("SELECT * FROM banner LIMIT 1")->fetch_assoc();
$cars_most_searched = $conn->query("SELECT * FROM cars WHERE category = 'most_searched'");
$cars_latest = $conn->query("SELECT * FROM cars WHERE category = 'latest'");
$footer = $conn->query("SELECT * FROM footer LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - CarDekho</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f4f6f9;
            color: #1a1f36;
        }

        .header {
            background: white;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 800;
        }

        .logout-btn {
            padding: 8px 16px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .section h2 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 16px;
            color: #1a1f36;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section h2 svg {
            width: 24px;
            height: 24px;
            color: #ef4444;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input,
        .form-textarea,
        .form-select {
            width: 100%;
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
        }

        .btn-primary {
            background: #ef4444;
            color: white;
        }

        .btn-primary:hover {
            background: #dc2626;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .items-list {
            margin-top: 20px;
        }

        .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .item img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .item-actions {
            display: flex;
            gap: 8px;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 12px;
        }

        .card h3 {
            font-size: 18px;
            margin-bottom: 8px;
        }

        .card p {
            margin: 4px 0;
            font-size: 14px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>CarDekho Admin Panel</h1>
        <form method="POST" style="display: inline;">
            <button type="submit" name="logout" class="logout-btn">Logout</button>
        </form>
    </div>

    <div class="container">
      
        <div class="section">
            <h2>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
                Dashboard Overview
            </h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div
                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 12px; text-align: center;">
                    <h3 style="margin: 0; font-size: 24px;">
                        <?php echo $cars_most_searched->num_rows + $cars_latest->num_rows; ?></h3>
                    <p style="margin: 5px 0 0; opacity: 0.9;">Total Cars</p>
                </div>
                <div
                    style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 20px; border-radius: 12px; text-align: center;">
                    <h3 style="margin: 0; font-size: 24px;"><?php echo $cars_most_searched->num_rows; ?></h3>
                    <p style="margin: 5px 0 0; opacity: 0.9;">Most Searched</p>
                </div>
                <div
                    style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white; padding: 20px; border-radius: 12px; text-align: center;">
                    <h3 style="margin: 0; font-size: 24px;"><?php echo $cars_latest->num_rows; ?></h3>
                    <p style="margin: 5px 0 0; opacity: 0.9;">Latest Cars</p>
                </div>
            </div>
        </div>

        
        <div class="section">
            <h2>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                    </path>
                </svg>
                Header
            </h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="section" value="header">
                <div class="form-group">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" accept="image/*">
                </div>
                <div class="form-group">
                    <label class="form-label">Site Name</label>
                    <input type="text" name="site_name" class="form-input"
                        value="<?php echo $header['site_name'] ?? 'CarDekho'; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Navigation Items (comma separated)</label>
                    <input type="text" name="nav_items" class="form-input"
                        value="<?php echo $header ? implode(',', json_decode($header['nav_items'], true)) : 'New Cars,Used Cars,Compare,News'; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update Header</button>
            </form>
        </div>

        
        <div class="section">
            <h2>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <circle cx="9" cy="9" r="2"></circle>
                    <path d="m21 15-3.086-3.086a2 2 0 00-2.828 0L6 21"></path>
                </svg>
                Banner
            </h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="section" value="banner">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-input"
                        value="<?php echo $banner['title'] ?? 'Find Your Dream Car'; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                        class="form-textarea"><?php echo $banner['description'] ?? 'Compare prices, features & best offers from top brands. Get the best deals on new and used cars.'; ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary">Update Banner</button>
            </form>
        </div>

       
        <div class="section">
            <h2>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                    <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                    <path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5"></path>
                </svg>
                <?php echo $edit_car ? 'Edit Car' : 'Add Car'; ?>
            </h2>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="section" value="car">
                <input type="hidden" name="car_id" value="<?php echo $edit_car['id'] ?? ''; ?>">
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-input" value="<?php echo $edit_car['name'] ?? ''; ?>"
                        required>
                </div>
                <div class="form-group">
                    <label class="form-label">Price</label>
                    <input type="text" name="price" class="form-input" value="<?php echo $edit_car['price'] ?? ''; ?>"
                        required>
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category" class="form-select" required>
                        <option value="most_searched" <?php echo ($edit_car['category'] ?? '') == 'most_searched' ? 'selected' : ''; ?>>Most Searched</option>
                        <option value="latest" <?php echo ($edit_car['category'] ?? '') == 'latest' ? 'selected' : ''; ?>>
                            Latest</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Body Type (e.g. SUV, Sedan)</label>
                    <input type="text" name="body_type" class="form-input"
                        value="<?php echo $edit_car['body_type'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Fuel Type</label>
                    <select name="fuel_type" class="form-select" required>
                        <option value="Petrol" <?php echo ($edit_car['fuel_type'] ?? '') == 'Petrol' ? 'selected' : ''; ?>>Petrol</option>
                        <option value="Diesel" <?php echo ($edit_car['fuel_type'] ?? '') == 'Diesel' ? 'selected' : ''; ?>>Diesel</option>
                        <option value="Electric" <?php echo ($edit_car['fuel_type'] ?? '') == 'Electric' ? 'selected' : ''; ?>>Electric</option>
                        <option value="Hybrid" <?php echo ($edit_car['fuel_type'] ?? '') == 'Hybrid' ? 'selected' : ''; ?>>Hybrid</option>
                        <option value="CNG" <?php echo ($edit_car['fuel_type'] ?? '') == 'CNG' ? 'selected' : ''; ?>>CNG
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Mileage (e.g. 10-14 km/l)</label>
                    <input type="text" name="mileage" class="form-input"
                        value="<?php echo $edit_car['mileage'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Year</label>
                    <input type="text" name="year" class="form-input" value="<?php echo $edit_car['year'] ?? ''; ?>"
                        required>
                </div>
                <div class="form-group">
                    <label class="form-label">Badge (Optional, e.g. New Launch)</label>
                    <input type="text" name="badge" class="form-input" value="<?php echo $edit_car['badge'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" accept="image/*" <?php echo $edit_car ? '' : 'required'; ?>>
                    <?php if ($edit_car && $edit_car['image_path']): ?>
                        <p>Current Image: <img src="<?php echo $edit_car['image_path']; ?>"
                                style="width: 100px; height: 100px; object-fit: cover;"></p>
                    <?php endif; ?>
                </div>
                <button type="submit"
                    class="btn btn-primary"><?php echo $edit_car ? 'Update Car' : 'Add Car'; ?></button>
            </form>

            <div class="items-list">
                <h3>Most Searched Cars</h3>
                <div class="cards-grid">
                    <?php while ($car = $cars_most_searched->fetch_assoc()): ?>
                        <div class="card">
                            <img src="<?php echo $car['image_path']; ?>" alt="<?php echo $car['name']; ?>">
                            <h3><?php echo $car['name']; ?></h3>
                            <p>Price: <?php echo $car['price']; ?></p>
                            <p>Body Type: <?php echo $car['body_type']; ?></p>
                            <p>Fuel Type: <?php echo $car['fuel_type']; ?></p>
                            <p>Mileage: <?php echo $car['mileage']; ?></p>
                            <p>Year: <?php echo $car['year']; ?></p>
                            <?php if ($car['badge']): ?>
                                <p>Badge: <?php echo $car['badge']; ?></p><?php endif; ?>
                            <div style="margin-top: 12px; display: flex; gap: 8px;">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="edit" value="1">
                                    <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
                                    <button type="submit" class="btn btn-secondary">Edit</button>
                                </form>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="table" value="cars">
                                    <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-secondary">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <h3>Latest Cars</h3>
                <div class="cards-grid">
                    <?php while ($car = $cars_latest->fetch_assoc()): ?>
                        <div class="card">
                            <img src="<?php echo $car['image_path']; ?>" alt="<?php echo $car['name']; ?>">
                            <h3><?php echo $car['name']; ?></h3>
                            <p>Price: <?php echo $car['price']; ?></p>
                            <p>Body Type: <?php echo $car['body_type']; ?></p>
                            <p>Fuel Type: <?php echo $car['fuel_type']; ?></p>
                            <p>Mileage: <?php echo $car['mileage']; ?></p>
                            <p>Year: <?php echo $car['year']; ?></p>
                            <?php if ($car['badge']): ?>
                                <p>Badge: <?php echo $car['badge']; ?></p><?php endif; ?>
                            <div style="margin-top: 12px; display: flex; gap: 8px;">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="edit" value="1">
                                    <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
                                    <button type="submit" class="btn btn-secondary">Edit</button>
                                </form>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="table" value="cars">
                                    <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-secondary">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>

        
        <div class="section">
            <h2>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path
                        d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                    </path>
                </svg>
                Footer
            </h2>
            <form method="POST">
                <input type="hidden" name="section" value="footer">
                <div class="form-group">
                    <label class="form-label">Brand Description</label>
                    <textarea name="brand_description"
                        class="form-textarea"><?php echo $footer['brand_description'] ?? "India's most trusted platform to buy and sell cars. Find your dream car with CarDekho."; ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Contact Items (comma separated)</label>
                    <input type="text" name="contact_items" class="form-input"
                        value="<?php echo $footer ? implode(',', json_decode($footer['contact_items'], true)) : 'support@cardekho.com,+91 9876543210,Jaipur, Rajasthan, India'; ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Footer Columns (comma separated)</label>
                    <input type="text" name="footer_columns" class="form-input"
                        value="<?php echo $footer ? implode(',', json_decode($footer['footer_columns'], true)) : 'New Cars,Used Cars,Company,Support'; ?>">
                </div>
                <button type="submit" class="btn btn-primary">Update Footer</button>
            </form>
        </div>
    </div>

    <?php $conn->close(); ?>
</body>

</html>
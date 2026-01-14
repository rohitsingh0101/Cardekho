<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CarDekho</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header h1 {
            font-size: 28px;
            font-weight: 800;
            color: #1a1f36;
            margin-bottom: 8px;
        }

        .login-header p {
            color: #64748b;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            font-family: inherit;
            color: #1a1f36;
            transition: border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 8px;
        }

        .login-btn:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .error {
            color: #ef4444;
            font-size: 14px;
            margin-top: 8px;
            text-align: center;
        }
    </style>

</head>

<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Admin Login</h1>
            <p>Access the CarDekho admin panel</p>
        </div>

        <?php
        session_start();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $conn = new mysqli("localhost", "root", "", "cardekho");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $username = $_POST['username'];
            $password = $_POST['password'];

            $stmt = $conn->prepare("SELECT password FROM admin WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if (password_verify($password, $row['password'])) {
                    $_SESSION['admin_logged_in'] = true;
                    header('Location: admin.php');
                    exit;
                } else {
                    $error = "Invalid credentials";
                }
            } else {
                $error = "Invalid credentials";
            }
            $stmt->close();
            $conn->close();
        }
        ?>

        <form method="POST">
            <div class="form-group">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" required>
            </div>

            <button type="submit" class="login-btn">Login</button>

            <?php if (isset($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
        </form>
    </div>

    <?php if ($show_dashboard): ?>
        <div class="header">
            <h1>CarDekho Admin Panel</h1>
            <form method="POST" style="display: inline;">
                <button type="submit" name="logout" class="logout-btn">Logout</button>
            </form>
        </div>

        <div class="container">
          
            <div class="section">
                <h2>Header</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="section" value="header">
                    <div class="form-group">
                        <label class="form-label">Logo</label>
                        <input type="file" name="logo" accept="image/*">
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
                <h2>Banner</h2>
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
                <h2>Add Car</h2>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="section" value="car">
                    <div class="form-group">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Price</label>
                        <input type="text" name="price" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select" required>
                            <option value="most_searched">Most Searched</option>
                            <option value="latest">Latest</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tags (comma separated)</label>
                        <input type="text" name="tags" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Specs (comma separated)</label>
                        <input type="text" name="specs" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Car</button>
                </form>

                <div class="items-list">
                    <h3>Most Searched Cars</h3>
                    <?php while ($car = $cars_most_searched->fetch_assoc()): ?>
                        <div class="item">
                            <div>
                                <strong><?php echo $car['name']; ?></strong>
                                <p><?php echo $car['price']; ?></p>
                            </div>
                            <div class="item-actions">
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="table" value="cars">
                                    <input type="hidden" name="id" value="<?php echo $car['id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-secondary">Delete</button>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>

                    <h3>Latest Cars</h3>
                    <?php while ($car = $cars_latest->fetch_assoc()): ?>
                        <div class="item">
                            <div>
                                <strong><?php echo $car['name']; ?></strong>
                                <p><?php echo $car['price']; ?></p>
                            </div>
                            <div class="item-actions">
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

           
            <div class="section">
                <h2>Footer</h2>
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
    <?php endif; ?>
</body>

</html>
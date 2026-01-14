<?php
$conn = new mysqli("localhost", "root", "", "cardekho");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cars = $conn->query("SELECT * FROM cars WHERE category = 'most_searched' ORDER BY id DESC");
$banner = $conn->query("SELECT * FROM banner WHERE id=1")->fetch_assoc();
$footer = $conn->query("SELECT * FROM footer WHERE id=1")->fetch_assoc();
$header = $conn->query("SELECT * FROM header WHERE id=1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarDekho | Find Your Dream Car</title>
    <meta name="description" content="India's #1 Car Platform - Compare prices, features & best offers from top brands">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
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
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

       
        .header {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid #e5e7eb;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-icon svg {
            width: 24px;
            height: 24px;
            fill: white;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 800;
            color: #1a1f36;
        }

        .logo-text span {
            color: #ef4444;
        }

        .nav {
            display: flex;
            align-items: center;
            gap: 32px;
        }

        .nav a {
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            font-size: 15px;
            transition: color 0.2s;
            position: relative;
        }

        .nav a:hover {
            color: #1a1f36;
        }

        .nav a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: #ef4444;
            transition: width 0.2s;
        }

        .nav a:hover::after {
            width: 100%;
        }

        .nav.active {
            display: flex;
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            flex-direction: column;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }

        .btn-ghost {
            background: transparent;
            color: #1a1f36;
        }

        .btn-ghost:hover {
            background: #f1f5f9;
        }

        .btn-primary {
            background: #ef4444;
            color: white;
        }

        .btn-primary:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .btn-outline {
            background: white;
            border: 2px solid #ef4444;
            color: #ef4444;
        }

        .btn-outline:hover {
            background: #ef4444;
            color: white;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            cursor: pointer;
            padding: 8px;
        }

       
        .hero {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 50px;
            left: 50px;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            filter: blur(80px);
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -50px;
            right: 100px;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            filter: blur(100px);
        }

        .hero-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 60px 24px 80px;
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-content {
            color: white;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 24px;
        }

        .hero-badge svg {
            width: 16px;
            height: 16px;
        }

        .hero h1 {
            font-size: 56px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 20px;
        }

        .hero h1 .highlight {
            color: #fbbf24;
        }

        .hero-description {
            font-size: 18px;
            opacity: 0.9;
            max-width: 480px;
            margin-bottom: 32px;
        }

        .hero-stats {
            display: flex;
            gap: 24px;
        }

        .stat {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat-number {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }

      
        .search-box {
            background: white;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .search-box h2 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1f36;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 8px;
        }

        .form-select {
            width: 100%;
            padding: 14px 16px;
            border: none;
            background: #f1f5f9;
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            color: #1a1f36;
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 20px;
        }

        .form-select:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        .search-btn {
            width: 100%;
            padding: 16px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
            box-shadow: 0 10px 30px -10px rgba(239, 68, 68, 0.5);
            margin-top: 8px;
        }

        .search-btn:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .search-btn svg {
            width: 20px;
            height: 20px;
        }

      
        .brands {
            padding: 48px 0;
            background: white;
            border-bottom: 1px solid #e5e7eb;
        }

        .brands-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: #1a1f36;
            text-align: center;
            margin-bottom: 40px;
        }

        .brands-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 16px;
        }

        .brand-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            padding: 20px;
            border-radius: 16px;
            text-decoration: none;
            transition: all 0.2s;
            background: #f8fafc;
        }

        .brand-item:hover {
            background: #f1f5f9;
            transform: translateY(-4px);
        }

        .brand-logo {
            width: 64px;
            height: 64px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .brand-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .brand-name {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
            text-align: center;
        }

        .brand-item:hover .brand-name {
            color: #1a1f36;
        }

       
        .featured {
            padding: 80px 0;
        }

        .featured-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .section-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 40px;
        }

        .section-header-content h2 {
            font-size: 36px;
            font-weight: 800;
            color: #1a1f36;
            margin-bottom: 8px;
        }

        .section-header-content p {
            font-size: 17px;
            color: #64748b;
            max-width: 500px;
        }

        .view-all-link {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ef4444;
            font-weight: 600;
            text-decoration: none;
            transition: gap 0.2s;
        }

        .view-all-link:hover {
            gap: 12px;
        }

        .cars-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
        }

        .car-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
            transition: all 0.3s;
        }

        .car-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .car-image {
            position: relative;
            height: 200px;
            overflow: hidden;
            background: #f1f5f9;
        }

        .car-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s;
        }

        .car-card:hover .car-image img {
            transform: scale(1.08);
        }

        .car-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            background: #f97316;
            color: white;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
        }

        .like-btn {
            position: absolute;
            top: 16px;
            right: 16px;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .like-btn:hover {
            transform: scale(1.1);
        }

        .like-btn svg {
            width: 20px;
            height: 20px;
            stroke: #64748b;
            fill: none;
            transition: all 0.2s;
        }

        .like-btn.liked svg {
            stroke: #ef4444;
            fill: #ef4444;
        }

        .car-info {
            padding: 20px;
        }

        .car-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1f36;
            margin-bottom: 4px;
        }

        .car-price {
            font-size: 20px;
            font-weight: 800;
            color: #ef4444;
            margin-bottom: 12px;
        }

        .car-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 16px;
        }

        .car-tag {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #f1f5f9;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            color: #475569;
        }

        .car-tag svg {
            width: 14px;
            height: 14px;
        }

        .car-specs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 16px;
        }

        .car-spec {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #64748b;
        }

        .car-spec svg {
            width: 16px;
            height: 16px;
        }

        .car-btn {
            width: 100%;
            padding: 12px;
            background: white;
            border: 2px solid #ef4444;
            color: #ef4444;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }

        .car-btn:hover {
            background: #ef4444;
            color: white;
        }

       
        .quote-section {
            padding: 80px 0;
            background: #f1f5f9;
        }

        .quote-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .quote-content h2 {
            font-size: 40px;
            font-weight: 800;
            color: #1a1f36;
            line-height: 1.2;
            margin-bottom: 16px;
        }

        .quote-content h2 span {
            color: #ef4444;
        }

        .quote-content>p {
            font-size: 18px;
            color: #64748b;
            margin-bottom: 32px;
            max-width: 400px;
        }

        .benefits {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .benefit {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .benefit-icon {
            width: 48px;
            height: 48px;
            background: rgba(239, 68, 68, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .benefit-icon svg {
            width: 24px;
            height: 24px;
            stroke: #ef4444;
        }

        .benefit span {
            font-weight: 600;
            color: #1a1f36;
        }

        .quote-form-box {
            background: white;
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #e5e7eb;
        }

        .quote-form-box h3 {
            font-size: 22px;
            font-weight: 700;
            color: #1a1f36;
            margin-bottom: 24px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: none;
            background: #f1f5f9;
            border-radius: 12px;
            font-size: 15px;
            font-family: inherit;
            color: #1a1f36;
        }

        .form-input:focus {
            outline: 2px solid #3b82f6;
            outline-offset: 2px;
        }

        .form-input::placeholder {
            color: #94a3b8;
        }

        .checkbox-group {
            margin: 20px 0;
        }

        .checkbox-group p {
            font-size: 14px;
            font-weight: 600;
            color: #1a1f36;
            margin-bottom: 12px;
        }

        .checkbox-options {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
        }

        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            font-size: 14px;
            color: #64748b;
        }

        .checkbox-label input {
            width: 18px;
            height: 18px;
            accent-color: #ef4444;
            cursor: pointer;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: all 0.2s;
            box-shadow: 0 10px 30px -10px rgba(239, 68, 68, 0.5);
            margin-top: 8px;
        }

        .submit-btn:hover {
            background: #dc2626;
            transform: translateY(-2px);
        }

        .form-note {
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            margin-top: 16px;
        }

       
        .footer {
            background: #1a1f36;
            color: white;
            padding: 64px 0 0;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 48px;
        }

        .footer-brand .logo {
            margin-bottom: 16px;
        }

        .footer-brand .logo-text {
            color: white;
        }

        .footer-brand p {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            margin-bottom: 24px;
            max-width: 280px;
        }

        .footer-contact {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .footer-contact-item {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
        }

        .footer-contact-item svg {
            width: 16px;
            height: 16px;
        }

        .footer-column h4 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-column ul {
            list-style: none;
        }

        .footer-column li {
            margin-bottom: 12px;
        }

        .footer-column a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }

        .footer-column a:hover {
            color: #ef4444;
        }

        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 24px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .footer-bottom p {
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
        }

        .social-links {
            display: flex;
            gap: 12px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .social-link:hover {
            background: #ef4444;
        }

        .social-link svg {
            width: 18px;
            height: 18px;
            fill: white;
        }

       
        @media (max-width: 1200px) {
            .cars-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 992px) {
            .hero-container {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .hero h1 {
                font-size: 42px;
            }

            .hero-description {
                margin: 0 auto 32px;
            }

            .hero-stats {
                justify-content: center;
            }

            .search-box {
                max-width: 420px;
                margin: 0 auto;
            }

            .brands-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .cars-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .quote-container {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {
            .nav {
                display: none;
            }

            .header-actions {
                display: flex;
                gap: 8px;
            }

            .mobile-menu-btn {
                display: block;
            }

            .hero h1 {
                font-size: 32px;
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .section-header-content h2 {
                font-size: 28px;
            }

            .brands-grid {
                grid-template-columns: repeat(4, 1fr);
                gap: 8px;
            }

            .brand-item {
                padding: 12px;
            }

            .brand-logo {
                width: 48px;
                height: 48px;
            }

            .brand-name {
                font-size: 11px;
            }

            .cars-grid {
                grid-template-columns: 1fr;
            }

            .quote-content h2 {
                font-size: 28px;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }
        }
    </style>
</head>

<body>
   
    <header class="header">
        <div class="header-container">
            <a href="#" class="logo">
                <div class="logo-icon">
                    <?php if ($header && $header['logo_path']): ?>
                        <img src="<?php echo $header['logo_path']; ?>" alt="Logo"
                            style="width: 42px; height: 42px; object-fit: contain;">
                    <?php else: ?>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                            <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                            <path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5"></path>
                        </svg>
                    <?php endif; ?>
                </div>
                <span class="logo-text"><?php
                $site_name = $header['site_name'] ?? 'CarDekho';
                $prefix = substr($site_name, 0, -5);
                $suffix = substr($site_name, -5);
                echo htmlspecialchars($prefix) . '<span>' . htmlspecialchars($suffix) . '</span>';
                ?></span>
            </a>

            <nav class="nav">
                <?php
                $nav_items = json_decode($header['nav_items'] ?? '["New Cars","Used Cars","Compare","News"]', true);
                foreach ($nav_items as $item):
                    ?>
                    <a href="#"><?php echo htmlspecialchars($item); ?></a>
                <?php endforeach; ?>
                <div class="header-actions">
                    <a href="login.php" class="btn btn-ghost">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Admin Login
                    </a>
                    <a href="#" class="btn btn-primary">Sell Car</a>
                </div>
            </nav>

            <button class="mobile-menu-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </header>

    
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z" />
                    </svg>
                    India's #1 Car Platform
                </div>

                <h1>Find Your<br><span class="highlight"><?php echo $banner['title'] ?? 'Dream Car'; ?></span></h1>

                <p class="hero-description">
                    <?php echo $banner['description'] ?? 'Compare prices, features & best offers from top brands. Get the best deals on new and used cars.'; ?>
                </p>

                <div class="hero-stats">
                    <div class="stat">
                        <div class="stat-number">50K+</div>
                        <span class="stat-label">Cars Listed</span>
                    </div>
                    <div class="stat">
                        <div class="stat-number">1M+</div>
                        <span class="stat-label">Happy Customers</span>
                    </div>
                </div>
            </div>

            <div class="search-box">
                <h2>Search Your Perfect Car</h2>

                <div class="form-group">
                    <label class="form-label">Select Brand</label>
                    <select class="form-select" id="brandSelect">
                        <option value="">Choose a brand</option>
                        <option value="toyota">Toyota</option>
                        <option value="hyundai">Hyundai</option>
                        <option value="tata">Tata</option>
                        <option value="mahindra">Mahindra</option>
                        <option value="honda">Honda</option>
                        <option value="maruti">Maruti Suzuki</option>
                        <option value="kia">Kia</option>
                        <option value="mg">MG</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Budget Range</label>
                    <select class="form-select" id="budgetSelect">
                        <option value="">Select budget</option>
                        <option value="5">Below ₹5 Lakh</option>
                        <option value="10">₹5 - 10 Lakh</option>
                        <option value="20">₹10 - 20 Lakh</option>
                        <option value="50">₹20 - 50 Lakh</option>
                        <option value="above">Above ₹50 Lakh</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Fuel Type</label>
                    <select class="form-select" id="fuelSelect">
                        <option value="">Select fuel type</option>
                        <option value="petrol">Petrol</option>
                        <option value="diesel">Diesel</option>
                        <option value="electric">Electric</option>
                        <option value="hybrid">Hybrid</option>
                        <option value="cng">CNG</option>
                    </select>
                </div>

                <button class="search-btn" id="searchBtn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.35-4.35"></path>
                    </svg>
                    Search Cars
                </button>
            </div>
        </div>
    </section>

   
    <section class="brands">
        <div class="brands-container">
            <h2 class="section-title">Popular Brands</h2>

            <div class="brands-grid">
                <a href="#" class="brand-item">
                    <div class="brand-logo">
                        <img src="data:image/jpeg;base64,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"
                            alt="Toyota">
                    </div>
                    <span class="brand-name">Toyota</span>
                </a>
                <a href="#" class="brand-item">
                    <div class="brand-logo">
                        <img src="https://www.carlogos.org/car-logos/hyundai-logo-2011-download.png" alt="Hyundai">
                    </div>
                    <span class="brand-name">Hyundai</span>
                </a>
                <a href="#" class="brand-item">
                    <div class="brand-logo">
                        <img src="data:image/jpeg;base64,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"
                            alt="Tata">
                    </div>
                    <span class="brand-name">Tata</span>
                </a>
                <a href="#" class="brand-item">
                    <div class="brand-logo">
                        <img src="data:image/jpeg;base64,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"
                            alt="Mahindra">
                    </div>
                    <span class="brand-name">Mahindra</span>
                </a>
                <a href="#" class="brand-item">
                    <div class="brand-logo">
                        <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBw8QDxUQDxAVEBAPFRUPEBAVFRUVFxUVFRUWGBUWFRUYHSggGBslGxUVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OFQ8PFSsdFRkrKysrKysrLSsrLSsrKy0tLSsrLS0rNy03Ky0tLTcrKystLSsrLSsrKysrKysrKysrK//AABEIALgBEQMBIgACEQEDEQH/xAAcAAEAAwADAQEAAAAAAAAAAAAAAQYHAgQFAwj/xABREAABAwIBBggJBgoJBAMAAAABAAIDBBEFBgcSEyExIkFRUmFxgZEUMmJygpKhscEjQpOiwtEVJDNTZIOjstLhNENEVFVjc7PUNbTD8BclJv/EABYBAQEBAAAAAAAAAAAAAAAAAAABAv/EABgRAQEBAQEAAAAAAAAAAAAAAAABEUEx/9oADAMBAAIRAxEAPwDcUREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBERAREQEREBEUFAREQLpdFBKCbpddJ1dpbIWmTyr6LfW4+xfOofI1pfLMyBg2k7AB1ufsQejdcXygbyB1myqFVlVhTPGrXVBJtaHWzg9kIIXMYrEWGWLC6mRjWl+scyKO4AJNhLIHndzVBYzicG7XR35NNp9xXA4xB+cB6g4+4LOxnQjdspsMklNrhoJd/sxyfHcvo3LvFX/ksCcPONT8aZqC+nG4OVx6o5T9lDjUPlnqil/hVIGVWPuHBwdrb8pk+IapbjuUzvFw2Ecdjs98wVF1/DcXNl+jf9yHGoubL9G/7lSji+VP8Ah9P9X/kLg7GcqRvw6nPUAfdUIL23GITzx1xSD7K5DFYeceXxX/cs/OUWUw34ZEbcjHn3SlScrcfb42Dtd1Nm+Acgv/4Vg/OD2rmMSg/Os7XAe9ZwcvsWb+VwJxHkiq+FO5cTnQLP6ThEsW3j1g/3IGbVBpzKljvFe09TgfcvrdUrDMfiq4RUNwqodE4uAe3wZ54JLXcES6W8HcCuMeVeEBxY+aWke3eJWT09reU5obbtQXe6XXj0FQJm6dJWMqGcocyUes039q7Yq3t/KssOe3hDtG8e1B3bpdcI5A4XaQQdxBuFzVBERAupUIEEoiICIiAiIgKCpUFAREQLrofljvOqGy3PIP7vvXPE3HRDGmzpXCMHkBuXnoOiHdtlSM8eUhoMOEMDtCasvBGW7CyNo+VcOTYWtvxF4QeZlXnLlNR+DsEi8IqbmMytAc0OHjCIHYbbbvdwRbceKMNzY1lU4T4zXPe/fqYjpFv6599DqjaB0r1MzeSTKKhbUPYPCaxokc4jayI2McY5Bazj0noC0NBkuWmT9Lhz6QUjDHrpHslcXvkc8NDXNDnvJNrk7AbdwtpeGkOp2X2gtAI7FS872wUR/SXDvid9ytmBPvA3qChWd5nZfB62uw92zQcXMG6+pkdE51ulpiPVZa1ZY3XyeA5WMfuZVGO56J2aoj6VgPctkCoJZSiCEspRBFuhLKUQRZZ/nqxLU4aIr2NTK1h81l5HdnAaPSWgrGs70hq8Vo6Bu0DQa4DlqJAHdoZHftQaNkPRGnwymiOxzYWOf57xpv8ArOKp9Zh0NdjPgtSzWQuhkkLblpDmlmi4OaQ5p4R2g8a0WR1mm3ENncs/wA6WUBPNppfa+IKD4YvmgjDjNhtXLSzAcEPc546AJQRK0Xt849S8bDc4eJ4TUCkx2Fz4zsbUgAu0b+O1w2TMFxf5w47nYtpXhZZ5Mw4lSPppRtPChkttjkAOi4e4jjBIQdqKRrmCopXB8cgElmm7ZGkX0m9Nu9ejDKHAOabgi4KxvMZjksU02EVOwxl8sLT8xzHWnjHRchw9JavSnVzPi4njXs7TaQD0i0/rEHoIiKgiIEEoiICIiAiIgKCpUFARFBQefKdKrYL7I4nvI8p7mNYe5sg9JY7nZb4bj1NQ/MYyKN3QZ5CX/VDFr9NtrZzyQ07R605PvCycM1+WEpO0QyRNHoxRn4FFjao2gAACwGwDoG5clAUojPs8A+QpHc2qHtikVgyafeBvUvCzxD8SgdzauP2slXqZKPvC3qU6VRs+dK5klJVxmzhpwX8tpbLEe8PWr4PXNqKeKdvizxslHptB+Kpud+h1uESPAu6mfHUjqDtF/wBR7lyzNYlrsLEZN3Usj4D5pOmz6rwPRQXxERUEREBERBBWI5OSfhDKmSfxmQOnlbyaMTRTx+1wd1rWMrcT8EoKio44onOZ55FmD1i0LM8wmH7KqpO3bHStPmt1j/34+4KDUq19mHqVFyMOljk55KZw75YvuVyxZ9mHqVMzc8LFat3NhYPWkP8ACnSNMREVGGZTxihysgnbcNqJonG3+e3Uv/eJWw4gdGandzpHwu6nxPd+9GwdqyfPtDoVVLUje3Q2+ZI5w9pC1fGTdkbuSeAjtlYPc4qLXpoiKoIEQIJREQEREBERAUFSoKAiIg8nD3Xq6nydQz9npfbWZ5JN08p61/JUOaPRikH2QtIwhx8MrRyPgt1eDs/ms5yIH/6GtP6XKPqTqVY2AIoClVFFzxD/AOtDuZUQO73Fv2l9sjpPkm9QU53YycIlI+bJTv7qiO/suupkTJ8k3qClL4tmJ0LaimlgdtbPG+E+m0j4rIcxOIujq5qSTYZog+3EJIHaLh12kPqLaIVglfJ+C8pXP8WNtSJjt/qqoXf2AyP9VUj9BooapQEREBEQoM0z64pq6COnB21MoLhe3Ai4R+uY/avTzSYfqMHgJ8ao06k8VxI67PqBizbPNXOqsWbSxm5hbHSsHJLM4OP70YPmrdaOkbDCyFnixMbG3qY0Ae5CvJygkswqqZqRetrn+TTtHfMT8O5WDKmWzD1FeHmdF3Vr+WWNvc1x+0oRpKIioyHP/EDTtdxtZceuB9paHib/AMTjcefSHvnh+9ULP3/RD0Rg/towrvi1/AIhyuoh3zwKKsKKApVQQIgQSiIgIiICIiAoKlQUBEUFB42HbK+rHObTP72yN+ws8yJ4OUNeP0p31o5z8FfqMkYtUg7nUlG4dYlrAbd7fYqDk6dHKavH6RC71oH/AMQUqxrgUqApVRV85rL4PV+THp+q5rvgq5kDNeJvUrdl1Dp4VWN5aaY90bj8FnWb2p+Tb1BSnGtU+5Yzn6wvRqaep0eDPE+mkPlRnSYD1h7/AFVsNDJcBVXO/hJqcKkc0XfSObVt2cTLiT9m5/chHrZA4x4ZhtPOXXfoCOX/AFI+A+/aL9qsKxrMjjGrlloXmzZ/xmHzwAHtHSW6Jt5BWyqrREREF8K2qZDG+WQ2ZE10jz5LQSfYF91n+eTGRDQeDNPytadXbj1TSDIbch2N9I8iDMs30b8Qx6OaTfrJcQlG+2iSWi/IHvYOxfoiRZPmIwewqaxw8YiliNuJtnSEdBJYPQWq1MlghVLyymswrrZl2/i9U7nVOj3RsP2l1Mtangu7V6mZqK2Hvd+cqJHdzWM+ypDi+KFKhUZNn6P4q4f5TfbO37lfcRH4nCLXvJRj9tCqJn3benDecGN75f5K/YzwWU0fOqIGepd/2FB7SIioIEQIJREQEREBERAUFSoKAoUogr77NxdvLNRv7dTOz3a5UVgEeVVRf+sFLJ7I2/aV4xohmJUEnP8ACqUenE2Uf9uVRMrvkcpmyW2S0bHjpdFKXe6MJVjXApUBSiOljUOnTTM58UjPWYQsPzeVXybDyhvwW9yNu0jlBHevzpka/VuLOY4t9U2+ClVt2Fz7AvXOi9pa4XDgWkcoOwhVPCKjYFYIJkjLF8SyXnoa3VQktfE7XUcnOjDrtF+NzfFI6OQhafk5lkyVojrWGlqRscHAiN55zHnYL80m/XvXsYjQw1LA2Vt9E6THA2cx3Oa7iP8A7uXRmhr2CzNRVtGwa0GN/RcgFrj07OpF1YI5WuF2uBB3EEEd4XIuHKqNURVxO3CoXHlbJF7LkFcIoa6//SYu2SH+JBYcZynpqYWuZ5fmwxDTcTxA22N63WWU4/h9dWVOvqI7VNQRFTU19jG8Q3bGgXJPnFabRjEN2ppaYcZu557GtAB713aPD2RvMriZZ3jRdM617c1gGxjegcgvdBOTuEsoaSOmYbiJti7nPO17u1xJUYhPsK+00y8TFKjglKii5Y1FwVds00WjhMXlvmf3yv8AuWa5Vz3utXzdR6OE0vTEH+uS77Ska4siIi0jIs8rtOenh/OT0sNvOdIT7wr9lFc1NAwbjVOe7qZTTm/fZULLtuuxyhhv41ZHJ9CyN3xPeVf6u7sTpm8UcFTKRyEup2MPcZPapFe6iIqgiIEEoiICIiAiIgKCpUFAREQVfLlwYaKc/wBTXwAkckwfAf8AdCqWdWLQxXDZtwkE9M7t0QL/AEhVxzi0xkwqp0Rd8UfhEdufA4St9rAqlnjeJcMpK+PaIZ4KkEc2RpG/kuWoNLo36UbDzmtPeAvsvOyfnD6WNwOwt2dXF7F6KCCV+cYW6rEaqPdoVM4A6Na/R9ll+jivzvlWNXjdWNwMocPTjY73kqVYv2D1OwKx09SqLg9RsCsdNUqJYskdQujimVlBSHRqauKJw26BeNP1BwvYs/zjZXSU7W0lNJq55wXySgi8UW0XB4nOsbHiAPKF3c32RT3wieSKOFkvDDpWa2eQHbpu0jZl94vcm+2ypj2nZ1sHG6oe/wA2Cf4sCDOvhB3zSt6TTz29jSvfjyNpPnaTvo2/uMC4uyLpeIvb9Gf3mFB0cOy8wuoIbFWxFzjYMeTE4nkDZA03XsyVCq2U2QjnxHUxwVIt+SkjbFI7zJm2be3EWgE8YVIyLykfTVQoJXPNPKXMpxJfTglaSDA6+21wRbiIHKhjVJ6peDi1VsK+9RUqv4pU7CoSKflJNv7Vu+SkGroKaPmU8Le6Nq/POOv0iQOPYO1fpeki0I2s5jWt7gArFr6oi4yOsCeQX7lUZROBPlXTt3inZUVDu57GnvDVeqJ5filQfmwU9PED5T3zSPHdqj2qjZvG+EY9X1G8U8TKZp6XkEj9me/pVwyMfrZK6ovds1ZJGzzadjINnRpRuRVoRERBAiIJREQEREBERAUFSoKAiIg+VTC2RjmOF2vaWOHKHCxHcs5osOdV5O1OGO4U9CJqKx36dO7Tpz2tERHWtLVDqar8HY7d/BpcZYxun81tXCNEAni02aI6bdCDlmixbX4dGCbuY0A9nBPu9qvSo2E0bqCuexjLQvJfYDZoPcTfra64P89t4BUgFYBnUj1eOSH87HDL9XV/+NfoBYjnzh0cQp5LflIC0n/TkP8AGEqz11MIqNgVjp6npVFwqo2Bd7GcTMVJI9ps7RLWHyncEe0qLjpZI0X4Yx0uk4UIc6dwO4wwECNvU4llx5Tl+jmtssmzAYNoU09WRtmeKePzItru97iPQWtLTNEREAhYVn1wUwVUdZDwfCBtcNlp4bFrusst9Gt1VOzrYR4VhUwAu+nAqo9m28Vy4DrYXt9JBUaLFhUU8cw2a1gfbkJHCHYbjsXl4nU7Cq/kZXfi7oSfyLzo+Y/hD26S7OIVGxZakdClj1tbTxjbp1ELSOgyNv7Lr9Mr845DM1mM0bf80v8Ao43v97Qv0cFYlF5WU1XqaWR+7Zbv/ldeqqblmZKgtp423aXBtjuc87NvQBcnoBVRXc2Moo8Gq8Ul2eEST1gvsuxg0Yxt5XB1vOCuuQlE6DDaZjxaQxCWXZb5SX5SS/TpPKq+VUbZZKPAKfa06E1bb5tNCQSHW3F7gO9aK0bNm5ByREQERAglERAREQEREBQVKgoCIiAvDywycixGkdTSHRJIfFKN8cjdrHjqO/lBK9xEGU4JljJDIMOxq9NWQHRhqyCWSt3Ak7NJrrb+O3ERsv8AFiQY0OdbRO4tOkx3mP3Hq39CnKLJykxCLVVcLZWi+idzmE8bHja09Szmozd4tQEnCMQL4uKnmNjbkv4rvqqDQ25TUmlovl1TuSQFntOz2rOs+bWSx0tRE4SiF0jJHMIeGMkDCHPLdjQTGBc8q86bHscpho1mE6xo3ujY4N7o7sHcurFnDpGO+VoZIX7iQGA9Is1rbjoTVxVKKWy+WUtXdjGb9pkI5Q0bPa72KzVGO4BPwix1O48bIXt7wJdH6q40MuANfrXVOteLavWB7Wssb3DdBwJvxm9uKyitqyLwkUWH09Nazo4xrP8AUdwpD6znL2ysiZlnS/Nxd3bM7405X2ZllHxYuPpGn306us41e6LLBle3/GG+uz/jo/K9v+Ls9dg91OmmNTXCRgcC1wuCLEcoO8LK35Yt48XZ2SNHupl8zlnT/OxfulPwp00xm/gZoMQnpTujc+DrDDpRHpJjIPavrVzXVixmuwSoOslqgZ7hwnaZHPNtnC+SaHi2zbxbiunBieAx7XPfUEcRhkLe4StB7QQo07WaKK+LCVwtHDFLeQjgiRwaGtLtwJaXbOhbRUZR0bDYzsc7msOmerg3WNyZw8OsGRUj5A3xGBjGtb5rHaTR3LsU+U2KTbKLB32OwPeHhvbohsdusWVStcGLtfsYHF3N0eF280dJIHSqdlNlxDSO1UAFbiMnycFPFd7YyeUje7ZtPRxC68aHI7KGuAbWVkdDT8cMO8DkDGWb7VeckchaHDReBhfMRZ1RJZ0h5QDuaNm4dt0R1s3+S8tI2SprXa3EK0iSpfvDR82JvQPf0AK4WUKVQREQECIEEoiICIiAiIgKCpRBCIpQQilRZASyWSyCF85adj/HY1w5HAH3r7Ig8mfJygf49HA7pMUfvsulJkNhLt+H05/Vj4KxogqcmbjBnb6CIdWkPcV1n5rMFP8AYwOqSX+JXSyIKP8A/E+C/wB2P0j/AL0GafBf7sT+sk+9XlRZBS25rMEH9j/azfxr7NzaYKP7CztdIfe5W9EFYjyAwdu6gh7QT7yu9T5LYdH4lFTj9Uw+8L2LIg+ENHEzYyNjB5LQPcF9rLkosgIllKCESyICKUQQgUogIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiAiIgIiICIiD/2Q=="
                            alt="Honda">
                    </div>
                    <span class="brand-name">Honda</span>
                </a>
                <a href="#" class="brand-item">
                    <div class="brand-logo">
                        <img src="data:image/jpeg;base64,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"
                            alt="Maruti Suzuki">
                    </div>
                    <span class="brand-name">Maruti</span>
                </a>
                <a href="#" class="brand-item">
                    <div class="brand-logo">
                        <img src="data:image/png;base64,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"
                            alt="Kia">
                    </div>
                    <span class="brand-name">Kia</span>
                </a>
                <a href="#" class="brand-item">
                    <div class="brand-logo">
                        <img src="data:image/png;base64,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"
                            alt="MG">
                    </div>
                    <span class="brand-name">MG</span>
                </a>
            </div>
        </div>
    </section>

    
    <section class="featured">
        <div class="featured-container">
            <div class="section-header">
                <div class="section-header-content">
                    <h2>Most Searched Cars</h2>
                    <p>Explore the most popular cars in India with best-in-class features and prices.</p>
                </div>
                <a href="#" class="view-all-link">
                    View All Cars
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <div class="cars-grid">
                
                <div class="car-card">
                    <div class="car-image">
                        <img src="data:image/jpeg;base64,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"
                            alt="Toyota Camry">
                        <span class="car-badge">New Launch</span>
                        <button class="like-btn" onclick="this.classList.toggle('liked')">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div class="car-info">
                        <h3 class="car-title">Toyota Camry</h3>
                        <p class="car-price">₹25 - 35 Lakh</p>
                        <div class="car-tags">
                            <span class="car-tag">Sedan</span>
                            <span class="car-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7"></path>
                                    <path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3">
                                    </path>
                                </svg>Petrol</span>
                        </div>
                        <div class="car-specs">
                            <div class="car-spec">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M12 6v6l3 3"></path>
                                </svg>
                                12-15 km/l
                            </div>
                            <div class="car-spec">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="4" y="5" width="16" height="16" rx="2"></rect>
                                    <path d="M16 3v4"></path>
                                    <path d="M8 3v4"></path>
                                    <path d="M4 11h16"></path>
                                </svg>
                                2024
                            </div>
                        </div>
                        <button class="car-btn">View Details</button>
                    </div>
                </div>

                <div class="car-card">
                    <div class="car-image">
                        <img src="data:image/jpeg;base64,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"
                            alt="Honda City">
                        <button class="like-btn" onclick="this.classList.toggle('liked')">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div class="car-info">
                        <h3 class="car-title">Honda City</h3>
                        <p class="car-price">₹12 - 18 Lakh</p>
                        <div class="car-tags">
                            <span class="car-tag">Sedan</span>
                            <span class="car-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7"></path>
                                    <path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3">
                                    </path>
                                </svg>Petrol</span>
                        </div>
                        <div class="car-specs">
                            <div class="car-spec">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M12 6v6l3 3"></path>
                                </svg>
                                17-21 km/l
                            </div>
                            <div class="car-spec">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="4" y="5" width="16" height="16" rx="2"></rect>
                                    <path d="M16 3v4"></path>
                                    <path d="M8 3v4"></path>
                                    <path d="M4 11h16"></path>
                                </svg>
                                2023
                            </div>
                        </div>
                        <button class="car-btn">View Details</button>
                    </div>
                </div>

                <div class="car-card">
                    <div class="car-image">
                        <img src="data:image/jpeg;base64,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"
                            alt="Mahindra Scorpio">
                        <span class="car-badge">Popular</span>
                        <button class="like-btn" onclick="this.classList.toggle('liked')">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div class="car-info">
                        <h3 class="car-title">Mahindra Scorpio</h3>
                        <p class="car-price">₹13 - 18 Lakh</p>
                        <div class="car-tags">
                            <span class="car-tag">SUV</span>
                            <span class="car-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7"></path>
                                    <path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3">
                                    </path>
                                </svg>Diesel</span>
                        </div>
                        <div class="car-specs">
                            <div class="car-spec">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M12 6v6l3 3"></path>
                                </svg>
                                15 km/l
                            </div>
                            <div class="car-spec">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="4" y="5" width="16" height="16" rx="2"></rect>
                                    <path d="M16 3v4"></path>
                                    <path d="M8 3v4"></path>
                                    <path d="M4 11h16"></path>
                                </svg>
                                2024
                            </div>
                        </div>
                        <button class="car-btn">View Details</button>
                    </div>
                </div>

                <div class="car-card">
                    <div class="car-image">
                        <img src="data:image/jpeg;base64,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"
                            alt="Tata Nexon">
                        <button class="like-btn" onclick="this.classList.toggle('liked')">
                            <svg viewBox="0 0 24 24" stroke-width="2">
                                <path
                                    d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div class="car-info">
                        <h3 class="car-title">Tata Nexon</h3>
                        <p class="car-price">₹8 - 15 Lakh</p>
                        <div class="car-tags">
                            <span class="car-tag">SUV</span>
                            <span class="car-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7"></path>
                                    <path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3">
                                    </path>
                                </svg>Petrol</span>
                        </div>
                        <div class="car-specs">
                            <div class="car-spec">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9"></circle>
                                    <path d="M12 6v6l3 3"></path>
                                </svg>
                                17 km/l
                            </div>
                            <div class="car-spec">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="4" y="5" width="16" height="16" rx="2"></rect>
                                    <path d="M16 3v4"></path>
                                    <path d="M8 3v4"></path>
                                    <path d="M4 11h16"></path>
                                </svg>
                                2023
                            </div>
                        </div>
                        <button class="car-btn">View Details</button>
                    </div>
                </div>

                <?php
                if ($cars && $cars->num_rows > 0) {
                    $cars->data_seek(0);
                    while ($car = $cars->fetch_assoc()) {
                        $name = htmlspecialchars($car['name']);
                        $price = htmlspecialchars($car['price']);
                        $image = htmlspecialchars($car['image_path']);
                        $badge = isset($car['badge']) && $car['badge'] ? htmlspecialchars($car['badge']) : '';
                        $body_type = isset($car['body_type']) ? htmlspecialchars($car['body_type']) : 'Car';
                        $fuel_type = isset($car['fuel_type']) ? htmlspecialchars($car['fuel_type']) : 'Petrol';
                        $mileage = isset($car['mileage']) ? htmlspecialchars($car['mileage']) : 'N/A';
                        $year = isset($car['year']) ? htmlspecialchars($car['year']) : '2024';
                        ?>
                        <div class="car-card">
                            <div class="car-image">
                                <img src="<?php echo $image; ?>" alt="<?php echo $name; ?>">
                                <?php if ($badge): ?><span class="car-badge"><?php echo $badge; ?></span><?php endif; ?>
                                <button class="like-btn" onclick="this.classList.toggle('liked')">
                                    <svg viewBox="0 0 24 24" stroke-width="2">
                                        <path
                                            d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <div class="car-info">
                                <h3 class="car-title"><?php echo $name; ?></h3>
                                <p class="car-price"><?php echo $price; ?></p>
                                <div class="car-tags">
                                    <span class="car-tag"><?php echo $body_type; ?></span>
                                    <span class="car-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M3 12h1m8 -9v1m8 8h1m-15.4 -6.4l.7 .7m12.1 -.7l-.7 .7"></path>
                                            <path d="M9 16a5 5 0 1 1 6 0a3.5 3.5 0 0 0 -1 3a2 2 0 0 1 -4 0a3.5 3.5 0 0 0 -1 -3">
                                            </path>
                                        </svg><?php echo $fuel_type; ?></span>
                                </div>
                                <div class="car-specs">
                                    <div class="car-spec">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="9"></circle>
                                            <path d="M12 6v6l3 3"></path>
                                        </svg>
                                        <?php echo $mileage; ?>
                                    </div>
                                    <div class="car-spec">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="4" y="5" width="16" height="16" rx="2"></rect>
                                            <path d="M16 3v4"></path>
                                            <path d="M8 3v4"></path>
                                            <path d="M4 11h16"></path>
                                        </svg>
                                        <?php echo $year; ?>
                                    </div>
                                </div>
                                <button class="car-btn">View Details</button>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<div style="grid-column: 1/-1; text-align: center; padding: 40px;"><p>No cars available at the moment.</p></div>';
                }
                ?>
            </div>
        </div>
    </section>

   
    <section class="quote-section">
        <div class="quote-container">
            <div class="quote-content">
                <h2>Get a Free Quote<br><span>in 2 Minutes</span></h2>
                <p>Fill in your details and get instant price quotes from verified dealers near you.</p>

                <div class="benefits">
                    <div class="benefit">
                        <div class="benefit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </div>
                        <span>100% Verified Dealers</span>
                    </div>
                    <div class="benefit">
                        <div class="benefit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                        </div>
                        <span>Quick Response</span>
                    </div>
                    <div class="benefit">
                        <div class="benefit-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2">
                                <circle cx="12" cy="8" r="7"></circle>
                                <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                            </svg>
                        </div>
                        <span>Best Price Guarantee</span>
                    </div>
                </div>
            </div>

            <div class="quote-form-box">
                <h3>Get Price Quote</h3>

                <form id="inquiryForm" action="submit_inquiry.php" method="POST">
                    <div class="form-row">
                        <input type="text" name="name" class="form-input" placeholder="Full Name" required>
                        <input type="email" name="email" class="form-input" placeholder="Email Address" required>
                    </div>
                    <div class="form-group">
                        <input type="tel" name="phone" class="form-input" placeholder="Mobile Number" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="address" class="form-input" placeholder="Address" required>
                    </div>

                    <div class="checkbox-group">
                        <p>Select Car Type</p>
                        <div class="checkbox-options">
                            <label class="checkbox-label">
                                <input type="checkbox" name="selected_cars[]" value="hatchback">
                                Hatchback
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="selected_cars[]" value="sedan">
                                Sedan
                            </label>
                            <label class="checkbox-label">
                                <input type="checkbox" name="selected_cars[]" value="suv">
                                SUV
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"></line>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"></polygon>
                        </svg>
                        Get Free Quote
                    </button>

                    <p class="form-note">By submitting, you agree to our Terms & Conditions</p>
                    <div id="formMessage"></div>
                </form>
            </div>
        </div>
    </section>

   
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <a href="#" class="logo">
                        <div class="logo-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                <path d="M5 17h-2v-6l2 -5h9l4 5h1a2 2 0 0 1 2 2v4h-2m-4 0h-6m-6 -6h15m-6 0v-5"></path>
                            </svg>
                        </div>
                        <span class="logo-text">Car<span>Dekho</span></span>
                    </a>
                    <p><?php echo $footer['brand_description'] ?? "India's most trusted platform to buy and sell cars. Find your dream car with CarDekho."; ?>
                    </p>

                    <div class="footer-contact">
                        <?php
                        $contact_items = json_decode($footer['contact_items'] ?? '["support@cardekho.com","+91 9876543210","Jaipur, Rajasthan, India"]', true);
                        ?>
                        <div class="footer-contact-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                </path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                            <?php echo htmlspecialchars($contact_items[0] ?? 'support@cardekho.com'); ?>
                        </div>
                        <div class="footer-contact-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                </path>
                            </svg>
                            <?php echo htmlspecialchars($contact_items[1] ?? '+91 9876543210'); ?>
                        </div>
                        <div class="footer-contact-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <?php echo htmlspecialchars($contact_items[2] ?? 'Jaipur, Rajasthan, India'); ?>
                        </div>
                    </div>
                </div>

                <div class="footer-column">
                    <h4>New Cars</h4>
                    <ul>
                        <li><a href="#">By Brand</a></li>
                        <li><a href="#">By Budget</a></li>
                        <li><a href="#">By Body Type</a></li>
                        <li><a href="#">Upcoming Cars</a></li>
                        <li><a href="#">Latest Cars</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4>Used Cars</h4>
                    <ul>
                        <li><a href="#">Find Used Cars</a></li>
                        <li><a href="#">Sell Your Car</a></li>
                        <li><a href="#">Car Valuation</a></li>
                        <li><a href="#">Certified Cars</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Contact</a></li>
                        <li><a href="#">Press</a></li>
                        <li><a href="#">Blog</a></li>
                    </ul>
                </div>

                <div class="footer-column">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <p>© 2026 CarDekho. All rights reserved.</p>
                <div class="social-links">
                    <a href="#" class="social-link">
                        <svg viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                    <a href="#" class="social-link">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                            </path>
                        </svg>
                    </a>
                    <a href="#" class="social-link">
                        <svg viewBox="0 0 24 24">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                    <a href="#" class="social-link">
                        <svg viewBox="0 0 24 24">
                            <path
                                d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z">
                            </path>
                            <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('inquiryForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const messageDiv = document.getElementById('formMessage');

            fetch('submit_inquiry.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        messageDiv.innerHTML = '<p style="color: green; margin-top: 10px;">' + data.message + '</p>';
                        this.reset();
                    } else {
                        messageDiv.innerHTML = '<p style="color: red; margin-top: 10px;">' + data.message + '</p>';
                    }
                })
                .catch(error => {
                    messageDiv.innerHTML = '<p style="color: red; margin-top: 10px;">An error occurred. Please try again.</p>';
                });
        });

        
        document.querySelector('.mobile-menu-btn').addEventListener('click', function () {
            document.querySelector('.nav').classList.toggle('active');
        });

      
        document.getElementById('searchBtn').addEventListener('click', function () {
            const brand = document.getElementById('brandSelect').value.toLowerCase();
            const budget = document.getElementById('budgetSelect').value;
            const fuel = document.getElementById('fuelSelect').value.toLowerCase();

            const carCards = document.querySelectorAll('.car-card');

            carCards.forEach(card => {
                const title = card.querySelector('.car-title').textContent.toLowerCase();
                const priceText = card.querySelector('.car-price').textContent;
                const fuelTags = Array.from(card.querySelectorAll('.car-tag')).map(tag => tag.textContent.toLowerCase());

                let show = true;


                if (brand && !title.includes(brand)) {
                    show = false;
                }


                if (fuel && !fuelTags.includes(fuel)) {
                    show = false;
                }


                if (budget) {
                    const priceMatch = priceText.match(/₹([\d.]+)\s*-\s*([\d.]+)\s*Lakh/);
                    if (priceMatch) {
                        const minPrice = parseFloat(priceMatch[1]);
                        const maxPrice = parseFloat(priceMatch[2]);

                        if (budget === '5' && maxPrice > 5) show = false;
                        else if (budget === '10' && (minPrice > 10 || maxPrice < 5)) show = false;
                        else if (budget === '20' && (minPrice > 20 || maxPrice < 10)) show = false;
                        else if (budget === '50' && (minPrice > 50 || maxPrice < 20)) show = false;
                        else if (budget === 'above' && maxPrice <= 50) show = false;
                    }
                }

                card.style.display = show ? 'block' : 'none';
            });


            document.querySelector('.featured').scrollIntoView({ behavior: 'smooth' });
        });
    </script>
</body>

</html>
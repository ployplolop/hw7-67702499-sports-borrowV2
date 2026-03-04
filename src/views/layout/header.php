<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sports Equipment Borrowing System</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Noto+Sans+Thai:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Design System CSS -->
    <link rel="stylesheet" href="views/assets/css/design-system.css">
    <link rel="stylesheet" href="views/assets/css/layout.css">
    <link rel="stylesheet" href="views/assets/css/dashboard.css">
    <link rel="stylesheet" href="views/assets/css/tables.css">
    <link rel="stylesheet" href="views/assets/css/modal.css">

    <!-- JS -->
    <script src="views/assets/js/toast.js" defer></script>
    <script src="views/assets/js/modal.js" defer></script>
    <script src="views/assets/js/charts.js" defer></script>
    <script src="views/assets/js/dashboard.js" defer></script>
</head>
<body class="app-layout">

    <!-- Sidebar -->
    <?php require __DIR__ . '/../components/sidebar.php'; ?>

    <!-- Main Area -->
    <div class="main-area">

        <!-- Top Navbar -->
        <?php require __DIR__ . '/../components/navbar.php'; ?>

        <!-- Content -->
        <main class="content-area">

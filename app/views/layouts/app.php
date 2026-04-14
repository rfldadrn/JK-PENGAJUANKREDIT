<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Pengajuan Kredit BRI' ?></title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --bri-blue: #0066CC;
            --bri-dark: #003d7a;
            --bri-light: #e8f4ff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--bri-blue) !important;
        }

        .btn-primary {
            background-color: var(--bri-blue);
            border-color: var(--bri-blue);
        }

        .btn-primary:hover {
            background-color: var(--bri-dark);
            border-color: var(--bri-dark);
        }

        .text-primary {
            color: var(--bri-blue) !important;
        }

        .bg-primary {
            background-color: var(--bri-blue) !important;
        }
    </style>

    <?= $additionalCSS ?? '' ?>
</head>
<body>
    <?= $content ?? '' ?>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?= $additionalJS ?? '' ?>
</body>
</html>

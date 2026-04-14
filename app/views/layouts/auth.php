<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Sistem Pengajuan Kredit BRI' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            --bri-blue: #0066CC;
            --bri-dark: #003d7a;
            --bri-light: #e8f4ff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--bri-blue) 0%, var(--bri-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .auth-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .auth-header {
            background: linear-gradient(135deg, var(--bri-blue), var(--bri-dark));
            color: white;
            padding: 2rem;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--bri-blue);
            border-color: var(--bri-blue);
            padding: 0.75rem;
            font-weight: 500;
        }

        .btn-primary:hover {
            background-color: var(--bri-dark);
            border-color: var(--bri-dark);
        }

        .form-control:focus {
            border-color: var(--bri-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 102, 204, 0.25);
        }

        a {
            color: var(--bri-blue);
            text-decoration: none;
        }

        a:hover {
            color: var(--bri-dark);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <?= $content ?? '' ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

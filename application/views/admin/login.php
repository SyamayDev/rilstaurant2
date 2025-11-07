<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Login Admin | Rilstaurant</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('<?= base_url("assets/img/batik.webp") ?>') repeat center fixed;
            background-size: 200px auto;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-login {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            color: #fff;
        }

        .card-login h4 {
            color: #fff;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.4);
        }

        .form-control {
            border-radius: 10px;
        }

        .btn-danger {
            border-radius: 10px;
            width: 100%;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 90px;
            height: 90px;
            object-fit: cover;
            border-radius: 50%;
            background: #fff;
            padding: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        @media (max-width: 576px) {
            .card-login {
                margin: 0 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4 col-11">
                <div class="card card-login p-4 text-center">
                    <div class="logo-container">
                        <img src="<?= base_url('assets/img/logo.webp') ?>" alt="Rilstaurant Logo">
                    </div>
                    <h4 class="mb-3 fw-bold">Login Admin</h4>
                    <form method="post" action="<?= base_url('admin/login_action') ?>">
                        <div class="mb-3">
                            <input name="username" class="form-control text-center" placeholder="Username" required>
                        </div>
                        <div class="mb-3">
                            <input name="password" type="password" class="form-control text-center" placeholder="Password" required>
                        </div>
                        <button class="btn btn-danger btn-lg mt-2">Masuk</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if ($this->session->flashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Login',
            text: '<?= $this->session->flashdata('error') ?>',
            confirmButtonColor: '#dc3545'
        });
    </script>
    <?php endif; ?>
</body>

</html>

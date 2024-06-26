<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/6a8041b5d6.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./assets/style.css">
</head>

<body>
    <section class="container-xl" style="height:100vh;">
        <a href="./index.php" class="position-absolute top-0"><img src="./assets/img/logo-team-builder.png" alt="" style="width: 120px;"></a>
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-12 col-md-4">
                <h1 class="text-white text-center mb-4">Register</h1>
                <form method="post" action="./api/register.php">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input id="name" type="name" class="form-control" name="inputName">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="inputEmail" aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control" name="inputPassword">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

                <a href="./login.php" class='link-light'>I already have an account</a>

            </div>
    </section>
</body>

</html>
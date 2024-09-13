<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clonix Corp&nbsp;-&nbsp;Page de connexion</title>
    <style>
        body {
            color: #ffffff;
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('/images/image-fond-connexion.gif');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }


        .login-form {
            width: 320px;
            margin: 150px auto;
            padding: 20px;
            background: rgba(0, 0, 0, 0.7);
            box-shadow: 0px 8px 15px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
            
        }

        .login-form form {
            margin-bottom: 15px;
        }

        .login-form h2 {
            margin-bottom: 30px;
            font-size: 24px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.3);
            color: #ffffff;
            outline: none;
        }

        .form-check-label {
            color: #ffffff;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .form-check-label:hover {
            color: #f0f0f0;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background-color: #efa676;
            color: #ffffff;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="login-form" style="border-radius: 10px !important;">
        <?php if (isset($_GET["login_err"])) {
             $err = htmlspecialchars($_GET["login_err"]);
             switch ($err) {
                case "password":
        ?>
        <div class="alert alert-danger">
            <strong>Erreur</strong> mot de passe incorrect
        </div>
        <?php break;case "mail": ?>
        <div class="alert alert-danger">
            <strong>Erreur</strong> mail incorrect
        </div>
        <?php break;case "already": ?>
        <div class="alert alert-danger">
            <strong>Erreur</strong> compte non existant
        </div>
            <?php break;}
         } ?>

        <form action="./connexion.php" method="post">
            <img class='profilePictureClass' src='images/logo-hopital-global.png' width='30%' height='30%' style="padding-bottom: 10px;"/>
            <h2>Page de connexion</h2>
            <div class="form-group">
                <input type="email" name="mail" class="form-control" placeholder="Mail" required="required"
                    autocomplete="off">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Mot de passe"
                    required="required" autocomplete="off">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block" style="background-color: #0c6d98 !important">Connexion</button>
            </div>
        </form>

        
    </div>

</body>
</html>

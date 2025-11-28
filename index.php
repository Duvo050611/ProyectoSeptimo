<!DOCTYPE html>
<?php include "conexionbd.php"; ?>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>INEO Metepec - Sistema de Acceso</title>
    <link rel="icon" href="imagenes/SIF.PNG">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Raleway', sans-serif;
            background: #0a0a0a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background:
                    radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.03) 0%, transparent 50%),
                    radial-gradient(circle at 80% 80%, rgba(255, 255, 255, 0.03) 0%, transparent 50%);
            pointer-events: none;
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
        }

        .login-box {
            background: rgba(20, 20, 20, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 60px 50px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
        }

        .header-title {
            margin-bottom: 50px;
        }

        .header-title h1 {
            color: #ffffff;
            font-size: 32px;
            font-weight: 300;
            letter-spacing: 12px;
            text-transform: uppercase;
            margin-bottom: 8px;
            text-shadow: 0 0 20px rgba(255, 255, 255, 0.3);
        }

        .header-title .subtitle {
            color: #999999;
            font-size: 13px;
            font-weight: 400;
            letter-spacing: 6px;
            text-transform: uppercase;
        }

        .logo-circle {
            width: 100px;
            height: 100px;
            margin: 0 auto 50px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
        }

        .logo-circle img {
            max-width: 70%;
            max-height: 70%;
            opacity: 0.9;
        }

        .logo-circle .letter {
            color: #ffffff;
            font-size: 48px;
            font-weight: 300;
            letter-spacing: 2px;
        }

        .alert {
            padding: 12px 16px;
            margin-bottom: 30px;
            font-size: 13px;
            border-left: 3px solid #ff4444;
            background: rgba(255, 68, 68, 0.1);
            border-radius: 4px;
            text-align: left;
        }

        .alert-text {
            color: #ff6666;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-text i {
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 30px;
            position: relative;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 12px 0;
            transition: all 0.3s ease;
        }

        .input-wrapper:hover {
            border-bottom-color: rgba(255, 255, 255, 0.4);
        }

        .input-wrapper.focused {
            border-bottom-color: rgba(255, 255, 255, 0.8);
            box-shadow: 0 1px 0 0 rgba(255, 255, 255, 0.3);
        }

        .input-wrapper i {
            color: rgba(255, 255, 255, 0.5);
            font-size: 18px;
            width: 40px;
            text-align: center;
        }

        .form-group input {
            flex: 1;
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 15px;
            font-family: 'Raleway', sans-serif;
            font-weight: 300;
            letter-spacing: 0.5px;
            outline: none;
            padding: 4px 0;
        }

        .form-group input::placeholder {
            color: rgba(255, 255, 255, 0.3);
            font-weight: 300;
        }

        .form-group input:-webkit-autofill,
        .form-group input:-webkit-autofill:hover,
        .form-group input:-webkit-autofill:focus {
            -webkit-text-fill-color: #ffffff;
            -webkit-box-shadow: 0 0 0px 1000px #0a0a0a inset;
            transition: background-color 5000s ease-in-out 0s;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: transparent;
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 4px;
            font-size: 14px;
            font-weight: 400;
            font-family: 'Raleway', sans-serif;
            letter-spacing: 3px;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transition: left 0.3s ease;
        }

        .btn-login:hover {
            border-color: rgba(255, 255, 255, 0.8);
            box-shadow:
                    0 0 10px rgba(255, 255, 255, 0.3),
                    inset 0 0 10px rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .footer-link {
            margin-top: 30px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.4);
            letter-spacing: 1px;
        }

        .footer-link a {
            color: rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: all 0.3s ease;
            border-bottom: 1px solid transparent;
        }

        .footer-link a:hover {
            color: #ffffff;
            border-bottom-color: rgba(255, 255, 255, 0.6);
        }

        .footer-info {
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-info p {
            color: rgba(255, 255, 255, 0.3);
            font-size: 11px;
            letter-spacing: 2px;
            font-weight: 300;
        }

        /* Responsive */
        @media screen and (max-width: 480px) {
            .login-box {
                padding: 50px 35px;
            }

            .header-title h1 {
                font-size: 24px;
                letter-spacing: 8px;
            }

            .header-title .subtitle {
                font-size: 11px;
                letter-spacing: 4px;
            }

            .logo-circle {
                width: 80px;
                height: 80px;
                margin-bottom: 40px;
            }

            .logo-circle .letter {
                font-size: 36px;
            }
        }

        /* Animación de entrada */
        .login-container {
            animation: fadeInScale 0.6s ease-out;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes glow {
            0%, 100% {
                box-shadow: 0 0 5px rgba(255, 255, 255, 0.1);
            }
            50% {
                box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
            }
        }

        .logo-circle {
            animation: glow 3s ease-in-out infinite;
        }
    </style>
</head>
<body oncontextmenu="return false;">

<div class="login-container">
    <div class="login-box">
        <!-- Título -->
        <div class="header-title">
            <h1>DABOSOFT</h1>
            <p class="subtitle">Sistema Médico</p>
        </div>

        <!-- Logo Circular -->
        <div class="logo-circle">
            <?php
            $resultado = $conexion->query("SELECT * FROM img_sistema ORDER BY id_simg DESC LIMIT 1") or die($conexion->error);
            if($f = mysqli_fetch_array($resultado)){
                ?>
                <img src="imagenes/SIF.PNG" alt="Logo">
                <?php
            } else {
                ?>
                <span class="letter">I</span>
                <?php
            }
            ?>
        </div>

        <!-- Alerta de error -->
        <?php
        if(isset($_GET['error'])){
            echo '<div class="alert">
                        <div class="alert-text">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>' . htmlspecialchars($_GET['error']) . '</span>
                        </div>
                      </div>';
        }
        ?>

        <!-- Formulario -->
        <form action="revisar_login.php" method="POST">
            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-envelope"></i>
                    <input
                            type="text"
                            name="usuario"
                            placeholder="your@email.com"
                            required
                            autocomplete="username"
                            onfocus="this.parentElement.classList.add('focused')"
                            onblur="this.parentElement.classList.remove('focused')"
                    >
                </div>
            </div>

            <div class="form-group">
                <div class="input-wrapper">
                    <i class="fas fa-lock"></i>
                    <input
                            type="password"
                            name="pass"
                            placeholder="••••••••••"
                            required
                            autocomplete="current-password"
                            onfocus="this.parentElement.classList.add('focused')"
                            onblur="this.parentElement.classList.remove('focused')"
                    >
                </div>
            </div>

            <button type="submit" class="btn-login">
                Iniciar Sesión
            </button>

            <div class="footer-link">
                ¿Olvidaste tu contraseña? <a href="#">Recupérala</a>
            </div>
        </form>

        <!-- Footer -->
        <div class="footer-info">
            <p>INEO METEPEC © <?php echo date('Y'); ?></p>
        </div>
    </div>
</div>

<script type="text/javascript" src="js/main.js"></script>
</body>
</html>
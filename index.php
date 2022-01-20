<?php

    require_once './app.php';

    session_start();

    //Para validar que exista el id_user y el rol_id -> sin embargo, no se valida que tipo de role es...
    if (array_key_exists('user_id', $_SESSION) || array_key_exists('role_id', $_SESSION)) {
        header('Location: ./dashboard/');
        die;
    }

    if($_SERVER['REQUEST_METHOD']=='POST'){

            $error = false;

            $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
            $pass = isset($_REQUEST['password']) ? $_REQUEST['password'] : '';

            $user = new User();
            $user->setEmail($email);
            $user->setPassword($pass);

            $resultEmail = $user->validateEmail();

            if(!empty($resultEmail)){

                if(password_verify($pass, $resultEmail['password']) && $resultEmail['status']==1){

                    session_start();
                    $_SESSION['user_id'] = $resultEmail['user_id'];
                    $_SESSION['role_id'] = $resultEmail['role_id'];

                    header("Location: ./dashboard/");
                }
                else{
                    $error = true;
                }

            }
            else{
                $error = true;
            }

        }

?>

<!DOCTYPE html>
<html lang="es" class="overflow-y-scroll">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión - Fablab System</title>
    <link href="<?= constant('URL')?>assets/css/tailwind.output.css" rel="stylesheet">
    <link rel="icon" href="<?= constant('URL')?>assets/img/fab.ico" type="image/x-icon">
</head>
<body>
    <div class="absolute w-screen h-screen flex">
        <div class="hidden lg:block w-5/12 h-full">
          <img
            src="./assets/img/header.jpg"
            class="w-full h-full object-cover"
          />
        </div>
        <div class="w-full lg:w-7/12 overflow-scroll py-24 relative">
          <form class="w-5/6 sm:w-2/3 mx-auto text-center" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <img
              src="./assets/img/fab.png"
              class="h-2/4 w-1/3 block mx-auto"
            />
            <div class="mt-10">
              <h2 class="text-3xl font-bold text-gray-800">Bienvenido de nuevo</h2>
              <p class="mt-3 text-gray-800">
                ¿Nuevo usuario? <a href="#" class="text-blue-500">Regístrate ahora</a>
              </p>
            </div>
            <div class="mt-12">
              <div class="my-6">
                <div class="">
                  <div class="">
                    <input
                      type="email"
                      name="email"
                      class="w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-blue-500 focus:ring-0"
                      placeholder="Correo electrónico"
                      value="<?= isset($_REQUEST['email']) ? $_REQUEST['email'] : '';?>"
                      autocomplete="email"
                      required
                    />
                  </div>
                </div>
              </div>
              <div class="my-6">
                <div class="">
                  <div class="">
                    <input
                      type="password"
                      name="password"
                      class="w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-blue-500 focus:ring-0"
                      placeholder="Contraseña"
                      autocomplete="current-password"
                      required
                    />
                  </div>
                </div>
              </div>
                <?php if (isset($error)): ?>

                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Acceso inválido.</strong>
                    <span class="block sm:inline"> Por favor, inténtelo otra vez.</span>
                    <span onclick="remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
    <svg class="fill-current h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
  </span>
                    <?php endif; ?>
                </div>
              <div class="my-6">
                <button
                  class="inline-block rounded-sm font-medium border border-solid cursor-pointer text-center text-base py-3 px-6 text-white bg-blue-500 border-blue-500 hover:bg-blue-600 hover:border-blue-600 w-full"
                  type="submit"
                >
                Iniciar sesión
                </button>
              </div>
              <div class="text-right">
                <a href="#" class="text-blue-500">¿Olvidaste tu contraseña?</a>
              </div>
              <div class="mt-6 border-t border-b border-gray-300">
                <div class="my-6">
                  <div class="w-full flex items-center">
                    <input
                      type="checkbox"
                      name="rememberMe"
                      class="w-6 h-6 border border-gray-300 rounded-sm outline-none cursor-pointer text-blue-500 shadow-sm focus:border-blue-500 focus:ring focus:ring-offset-0 focus:ring-blue-500 focus:ring-opacity-50"
                      checked=""
                    /><label class="ml-2 text-sm" for="rememberMe"
                      >Recuerda este dispositivo
                      </label>
                  </div>
                </div>
              </div>
              <p class="text-sm mt-6 text-left">
                Al continuar, acepta nuestros
                <a href="#" class="text-blue-500">Términos de uso</a> y
                <a href="#" class="text-blue-500">Política de privacidad</a>.
              </p>
            </div>
          </form>
        </div>

        <script>
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }

            function remove() {
                    document.querySelector('[role="alert"]').classList.add('hidden');
            }
        </script>
</body>
</html>
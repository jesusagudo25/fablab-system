<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Fablab System</title>
    <link rel="stylesheet" href="../assets/css/tailwind.output.css">
</head>
<body>
    <div class="container mx-auto">
        <div class="w-full flex justify-between item-center py-6">
            <div class="h-10 w-40"><img src="../assets/img/fab.png" class="h-full w-full"></div>
            
            <a href="../" class="no-underline font-medium text-blue-400">Regresar</a></div>
        <div class="mt-8">
            <div class=" w-full px-4 sm:px-8">
                <div class="h-2px w-full bg-gray-300 relative">
                    <div class="absolute top-1/2 left-0 h-1 transform -translate-y-1/2 bg-blue-400 transition-width ease-in-out duration-500" style="width: 50%;">
                        <div class="w-3 h-3 bg-blue-800 rounded-full absolute right-0 top-1/2 transform translate-x-1/2 -translate-y-1/2"></div>
                    </div>
                </div>
                <div class="mt-3 relative hidden sm:block">
                    <div class="absolute" style="left: 0%;"><span class="inline-block transform -translate-x-1/2 text-sm font-medium text-blue-400">Cuenta</span></div>
                    <div class="absolute" style="left: 50%;"><span class="inline-block transform -translate-x-1/2 text-sm font-medium">Detalles</span></div>
                    <div class="absolute" style="left: 100%;"><span class="inline-block transform -translate-x-1/2 text-sm">Entrar</span></div>
                </div>
            </div>
        </div>
        <div class="my-12 pb-12 w-full max-w-screen-md mx-auto">
            <h1 class="text-4xl font-bold text-center p-5">Detalles personales</h1>
            <form class="mt-2 w-full">
                <div class=" my-6 grid grid-cols-1 gap-6 sm:mb-0 sm:gap-6 sm:grid-cols-2">
                    <div class="">
                        <label class="text-sm text-gray-600  false" for="firstName">Nombre</label>
                        <div class="">
                            <input type="text" name="firstName" class=" w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-blue-400 " placeholder="" value="">
                        </div>
                    </div>
                    <div class="">
                        <label class="text-sm text-gray-600  false" for="lastName">Apellido</label>
                        <div class="">
                            <input type="text" name="lastName" class=" w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-blue-400 " placeholder="" value="">
                        </div>
                    </div>
                </div>
                <div class=" my-6">
                    <div>
                        <label class="text-sm text-gray-600  false" for="">Correo</label>
                        <div class="">
                            <input type="text" name="" class=" w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-blue-400 " placeholder="" value="">
                        </div>
                    </div>
                </div>
                <div class=" my-6">
                    <div>
                        <label class="text-sm text-gray-600  false" for="">Contraseña</label>
                        <div class="">
                            <input type="password" name="" class=" w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-blue-400 " placeholder="" value="">
                        </div>
                    </div>
                </div>
                <div class=" my-6">
                    <div class="">
                        <label class="text-sm text-gray-600  false" for="resume">Imagen de perfil</label>
                        <div>
                            <input type="file" name="resume" class=" w-full border border-gray-300 rounded-sm px-4 py-3 outline-none transition-colors duration-150 ease-in-out focus:border-blue-400 " placeholder="" value="">
                        </div>
                    </div>
                </div>
                <div class=" my-6">
                    <input type="submit" class="inline-block rounded-sm font-medium border border-solid cursor-pointer text-center text-base py-3 px-6 text-white bg-blue-400 border-blue-400 hover:bg-blue-600 hover:border-blue-600 w-full" value="Registrarse">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
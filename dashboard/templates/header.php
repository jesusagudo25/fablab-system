<nav id="header" class="bg-white fixed w-full z-10 top-0 shadow">

        <div class="w-10/12 container mx-auto flex flex-wrap items-center mt-0 pt-3 pb-3 md:pb-0">

            <div class="w-1/2 pl-2 md:pl-0">
                <a class="text-gray-900 text-base xl:text-xl no-underline hover:no-underline font-bold" href="#">
                    <img src="<?= constant('URL')?>assets/img/fab.png" alt="" title="" class="w-28">
                </a>
            </div>

            <div class="w-1/2 pr-0">
                <div class="flex relative inline-block float-right">

                    <div class="relative text-sm">
                        <button id="userButton" class="flex items-center focus:outline-none mr-3">
                            <img class="w-8 h-8 rounded-full mr-4" src="<?= constant('URL')?>assets/img/avatar.svg" alt="Avatar of User"> <span class="hidden md:inline-block">Hola, Usuario </span>
                            <svg class="pl-2 h-2" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 129 129" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 129 129">
                                <g>
                                    <path d="m121.3,34.6c-1.6-1.6-4.2-1.6-5.8,0l-51,51.1-51.1-51.1c-1.6-1.6-4.2-1.6-5.8,0-1.6,1.6-1.6,4.2 0,5.8l53.9,53.9c0.8,0.8 1.8,1.2 2.9,1.2 1,0 2.1-0.4 2.9-1.2l53.9-53.9c1.7-1.6 1.7-4.2 0.1-5.8z" />
                                </g>
                            </svg>
                        </button>
                        <div id="userMenu" class="bg-white rounded shadow-md mt-2 absolute mt-12 top-0 right-0 min-w-full overflow-auto z-30 invisible">
                            <ul class="list-reset">
                                <li><a href="#" class="px-4 py-2 block text-gray-900 hover:bg-gray-400 no-underline hover:no-underline">Mi cuenta</a></li>
                                <li><a href="#" class="px-4 py-2 block text-gray-900 hover:bg-gray-400 no-underline hover:no-underline">Mis observaciones</a></li>
                                <li>
                                    <hr class="border-t mx-2 border-gray-400">
                                </li>
                                <li><a href="<?= constant('URL')?>dashboard/logout.php" class="px-4 py-2 block text-gray-900 hover:bg-gray-400 no-underline hover:no-underline">Salir</a></li>
                            </ul>
                        </div>
                    </div>


                    <div class="block lg:hidden pr-4">
                        <button id="nav-toggle" class="flex items-center px-3 py-2 border rounded text-gray-500 border-gray-600 hover:text-gray-900 hover:border-teal-500 appearance-none focus:outline-none">
                            <svg class="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <title>Menu</title>
                                <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" />
                            </svg>
                        </button>
                    </div>
                </div>

            </div>


            <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block mt-2 lg:mt-0 bg-white z-20" id="nav-content">
                <ul class="list-reset lg:flex flex-1 items-center px-4 md:px-0">
                    <li class="mr-6 my-2 md:my-0">
                        <a href="<?= constant('URL')?>dashboard/" class="block py-1 md:py-3 pl-1 align-middle no-underline text-gray-500 hover:text-gray-900 border-b-2 border-white <?= in_array( 'dashboard',$pagina) ? 'border-blue-500 text-gray-900' : 'hover:border-blue-500' ?>">
                            <i class="fas fa-home fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Inicio</span>
                        </a>
                    </li>
                    <li class="mr-6 my-2 md:my-0">
                        <a href="<?= constant('URL')?>dashboard/form/" class="block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white <?= in_array( 'form',$pagina) ? 'border-blue-500 text-gray-900' : 'hover:border-blue-500' ?>">
                            <i class="fas fa-tasks fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Formulario de entrada</span>
                        </a>
                    </li>
                    <li class="mr-6 my-2 md:my-0">
                        <a href="<?= constant('URL')?>dashboard/sales/" class="block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white <?= in_array( 'sales',$pagina) ? 'border-blue-500 text-gray-900' : 'hover:border-blue-500' ?>">
                            <i class="fas fa-shopping-cart fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Generar venta</span>
                        </a>
                    </li>
                    <li class="mr-6 my-2 md:my-0">
                        <a href="<?= constant('URL')?>dashboard/reports/" class="block py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white <?= in_array( 'reports',$pagina) ? 'border-blue-500 text-gray-900' : '' ?> hover:border-blue-500">
                            <i class="fas fa-chart-area fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Reportes</span>
                        </a>
                    </li>
                    <li class="mr-6 my-2 md:my-0 relative">
                        <button id="gestButton" class="w-full text-left py-1 md:py-3 pl-1 align-middle text-gray-500 no-underline hover:text-gray-900 border-b-2 border-white <?= in_array( 'gestionar',$pagina) ? 'border-blue-500 text-gray-900' : 'hover:border-blue-500' ?>">
                            <i class="fas fa-cogs fa-fw mr-3"></i><span class="pb-1 md:pb-0 text-sm">Gestionar <svg class="pl-2 h-2 inline-block" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 129 129" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 129 129">
                                <g>
                                    <path d="m121.3,34.6c-1.6-1.6-4.2-1.6-5.8,0l-51,51.1-51.1-51.1c-1.6-1.6-4.2-1.6-5.8,0-1.6,1.6-1.6,4.2 0,5.8l53.9,53.9c0.8,0.8 1.8,1.2 2.9,1.2 1,0 2.1-0.4 2.9-1.2l53.9-53.9c1.7-1.6 1.7-4.2 0.1-5.8z"></path>
                                </g>
                            </svg>
                            </span>
                        </button>
                        <div id="dropdown" class="bg-white rounded shadow-md mt-2 absolute mt-12 top-1 right-0 min-w-full overflow-auto z-30 invisible">
                            <ul class="list-reset text-sm">
                                <li><a href="#" class=" px-4 py-2 block text-gray-900 hover:bg-gray-400 no-underline hover:no-underline">Clientes</a></li>
                                <li><a href="<?= constant('URL')?>dashboard/observations/" class="px-4 py-2 block text-gray-900 hover:bg-gray-400 no-underline hover:no-underline">Observaciones</a></li>
                                <li><a href="#" class=" px-4 py-2 block text-gray-900 hover:bg-gray-400 no-underline hover:no-underline">Visitas</a></li>                                <li><a href="#" class=" px-4 py-2 block text-gray-900 hover:bg-gray-400 no-underline hover:no-underline">Ventas</a></li>
                            </ul>
                        </div>

                    </li>
                </ul>

                <div class="relative pull-right pl-4 pr-4 md:pr-0">
                    <input type="search" placeholder="En construcción..." class="w-full text-sm text-gray-800 transition focus:outline-none py-1 px-2 pl-10 rounded-md bg-gray-100 border-transparent focus:border-gray-500 focus:bg-white focus:ring-0">
                    <div class="absolute search-icon" style="top: 0.375rem;left: 1.75rem;">
                        <svg class="fill-current pointer-events-none text-gray-800 w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                        </svg>
                    </div>
                </div>

            </div>

        </div>
    </nav>
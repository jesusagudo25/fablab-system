<?php

    // Inicia las sesiones
    session_start();
    // Destruye cualquier sesión del usuario
    session_destroy();
    // Redirecciona a login.php
    header('Location: ../index.php');


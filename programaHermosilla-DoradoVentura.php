<?php
include_once("wordix.php");

/**************************************/
/*********** FUNCIONES ****************/
/**************************************/

/**
 * Inicializa una colección de palabras de 5 letras.
 * @return array
 */
function cargarColeccionPalabras() {
    $coleccionPalabras = [
        "MUJER", "QUESO", "FUEGO", "CASAS", "RASGO",
        "GATOS", "GOTAS", "HUEVO", "TINTO", "NAVES",
        "VERDE", "MELON", "YUYOS", "PIANO", "PISOS"
    ];
    return $coleccionPalabras;
}

/**
 * Muestra el resultado de una partida.
 * @param array $partida
 */
function mostrarResultadoPartida($partida) {
    echo "\n********** RESULTADO DE LA PARTIDA **********\n";
    echo "Palabra Wordix: " . $partida["palabraWordix"] . "\n";
    echo "Jugador: " . $partida["jugador"] . "\n";
    if ($partida["intentos"] > 0 && $partida["intentos"] < 7) {
        echo "Intentos: " . $partida["intentos"] . "\n";
        echo "Puntaje: " . $partida["puntaje"] . "\n";
        echo "Resultado: ¡Ganaste!\n";
    } else {
        echo "Resultado: No adivinaste la palabra.\n";
        echo "Puntaje: 0\n";
    }
    echo "*********************************************\n";
}


/**
 * Inicializa una colección de partidas con datos de ejemplo.
 * @return array
 */
function cargarPartidas() {
    $coleccionPartidas = [
        ["palabraWordix" => "MUJER", "jugador" => "majo", "intentos" => 3, "puntaje" => 14],
        ["palabraWordix" => "QUESO", "jugador" => "ana", "intentos" => 6, "puntaje" => 10],
        ["palabraWordix" => "FUEGO", "jugador" => "luis", "intentos" => 1, "puntaje" => 20],
        ["palabraWordix" => "CASAS", "jugador" => "majo", "intentos" => 2, "puntaje" => 18],
        ["palabraWordix" => "TINTO", "jugador" => "pedro", "intentos" => 0, "puntaje" => 0],
        ["palabraWordix" => "VERDE", "jugador" => "majo", "intentos" => 5, "puntaje" => 11],
        ["palabraWordix" => "PIANO", "jugador" => "ana", "intentos" => 4, "puntaje" => 12],
        ["palabraWordix" => "NAVES", "jugador" => "luis", "intentos" => 3, "puntaje" => 15],
        ["palabraWordix" => "RASGO", "jugador" => "pedro", "intentos" => 1, "puntaje" => 19],
        ["palabraWordix" => "HUEVO", "jugador" => "ana", "intentos" => 6, "puntaje" => 9]
    ];
    return $coleccionPartidas;
}

/**
 * Muestra el menú de opciones y retorna la opción seleccionada.
 * @return int
 */
function seleccionarOpcion() {
    echo "\nMENU DE OPCIONES:\n";
    echo "1) Jugar al Wordix con una palabra elegida\n";
    echo "2) Jugar al Wordix con una palabra aleatoria\n";
    echo "3) Mostrar una partida\n";
    echo "4) Mostrar la primera partida ganadora\n";
    echo "5) Mostrar resumen de jugador\n";
    echo "6) Mostrar listado de partidas ordenadas\n";
    echo "7) Agregar una palabra\n";
    echo "8) Salir\n";
    return solicitarNumeroEntre(1, 8);
}

/**
 * Solicita un número dentro de un rango válido.
 * @param int $min
 * @param int $max
 * @return int
 */
function solicitarNumeroEntre($min, $max) {
    echo "Ingrese un número entre $min y $max: ";
    $numero = trim(fgets(STDIN));
    while (!is_numeric($numero) || $numero < $min || $numero > $max) {
        echo "Número inválido. Ingrese un número entre $min y $max: ";
        $numero = trim(fgets(STDIN));
    }
    return (int)$numero;
}

/**
 * Agrega una palabra a la colección de palabras.
 * @param array $coleccionPalabras
 * @param string $palabra
 * @return array
 */
function agregarPalabra($coleccionPalabras, $palabra) {
    if (strlen($palabra) == 5 && ctype_alpha($palabra)) {
        $coleccionPalabras[] = strtoupper($palabra);
        echo "Palabra agregada correctamente.\n";
    } else {
        echo "La palabra debe tener 5 letras.\n";
    }
    return $coleccionPalabras;
}

/**
 * Retorna el índice de la primera partida ganada por un jugador.
 * @param array $coleccionPartidas
 * @param string $jugador
 * @return int
 */
function obtenerPrimeraPartidaGanada($coleccionPartidas, $jugador) {
    foreach ($coleccionPartidas as $indice => $partida) {
        if ($partida["jugador"] === $jugador && $partida["puntaje"] > 0) {
            return $indice;
        }
    }
    return -1;
}

/**
 * Genera el resumen de un jugador basado en las partidas.
 * @param array $coleccionPartidas
 * @param string $jugador
 * @return array
 */
function generarResumenJugador($coleccionPartidas, $jugador) {
    $resumen = [
        "jugador" => $jugador,
        "partidas" => 0,
        "victorias" => 0,
        "puntajeTotal" => 0,
        "intento1" => 0,
        "intento2" => 0,
        "intento3" => 0,
        "intento4" => 0,
        "intento5" => 0,
        "intento6" => 0
    ];

    foreach ($coleccionPartidas as $partida) {
        if ($partida["jugador"] === $jugador) {
            $resumen["partidas"]++;
            $resumen["puntajeTotal"] += $partida["puntaje"];
            if ($partida["puntaje"] > 0) {
                $resumen["victorias"]++;
                // Contador de intentos
                $intento = $partida["intentos"];
                if ($intento >= 1 && $intento <= 6) {
                    $resumen["intento" . $intento]++;
                }
            }
        }
    }

    // Calcular el porcentaje de victorias
    if ($resumen["partidas"] > 0) {
        $resumen["porcentajeVictorias"] = ($resumen["victorias"] / $resumen["partidas"]) * 100;
    } else {
        $resumen["porcentajeVictorias"] = 0;
    }

    return $resumen;
}

/**
 * Muestra el resumen de un jugador con estadísticas detalladas.
 * @param array $coleccionPartidas
 * @param string $jugador
 */
function mostrarResumenJugador($coleccionPartidas, $jugador) {
    $resumen = generarResumenJugador($coleccionPartidas, $jugador);

    echo "\n********** RESUMEN DEL JUGADOR **********\n";
    echo "Jugador: " . ucfirst($resumen["jugador"]) . "\n";
    echo "Partidas jugadas: " . $resumen["partidas"] . "\n";
    echo "Victorias: " . $resumen["victorias"] . "\n";
    echo "Puntaje total: " . $resumen["puntajeTotal"] . " puntos\n";
    echo "Porcentaje de victorias: " . number_format($resumen["porcentajeVictorias"], 2) . "%\n";
    echo "\nEstadísticas de intentos:\n";
    echo "Adivinadas en el intento 1: " . $resumen["intento1"] . "\n";
    echo "Adivinadas en el intento 2: " . $resumen["intento2"] . "\n";
    echo "Adivinadas en el intento 3: " . $resumen["intento3"] . "\n";
    echo "Adivinadas en el intento 4: " . $resumen["intento4"] . "\n";
    echo "Adivinadas en el intento 5: " . $resumen["intento5"] . "\n";
    echo "Adivinadas en el intento 6: " . $resumen["intento6"] . "\n";
    echo "****************************************\n";
}

/**
 * Solicita el nombre de un jugador y lo retorna en minúsculas.
 * @return string
 */
function solicitarJugador() {
    echo "Ingrese el nombre del jugador: ";
    $nombre = trim(fgets(STDIN));
    while (!ctype_alpha($nombre)) {
        echo "El nombre debe comenzar con una letra: ";
        $nombre = trim(fgets(STDIN));
    }
    return strtolower($nombre);
}
/**
 * Obtiene una palabra válida no jugada previamente.
 * @param array $coleccionPalabras
 * @param array $coleccionPartidas
 * @return string
 */
function obtenerPalabraNoJugadas($coleccionPalabras, $coleccionPartidas) {
    $palabrasJugadas = [];
    foreach ($coleccionPartidas as $partida) {
        $palabrasJugadas[] = $partida["palabraWordix"]; // Extraer palabras jugadas
    }

    foreach ($coleccionPalabras as $palabra) {
        $yaJugada = false;
        foreach ($palabrasJugadas as $jugada) {
            if ($palabra === $jugada) {
                $yaJugada = true;
                break;
            }
        }
        if (!$yaJugada) {
            return $palabra; // Retornar la primera palabra no jugada
        }
    }
    return null; // Retornar null si no hay palabras disponibles
}


/**
 * Permite al jugador elegir una palabra disponible ingresando un número.
 * Valida que el jugador no haya jugado esa palabra antes.
 * @param array $coleccionPalabras
 * @param array $coleccionPartidas
 * @param string $nombreJugador
 * @return string
 */
function elegirPalabraNoJugadasPorJugador($coleccionPalabras, $coleccionPartidas, $nombreJugador) {
    // Obtener palabras ya jugadas por el jugador
    $palabrasJugadasPorJugador = [];
    foreach ($coleccionPartidas as $partida) {
        if ($partida["jugador"] === $nombreJugador) {
            $palabrasJugadasPorJugador[] = $partida["palabraWordix"];
        }
    }

    // Mostrar las palabras disponibles con su número
    echo "Palabras disponibles:\n";
    $palabrasDisponibles = [];
    foreach ($coleccionPalabras as $indice => $palabra) {
        $yaJugada = false;
        foreach ($palabrasJugadasPorJugador as $jugada) {
            if ($palabra === $jugada) {
                $yaJugada = true;
                break;
            }
        }
        if (!$yaJugada) {
            $palabrasDisponibles[$indice] = $palabra;
            echo "$indice: $palabra\n";
        }
    }

    // Validar la elección del usuario
    do {
        echo "Ingrese el número de la palabra que desea jugar: ";
        $numero = solicitarNumeroEntre(0, count($coleccionPalabras) - 1);

        if (isset($palabrasDisponibles[$numero])) {
            return $palabrasDisponibles[$numero]; // Devuelve la palabra seleccionada
        } else {
            echo "El número ingresado no corresponde a una palabra disponible. Intente nuevamente.\n";
        }
    } while (true);
}


/**
 * Ordena y muestra la colección de partidas utilizando uasort y print_r.
 * @param array $coleccionPartidas
 */
function mostrarPartidasOrdenadas(&$coleccionPartidas) {
    uasort($coleccionPartidas, function ($partida1, $partida2) {
        if ($partida1["jugador"] === $partida2["jugador"]) {
            return strcmp($partida1["palabraWordix"], $partida2["palabraWordix"]);
        }
        return strcmp($partida1["jugador"], $partida2["jugador"]); 
    });
    print_r($coleccionPartidas);
}



/**************************************/
/*********** PROGRAMA PRINCIPAL *******/
/**************************************/

$coleccionPalabras = cargarColeccionPalabras();
$coleccionPartidas = cargarPartidas();

do {
    $opcion = seleccionarOpcion();

    switch ($opcion) {
        case 1: // Jugar con una palabra elegida
            if (count($coleccionPalabras) === count($coleccionPartidas)) {
                echo "No quedan palabras disponibles para jugar.\n";
                break;
            }
            $jugador = solicitarJugador(); 
            $palabra = elegirPalabraNoJugadasPorJugador($coleccionPalabras, $coleccionPartidas, $jugador);
            $partida = jugarWordix($palabra, $jugador);
            mostrarResultadoPartida($partida);
            $coleccionPartidas[] = $partida;
            break;
        
        case 2: // Jugar con una palabra aleatoria
            if (count($coleccionPalabras) === count($coleccionPartidas)) {
                echo "No quedan palabras disponibles para jugar.\n";
                break;
            }
            $palabra = obtenerPalabraNoJugadas($coleccionPalabras, $coleccionPartidas);
            $jugador = solicitarJugador();
            $partida = jugarWordix($palabra, $jugador);
            mostrarResultadoPartida($partida);
            $coleccionPartidas[] = $partida;
            break;

        case 3: // Mostrar una partida
            echo "Ingrese el número de la partida a mostrar:\n";
            $numPartida = solicitarNumeroEntre(0, count($coleccionPartidas) - 1);
            if (isset($coleccionPartidas[$numPartida])) {
                mostrarResultadoPartida($coleccionPartidas[$numPartida]);
            } else {
                echo "Número de partida inválido.\n";
            }
            break;

        case 4: // Mostrar la primera partida ganada por un jugador
            $jugador = solicitarJugador();
            $indice = obtenerPrimeraPartidaGanada($coleccionPartidas, $jugador);
            if ($indice !== -1) {
                mostrarResultadoPartida($coleccionPartidas[$indice]);
            } else {
                echo "El jugador $jugador no tiene partidas ganadas.\n";
            }
            break;

        case 5: // Mostrar resumen de un jugador
            $jugador = solicitarJugador();
            mostrarResumenJugador($coleccionPartidas, $jugador);
            break;

        case 6: // Mostrar partidas ordenadas
            mostrarPartidasOrdenadas($coleccionPartidas);
            break;

        case 7: // Agregar una palabra
            $nuevaPalabra = leerPalabra5Letras();
            $coleccionPalabras = agregarPalabra($coleccionPalabras, $nuevaPalabra);
            break;

        case 8: // Salir
            echo "\n********** GRACIAS POR JUGAR WORDIX **********\n";
            echo "Saliendo del programa...\n\n";
            break;
    }
} while ($opcion != 8);

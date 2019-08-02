<?php

function prepare_tab($fichier)
{
    $t = fopen($fichier, 'r');
    $t = fread($t, filesize($fichier));
    $tmp = explode("\n", $t);
    array_pop($tmp);

    $algo_result = [];

    foreach ($tmp as $value) {
        array_push($algo_result, str_split($value));
    }
    $final = $algo_result;

    foreach ($algo_result as $key1 => $value) {
        foreach ($value as $key2 => $case) {
            if ($case === '.') {
                $algo_result[$key1][$key2] = 1;
            } elseif ($case === 'o') {
                $algo_result[$key1][$key2] = 0;
            }
        }
    }
    $result = [$algo_result, $final];
    return $result;
}


function algo($tab)
{
    array_shift($tab[1]);
    $final_tab = $tab[1];

    $tab = [$tab[0]];
    array_shift($tab[0]);
    $tab = [$tab[0]];
    while (@$compteur !== 0 ) {
        $compteur = 0;
        foreach ($tab[0] as $key1 => $ligne) {
            foreach ($ligne as $key2 => $case) {
                if ($key1 !== 0 && $key2 !== 0 && $case !== 0) {
                    $tab = transform($tab, $key1, $key2, $compteur);
                    $compteur = $tab[1];
                }
            }
        }
    }

    $coordonne = find_coin($tab[0]);
    if (!empty($coordonne)) {
        $tab = draw_square($final_tab, $coordonne);
    }
    else {
        $tab = $final_tab;
    }

    return $tab;
}

function find_coin($tab) {
    $nb = 0;
    $coordonne = [];
    foreach ($tab as $key1 => $value) {
        foreach ($value as $key2 => $case) {
            if ($case > $nb) {
                $nb = $case;
                $coordonne = [$key1, $key2, $nb];
            }
        }
    }
    return $coordonne;
}

function draw_square($tab, $coordonne) {

    $cote = $coordonne[2];
    for ($ligne = $coordonne[0]; $ligne > $coordonne[0]-$cote; $ligne--) {
        for ($colonne = $coordonne[1]; $colonne > $coordonne[1]-$cote; $colonne--) {
            $tab[$ligne][$colonne] = 'x';
        }
    }
    return $tab;
}

function transform($tab, $ligne, $key_debut, $compteur)
{

    $key = $key_debut - 1;
    $line = $ligne - 1;
    $tab = $tab[0];
    $nb = $tab[$ligne][$key_debut]+1;
    $nb_base = $tab[$ligne][$key_debut];

    if ($tab[$ligne][$key] >= $nb_base && $tab[$line][$key] >= $nb_base && $tab[$line][$key_debut] >= $nb_base) {
        if ($tab[$ligne][$key] !== 0 && $tab[$line][$key] !== 0 && $tab[$line][$key_debut] !== 0) {
            $tab[$ligne][$key_debut] = $nb;
            $compteur++;
        }
    }
    $tab = [$tab, $compteur];
    return $tab;

}

function main($argv) {
    $timestart = microtime(true);
    if (!empty($argv[1]) && is_file($argv[1]) !== false) {
        $t = prepare_tab($argv[1]);
        $tab = algo($t);
        echo count($tab) . "\n";
        foreach ($tab as $key1 => $ligne) {
            foreach ($ligne as $key2 => $case) {
                echo $case;
            }
            echo "\n";
        }
    }
    $timeend=microtime(true);
    $time=$timeend-$timestart;
    $page_load_time = number_format($time, 10);
    echo "Debut du script: ".date("H:i:s", $timestart)."\n";
    echo "Fin du script: ".date("H:i:s", $timeend)."\n";
    echo "Script execute en " . $page_load_time . " sec\n";
}

main($argv);

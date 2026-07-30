<?php

namespace App\Support;

/**
 * Calcula el nivel de alerta (vencido/urgente/proximo/ok) para documentos
 * y mantenciones, replicando los umbrales del prototipo original
 * (nivelDias() en app.js y los bloques de nivel en api/dashboard.php).
 */
class NivelEstado
{
    public static function porDias(?int $diasRestantes): string
    {
        if ($diasRestantes === null) {
            return 'ok';
        }
        if ($diasRestantes < 0) {
            return 'vencido';
        }
        if ($diasRestantes <= 15) {
            return 'urgente';
        }
        if ($diasRestantes <= 30) {
            return 'proximo';
        }

        return 'ok';
    }

    public static function porKm(?int $kmRestante): string
    {
        if ($kmRestante === null) {
            return 'ok';
        }
        if ($kmRestante <= 0) {
            return 'vencido';
        }
        if ($kmRestante <= 500) {
            return 'urgente';
        }
        if ($kmRestante <= 1500) {
            return 'proximo';
        }

        return 'ok';
    }

    public static function paraMantencion(?int $kmRestante, ?int $diasRestantes): string
    {
        return self::peor(self::porKm($kmRestante), self::porDias($diasRestantes));
    }

    private static function peor(string $a, string $b): string
    {
        $orden = ['vencido' => 0, 'urgente' => 1, 'proximo' => 2, 'ok' => 3];

        return $orden[$a] <= $orden[$b] ? $a : $b;
    }
}

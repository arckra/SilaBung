<?php

namespace App\Support;

class Geo
{
    /**
     * Jarak garis lurus antara dua koordinat (km) dengan Haversine.
     * Return null jika salah satu koordinat tidak lengkap.
     */
    public static function distanceKm(?array $a, ?array $b): ?float
    {
        if (! $a || ! $b) {
            return null;
        }
        foreach ([$a['lat'], $a['lng'], $b['lat'], $b['lng']] as $v) {
            if (! is_numeric($v)) {
                return null;
            }
        }

        $R    = 6371.0;
        $dLat = deg2rad($b['lat'] - $a['lat']);
        $dLng = deg2rad($b['lng'] - $a['lng']);

        $h = sin($dLat / 2) ** 2
           + cos(deg2rad($a['lat'])) * cos(deg2rad($b['lat'])) * sin($dLng / 2) ** 2;

        return 2 * $R * asin(min(1.0, sqrt($h)));
    }

    /** Format jarak ke string manusiawi. */
    public static function formatKm(?float $km): ?string
    {
        if ($km === null) {
            return null;
        }
        if ($km < 1) {
            return round($km * 1000) . ' m';
        }
        return number_format($km, 1, ',', '.') . ' km';
    }

    /** Estimasi waktu tempuh (menit) asumsi 22 km/h. */
    public static function estimateMinutes(?float $km): ?int
    {
        if ($km === null) {
            return null;
        }
        return max(2, (int) round(($km / 22) * 60));
    }

    /** Format estimasi waktu ke string. */
    public static function formatEta(?int $minutes): ?string
    {
        if ($minutes === null) {
            return null;
        }
        if ($minutes < 60) {
            return '± ' . $minutes . ' menit';
        }
        $h = intdiv($minutes, 60);
        $m = $minutes % 60;
        return '± ' . $h . ' jam' . ($m ? ' ' . $m . ' mnt' : '');
    }
}
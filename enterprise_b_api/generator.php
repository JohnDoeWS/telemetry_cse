<?php
/*
 * enterprise_b_api/generator.php
 *
 * Purpose:
 *   Produces a realistic-looking JSON payload representing reactor
 *   sensor telemetry. The function `generate_data()` is intentionally
 *   simple and deterministic in shape so the dashboard can render
 *   temperature, pressure, status, alerts and additional details.
 *
 * Notes:
 *   - Value ranges are produced using `mt_rand()` and then scaled to
 *     readable units. This is a mock; in real deployments the API
 *     would return real sensor values.
 */

function generate_data() {
    // Determine status distribution with skewed probabilities:
    // 95.0% OK, 4.9% Warning, 0.1% Critical.
    // For OK results we keep temperature and pressure in safe nominal ranges.
    // Warning and Critical results are rare and use higher values to match the
    // desired distribution.
    $roll = mt_rand(1, 1000);
    $alerts = [];
    if ($roll <= 950) {
        $status = 'OK';
        $temperature = round(mt_rand(2600, 3100) / 100, 2); // mostly nominal
        $pressure = round(mt_rand(15000, 19500) / 100, 2);
    } elseif ($roll <= 999) {
        $status = 'Warning';
        $temperature = round(mt_rand(3101, 3300) / 100, 2);
        $pressure = round(mt_rand(19501, 21000) / 100, 2);
        $alerts[] = 'Sensor reading approaching critical threshold';
    } else {
        $status = 'Critical';
        $temperature = round(mt_rand(3301, 3400) / 100, 2);
        $pressure = round(mt_rand(21001, 22000) / 100, 2);
        $alerts[] = 'Sensor reading above critical threshold';
    }

    // Additional synthetic details that the dashboard can display
    $details = [
        'sensor_core_1' => round(mt_rand(2500, 3400) / 100, 2),
        'sensor_core_2' => round(mt_rand(2500, 3400) / 100, 2),
        'coolant_flow' => round(mt_rand(800, 1200) / 10, 1), // arbitrary units
        'reactor_power_pct' => round(mt_rand(7000, 10000) / 100, 2),
    ];

    return [
        'timestamp' => gmdate('c'),
        'temperature' => $temperature,
        'pressure' => $pressure,
        'status' => $status,
        'alerts' => $alerts,
        'details' => $details
    ];
}

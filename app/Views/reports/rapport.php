<?php
$reportData = $reportData ?? [];
$kpi = $reportData['kpi'] ?? [
    'students' => 0,
    'present' => 0,
    'late' => 0,
    'absent' => 0,
    'global_rate' => 0,
];
$rows = $reportData['rows'] ?? [];
$departments = $reportData['departments'] ?? [];
$cohorts = $reportData['cohorts'] ?? [];
$filters = $reportData['filters'] ?? ['department_id' => '', 'cohort_id' => ''];
$today = $reportData['today'] ?? date('Y-m-d');

$perPage = 5;
$currentPage = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
$totalRows = count($rows);
$totalPages = max(1, (int)ceil($totalRows / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;
$pagedRows = array_slice($rows, $offset, $perPage);

$paginationParams = [
    'page' => 'reports',
    'department_id' => $filters['department_id'] ?? '',
    'cohort_id' => $filters['cohort_id'] ?? '',
];

$exportParams = $paginationParams;
unset($exportParams['p']);

$escape = function ($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
};

if (!function_exists('reportPdfText')) {
    function reportPdfText($value)
    {
        $text = str_replace(["\r", "\n", "\t"], ' ', (string)$value);

        if (function_exists('iconv')) {
            $converted = @iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
            if ($converted !== false) {
                $text = $converted;
            }
        }

        return preg_replace('/[^\x20-\x7E\x80-\xFF]/', '', $text);
    }
}

if (!function_exists('reportPdfEscape')) {
    function reportPdfEscape($value)
    {
        return str_replace(['\\', '(', ')'], ['\\\\', '\\(', '\\)'], reportPdfText($value));
    }
}

if (!function_exists('reportPdfShorten')) {
    function reportPdfShorten($value, $limit)
    {
        $value = trim((string)$value);
        $length = function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);

        if ($length <= $limit) {
            return $value;
        }

        return (function_exists('mb_substr') ? mb_substr($value, 0, $limit - 3, 'UTF-8') : substr($value, 0, $limit - 3)) . '...';
    }
}

if (!function_exists('reportBuildPdf')) {
    function reportBuildPdf($today, array $kpi, array $rows)
    {
        $pages = [];
        $content = '';
        $y = 0;

        $text = function ($value, $x, $yPos, $size = 10, $bold = false, $color = '0 0 0') use (&$content) {
            $font = $bold ? 'F2' : 'F1';
            $content .= "BT {$color} rg /{$font} {$size} Tf 1 0 0 1 {$x} {$yPos} Tm (" . reportPdfEscape($value) . ") Tj ET\n";
        };

        $line = function ($x1, $y1, $x2, $y2, $color = '0.8 0.84 0.9', $width = 0.5) use (&$content) {
            $content .= "q {$color} RG {$width} w {$x1} {$y1} m {$x2} {$y2} l S Q\n";
        };

        $rect = function ($x, $yPos, $w, $h, $color) use (&$content) {
            $content .= "q {$color} rg {$x} {$yPos} {$w} {$h} re f Q\n";
        };

        $startPage = function () use (&$content, &$y, &$text, &$line, $today) {
            $content = '';
            $y = 555;
            $text('Rapport de pointage', 30, $y, 20, true, '0.12 0.23 0.54');
            $text('Date du rapport : ' . $today . '    Genere le : ' . date('Y-m-d H:i'), 560, $y, 9, false, '0.39 0.45 0.55');
            $line(30, 538, 812, 538, '0.12 0.23 0.54', 1.5);
            $y = 508;
        };

        $drawTableHeader = function () use (&$y, &$text, &$rect, &$line) {
            $x = 30;
            $headers = ['Departement', 'Etudiants', 'Cohorte', 'Presents', 'Retards', 'Absences', 'Taux'];
            $widths = [190, 90, 140, 90, 90, 90, 92];
            $rect(30, $y - 6, 782, 24, '0.12 0.23 0.54');

            foreach ($headers as $index => $header) {
                $text($header, $x + 6, $y + 2, 9, true, '1 1 1');
                $x += $widths[$index];
            }

            $line(30, $y - 6, 812, $y - 6);
            $y -= 24;
        };

        $finishPage = function () use (&$pages, &$content) {
            $pages[] = $content;
        };

        $startPage();

        $kpiLabels = ['Etudiants', 'Presences', 'Retards', 'Absences', 'Taux global'];
        $kpiValues = [
            (int)($kpi['students'] ?? 0),
            (int)($kpi['present'] ?? 0),
            (int)($kpi['late'] ?? 0),
            (int)($kpi['absent'] ?? 0),
            (int)($kpi['global_rate'] ?? 0) . '%',
        ];
        $boxWidth = 148;

        foreach ($kpiLabels as $index => $label) {
            $x = 30 + ($index * 158);
            $rect($x, $y - 26, $boxWidth, 48, '0.96 0.98 1');
            $line($x, $y - 26, $x + $boxWidth, $y - 26);
            $line($x, $y + 22, $x + $boxWidth, $y + 22);
            $line($x, $y - 26, $x, $y + 22);
            $line($x + $boxWidth, $y - 26, $x + $boxWidth, $y + 22);
            $text($label, $x + 10, $y + 6, 8, true, '0.39 0.45 0.55');
            $text($kpiValues[$index], $x + 10, $y - 14, 18, true, '0.06 0.09 0.16');
        }

        $y -= 72;
        $drawTableHeader();

        if (empty($rows)) {
            $text('Aucun resultat trouve pour ces filtres.', 300, $y, 10, false, '0.39 0.45 0.55');
        }

        foreach ($rows as $row) {
            if ($y < 62) {
                $finishPage();
                $startPage();
                $drawTableHeader();
            }

            $x = 30;
            $widths = [190, 90, 140, 90, 90, 90, 92];
            $cells = [
                reportPdfShorten($row['department'] ?? '', 31),
                (int)($row['students'] ?? 0),
                reportPdfShorten($row['cohort'] ?? '', 22),
                (int)($row['present'] ?? 0),
                (int)($row['late'] ?? 0),
                (int)($row['absent'] ?? 0),
                (int)($row['rate'] ?? 0) . '%',
            ];

            $line(30, $y - 6, 812, $y - 6);

            foreach ($cells as $index => $cell) {
                $text($cell, $x + 6, $y + 1, 9);
                $line($x, $y - 6, $x, $y + 16);
                $x += $widths[$index];
            }

            $line(812, $y - 6, 812, $y + 16);
            $y -= 22;
        }

        $finishPage();

        $objects = [
            1 => "<< /Type /Catalog /Pages 2 0 R >>",
            3 => "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>",
            4 => "<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>",
        ];
        $kids = [];
        $objectId = 5;

        foreach ($pages as $pageContent) {
            $contentId = $objectId++;
            $pageId = $objectId++;
            $objects[$contentId] = "<< /Length " . strlen($pageContent) . " >>\nstream\n{$pageContent}endstream";
            $objects[$pageId] = "<< /Type /Page /Parent 2 0 R /MediaBox [0 0 842 595] /Resources << /Font << /F1 3 0 R /F2 4 0 R >> >> /Contents {$contentId} 0 R >>";
            $kids[] = "{$pageId} 0 R";
        }

        $objects[2] = "<< /Type /Pages /Kids [" . implode(' ', $kids) . "] /Count " . count($kids) . " >>";
        ksort($objects);

        $pdf = "%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";
        $offsets = [0 => 0];

        foreach ($objects as $id => $object) {
            $offsets[$id] = strlen($pdf);
            $pdf .= "{$id} 0 obj\n{$object}\nendobj\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 " . (count($objects) + 1) . "\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n{$xref}\n%%EOF";

        return $pdf;
    }
}

if (($_GET['export'] ?? '') === 'excel') {
    $fileName = 'rapport-pointage-' . date('Y-m-d') . '.xls';

    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: no-cache');
    header('Expires: 0');

    echo "\xEF\xBB\xBF";
    ?>
    <table border="1">
        <tr>
            <th colspan="7">Rapport de pointage - <?= htmlspecialchars($today) ?></th>
        </tr>
        <tr>
            <th>Etudiants</th>
            <th>Presences</th>
            <th>Retards</th>
            <th>Absences</th>
            <th colspan="3">Taux global</th>
        </tr>
        <tr>
            <td><?= (int)$kpi['students'] ?></td>
            <td><?= (int)$kpi['present'] ?></td>
            <td><?= (int)$kpi['late'] ?></td>
            <td><?= (int)$kpi['absent'] ?></td>
            <td colspan="3"><?= (int)$kpi['global_rate'] ?>%</td>
        </tr>
        <tr></tr>
        <tr>
            <th>Departement</th>
            <th>Etudiants</th>
            <th>Cohorte</th>
            <th>Presents</th>
            <th>Retards</th>
            <th>Absences</th>
            <th>Taux</th>
        </tr>
        <?php foreach ($rows as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['department']) ?></td>
                <td><?= (int)$row['students'] ?></td>
                <td><?= htmlspecialchars($row['cohort']) ?></td>
                <td><?= (int)$row['present'] ?></td>
                <td><?= (int)$row['late'] ?></td>
                <td><?= (int)$row['absent'] ?></td>
                <td><?= (int)$row['rate'] ?>%</td>
            </tr>
        <?php endforeach; ?>
    </table>
    <?php
    exit;
}

if (($_GET['export'] ?? '') === 'pdf') {
    $fileName = 'rapport-pointage-' . date('Y-m-d') . '.pdf';
    $pdf = reportBuildPdf($today, $kpi, $rows);

    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . strlen($pdf));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    echo $pdf;
    exit;
}

$chartLabels = array_map(function ($row) {
    return $row['department'] . ' - ' . $row['cohort'];
}, $rows);
$chartPresent = array_map('intval', array_column($rows, 'present'));
$chartLate = array_map('intval', array_column($rows, 'late'));
$chartAbsent = array_map('intval', array_column($rows, 'absent'));
?>
<!DOCTYPE html>
<html lang="fr">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Rapports - PointagePro</title>

<script src="https://cdn.tailwindcss.com"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- Sidebar -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- CONTENU -->
    <main class="flex-1 ml-0 lg:ml-64 p-4 sm:p-6 lg:p-8">

        <!-- HEADER -->

        <div class="bg-gradient-to-r from-blue-900 to-amber-700 rounded-3xl p-8 shadow-xl text-white mb-8">

            <div class="flex justify-between items-center">

                <div>

                    <h1 class="text-4xl font-bold flex items-center gap-3">

                        <i class="bi bi-bar-chart-line-fill text-5xl"></i>

                        Rapports & Statistiques

                    </h1>

                    <p class="mt-3 text-blue-100">

                        Analyse des présences, retards et absences des étudiants.

                    </p>

                </div>

                
                  

            </div>

        </div>
        <!-- ================= KPI ================= -->

<div class="grid lg:grid-cols-5 md:grid-cols-2 gap-6 mb-8">

    <!-- Etudiants -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-blue-900 hover:-translate-y-1 hover:shadow-xl transition">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-gray-500 text-sm uppercase font-semibold">
                    Etudiants
                </p>

                <h2 class="text-4xl font-bold text-blue-900 mt-2">
                    <?= (int)$kpi['students'] ?>
                </h2>

                <span class="text-green-600 text-sm">
                    Total
                </span>

            </div>

            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">

                <i class="bi bi-mortarboard-fill text-3xl text-blue-900"></i>

            </div>

        </div>

    </div>

    <!-- Présences -->

    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-green-600 hover:-translate-y-1 hover:shadow-xl transition">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-gray-500 text-sm uppercase font-semibold">
                    Présences
                </p>

                <h2 class="text-4xl font-bold text-green-600 mt-2">
                    <?= (int)$kpi['present'] ?>
                </h2>

                <span class="text-green-600 text-sm">
                    <?= (int)$kpi['global_rate'] ?>%
                </span>

            </div>

            <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center">

                <i class="bi bi-check-circle-fill text-3xl text-green-600"></i>

            </div>

        </div>

    </div>

    <!-- Retards -->

    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-amber-700 hover:-translate-y-1 hover:shadow-xl transition">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-gray-500 text-sm uppercase font-semibold">
                    Retards
                </p>

                <h2 class="text-4xl font-bold text-amber-700 mt-2">
                    <?= (int)$kpi['late'] ?>
                </h2>

                <span class="text-amber-700 text-sm">
                    Aujourd'hui
                </span>

            </div>

            <div class="w-16 h-16 rounded-full bg-amber-100 flex items-center justify-center">

                <i class="bi bi-clock-fill text-3xl text-amber-700"></i>

            </div>

        </div>

    </div>

    <!-- Absences -->

    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-red-600 hover:-translate-y-1 hover:shadow-xl transition">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-gray-500 text-sm uppercase font-semibold">
                    Absences
                </p>

                <h2 class="text-4xl font-bold text-red-600 mt-2">
                    <?= (int)$kpi['absent'] ?>
                </h2>

                <span class="text-red-600 text-sm">
                    Sans pointage
                </span>

            </div>

            <div class="w-16 h-16 rounded-full bg-red-100 flex items-center justify-center">

                <i class="bi bi-person-x-fill text-3xl text-red-600"></i>

            </div>

        </div>

    </div>

    <!-- Taux -->

    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-8 border-blue-700 hover:-translate-y-1 hover:shadow-xl transition">

        <div class="flex items-center justify-between">

            <div>

                <p class="text-gray-500 text-sm uppercase font-semibold">
                    Taux Global
                </p>

                <h2 class="text-4xl font-bold text-blue-700 mt-2">
                    <?= (int)$kpi['global_rate'] ?>%
                </h2>

                <span class="text-green-600 text-sm">
                    <?= ((int)$kpi['global_rate'] >= 80) ? 'Bon' : 'A surveiller' ?>
                </span>

            </div>

            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">

                <i class="bi bi-graph-up-arrow text-3xl text-blue-700"></i>

            </div>

        </div>

    </div>

</div>


    <!-- Camembert -->





<div class="grid grid-cols-1 xl:grid-cols-12 gap-6 mb-8">

<div class="bg-white rounded-3xl shadow-lg p-6 xl:col-span-4">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-xl font-bold text-blue-900">

            Présences par département

        </h2>

        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
            <i class="bi bi-bar-chart-fill text-2xl text-blue-900"></i>
        </div>

    </div>

    <canvas id="barChart" height="260"></canvas>

</div>
<!-- ================= TABLEAU ================= -->

<div class="bg-white rounded-3xl shadow-lg overflow-hidden xl:col-span-8">

    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4 px-6 py-5 border-b">

    <!-- Titre -->
    <div>

        <h2 class="text-2xl font-bold text-blue-900">
            Résumé des pointages
        </h2>

        <p class="text-gray-500 text-sm mt-1">
            Liste des performances des départements
        </p>

    </div>

    <!-- Filtres + Export -->
    <form method="GET" action="index.php" class="flex flex-wrap items-center gap-3">
        <input type="hidden" name="page" value="reports">

        <select name="department_id" class="border border-blue-700 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-700" onchange="this.form.submit()">
            <option value="">Tous les departements</option>
            <?php foreach ($departments as $department): ?>
                <option value="<?= $department['id'] ?>" <?= ((string)$filters['department_id'] === (string)$department['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($department['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="cohort_id" class="border border-blue-700 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-700" onchange="this.form.submit()">
            <option value="">Toutes les cohortes</option>
            <?php foreach ($cohorts as $cohort): ?>
                <option value="<?= $cohort['id'] ?>" <?= ((string)$filters['cohort_id'] === (string)$cohort['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cohort['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <a href="index.php?<?= htmlspecialchars(http_build_query(array_merge($exportParams, ['export' => 'pdf']))) ?>"
           class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-xl transition">
            <i class="bi bi-file-earmark-pdf"></i>
            PDF
        </a>

        <a href="index.php?<?= htmlspecialchars(http_build_query(array_merge($exportParams, ['export' => 'excel']))) ?>"
           class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl transition">
            <i class="bi bi-file-earmark-excel"></i>
            Excel
        </a>
    </form>

    <div class="hidden">

        <select class="border border-blue-700 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-700">

            <option>Tous les départements</option>
            <option>Développement Web</option>
            <option>Bureautique</option>
            <option>Marketing Digital</option>

        </select>

        <select class="border border-blue-700 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-700">

            <option>Toutes les cohortes</option>
            <option>Cohorte 1</option>
            <option>Cohorte 2</option>
            <option>Cohorte 3</option>

        </select>

        <button class="bg-blue-900 hover:bg-blue-800 text-white px-5 py-2 rounded-xl transition">

            <i class="bi bi-download"></i>
            Exporter

        </button>

    </div>

</div>


    <div class="overflow-x-auto">

        <table class="w-full">

            <thead class="bg-gradient-to-r from-blue-900 to-amber-700 text-white">

                <tr>

                    <th class="px-6 py-4 text-left">Département</th>

                    <th class="px-6 py-4 text-center">Étudiants</th>

                    <th class="px-6 py-4 text-center">Cohorte</th>

                    <th class="px-6 py-4 text-center">Présents</th>

                    <th class="px-6 py-4 text-center">Retards</th>

                    <th class="px-6 py-4 text-center">Absences</th>

                    <th class="px-6 py-4 text-center">Taux</th>

                </tr>

            </thead>

            <tbody>
                <?php if (!empty($pagedRows)): ?>
                    <?php foreach ($pagedRows as $row): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="bi bi-building text-blue-900 text-xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold">
                                            <?= htmlspecialchars($row['department']) ?>
                                        </h3>
                                        <span class="text-gray-500 text-sm">
                                            <?= htmlspecialchars($today) ?>
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center font-semibold">
                                <?= (int)$row['students'] ?>
                            </td>

                            <td class="text-center">
                                <?= htmlspecialchars($row['cohort']) ?>
                            </td>

                            <td class="text-center">
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                    <?= (int)$row['present'] ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">
                                    <?= (int)$row['late'] ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                    <?= (int)$row['absent'] ?>
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="bg-blue-100 text-blue-900 px-4 py-2 rounded-full font-bold">
                                    <?= (int)$row['rate'] ?>%
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Aucun resultat trouve pour ces filtres.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php if (false): ?>

                <tr class="hover:bg-slate-50 transition">

                    <td class="px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">

                                <i class="bi bi-laptop text-blue-900 text-xl"></i>

                            </div>

                            <div>

                                <h3 class="font-semibold">
                                    Développement Web
                                </h3>

                                <span class="text-gray-500 text-sm">
                                    Informatique
                                </span>

                            </div>

                        </div>

                    </td>

                    <td class="text-center font-semibold">
                        120
                    </td>

                    <td class="text-center">
                        Cohorte 1
                    </td>

                    <td class="text-center">

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

                            114

                        </span>

                    </td>

                    <td class="text-center">

                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">

                            4

                        </span>

                    </td>

                    <td class="text-center">

                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

                            2

                        </span>

                    </td>

                    <td class="text-center">

                        <span class="bg-blue-100 text-blue-900 px-4 py-2 rounded-full font-bold">

                            95%

                        </span>

                    </td>

                </tr>



                <tr class="hover:bg-slate-50 transition">

                    <td class="px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">

                                <i class="bi bi-briefcase-fill text-amber-700 text-xl"></i>

                            </div>

                            <div>

                                <h3 class="font-semibold">

                                    Marketing Digital

                                </h3>

                                <span class="text-gray-500 text-sm">

                                    Communication

                                </span>

                            </div>

                        </div>

                    </td>

                    <td class="text-center font-semibold">

                        90

                    </td>

                    <td class="text-center">

                        Cohorte 2

                    </td>

                    <td class="text-center">

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

                            80

                        </span>

                    </td>

                    <td class="text-center">

                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">

                            6

                        </span>

                    </td>

                    <td class="text-center">

                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

                            4

                        </span>

                    </td>

                    <td class="text-center">

                        <span class="bg-amber-100 text-amber-700 px-4 py-2 rounded-full font-bold">

                            89%

                        </span>

                    </td>

                </tr>



                <tr class="hover:bg-slate-50 transition">

                    <td class="px-6 py-5">

                        <div class="flex items-center gap-3">

                            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center">

                                <i class="bi bi-book-half text-purple-700 text-xl"></i>

                            </div>

                            <div>

                                <h3 class="font-semibold">

                                    Bureautique

                                </h3>

                                <span class="text-gray-500 text-sm">

                                    Informatique

                                </span>

                            </div>

                        </div>

                    </td>

                    <td class="text-center font-semibold">

                        110

                    </td>

                    <td class="text-center">

                        Cohorte 3

                    </td>

                    <td class="text-center">

                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">

                            90

                        </span>

                    </td>

                    <td class="text-center">

                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">

                            12

                        </span>

                    </td>

                    <td class="text-center">

                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">

                            8

                        </span>

                    </td>

                    <td class="text-center">

                        <span class="bg-blue-100 text-blue-900 px-4 py-2 rounded-full font-bold">

                            82%

                        </span>

                    </td>

                </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

    <?php if ($totalPages > 1): ?>
        <div class="flex flex-wrap justify-center items-center gap-2 px-6 py-5 border-t">
            <a href="index.php?<?= htmlspecialchars(http_build_query(array_merge($paginationParams, ['p' => max(1, $currentPage - 1)]))) ?>"
               class="<?= ($currentPage === 1) ? 'pointer-events-none opacity-50' : '' ?> inline-flex items-center gap-2 px-4 py-2 border rounded-2xl bg-white hover:bg-slate-50 transition">
                <i class="bi bi-chevron-left"></i>
                Precedent
            </a>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="index.php?<?= htmlspecialchars(http_build_query(array_merge($paginationParams, ['p' => $i]))) ?>"
                   class="w-10 h-10 flex items-center justify-center border rounded-2xl <?= ($currentPage === $i) ? 'bg-blue-900 text-white border-blue-900' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' ?> transition">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <a href="index.php?<?= htmlspecialchars(http_build_query(array_merge($paginationParams, ['p' => min($totalPages, $currentPage + 1)]))) ?>"
               class="<?= ($currentPage === $totalPages) ? 'pointer-events-none opacity-50' : '' ?> inline-flex items-center gap-2 px-4 py-2 border rounded-2xl bg-white hover:bg-slate-50 transition">
                Suivant
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    <?php endif; ?>

</div>

</div>


<script>












new Chart(document.getElementById("barChart"),{

type:"bar",

data:{

labels:[

"Dév Web",
"Bureautique",
"Marketing"

],

datasets:[{

label:"Présences",

backgroundColor:"#1E3A8A",

data:[95,82,76]

},

{

label:"Retards",

backgroundColor:"#8B5E3C",

data:[5,12,18]

},

{

label:"Absences",

backgroundColor:"#DC2626",

data:[2,5,7]

}

]

},

options:{

responsive:true,

plugins:{

legend:{

position:"top"

}

}

}

});

</script>

<script>
const reportChart = Chart.getChart("barChart");
if (reportChart) {
    reportChart.data.labels = <?= json_encode($chartLabels, JSON_UNESCAPED_UNICODE) ?>;
    reportChart.data.datasets[0].label = "Presences";
    reportChart.data.datasets[0].data = <?= json_encode($chartPresent) ?>;
    reportChart.data.datasets[1].data = <?= json_encode($chartLate) ?>;
    reportChart.data.datasets[2].data = <?= json_encode($chartAbsent) ?>;
    reportChart.update();
}
</script>

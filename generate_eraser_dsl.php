<?php

// Bootstrap Laravel
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Schema;

$tables = Schema::getTables();
$relationships = [];
$output = "direction right\n\n";

foreach ($tables as $tableInfo) {
    $tableName = $tableInfo['name'];

    $output .= "{$tableName} {\n";
    $columns = Schema::getColumns($tableName);
    $indexes = Schema::getIndexes($tableName);

    // Identify primary key
    $pk = null;
    foreach ($indexes as $index) {
        if ($index['primary']) {
            $pk = $index['columns'][0] ?? null;
        }
    }

    foreach ($columns as $col) {
        $name = $col['name'];
        $type = $col['type'];
        $nullable = $col['nullable'] ? ' null' : '';
        $isPk = ($name === $pk || $name === 'id') ? ' pk' : '';
        // If type contains spaces or special characters, clean it or simplify
        $cleanType = preg_replace('/[^a-zA-Z0-9_]/', '', $type);
        $output .= "  {$name} {$cleanType}{$isPk}{$nullable}\n";
    }
    $output .= "}\n\n";

    $foreignKeys = Schema::getForeignKeys($tableName);
    foreach ($foreignKeys as $fk) {
        $localCol = $fk['columns'][0] ?? null;
        $foreignTable = $fk['foreign_table'] ?? null;
        $foreignCol = $fk['foreign_columns'][0] ?? null;
        if ($localCol && $foreignTable && $foreignCol) {
            $relationships[] = "{$tableName}.{$localCol} > {$foreignTable}.{$foreignCol}";
        }
    }
}

foreach ($relationships as $rel) {
    $output .= $rel."\n";
}

// Print to stdout
echo "---START_DSL---\n";
echo $output;
echo "---END_DSL---\n";

<?php
$dbUrl = "postgresql://postgres:Govin161004_@db.vvgylrykkrqlvohmhymb.supabase.co:5432/postgres";

try {
    $dbopts = parse_url($dbUrl);
    $dsn = "pgsql:host=" . $dbopts["host"] . ";port=" . $dbopts["port"] . ";dbname=" . ltrim($dbopts["path"],'/');
    $pdo = new PDO($dsn, $dbopts["user"], $dbopts["pass"]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected to Supabase successfully!\n";
    
    $sql = file_get_contents('database_postgres.sql');
    if ($sql === false) {
        die("Could not read database_postgres.sql\n");
    }
    
    // Execute SQL
    $pdo->exec($sql);
    echo "Database schema and initial data created successfully in Supabase!\n";

} catch (PDOException $e) {
    echo "Database connection or migration failed: " . $e->getMessage() . "\n";
}

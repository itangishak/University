<?php
/**
 * Database Setup Script
 * Creates the database and imports the schema
 */

// Database connection without specifying database name
try {
    $pdo = new PDO(
        "mysql:host=localhost;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    echo "Connected to MySQL server successfully.\n";
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS bau_website CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database 'bau_website' created or already exists.\n";
    
    // Switch to the database
    $pdo->exec("USE bau_website");
    echo "Switched to bau_website database.\n";
    
    // Check if tables already exist
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($tables)) {
        echo "No tables found. Importing schema...\n";
        
        // Read and execute the main schema file
        $schemaFile = __DIR__ . '/bau_schema.sql';
        if (file_exists($schemaFile)) {
            $sql = file_get_contents($schemaFile);
            
            // Remove the CREATE DATABASE and USE statements since we already handled them
            $sql = preg_replace('/CREATE DATABASE.*?;/i', '', $sql);
            $sql = preg_replace('/USE.*?;/i', '', $sql);
            
            // Split by semicolon and execute each statement
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    try {
                        $pdo->exec($statement);
                    } catch (PDOException $e) {
                        echo "Warning: " . $e->getMessage() . "\n";
                    }
                }
            }
            
            echo "Main schema imported successfully.\n";
        }
        
        // Import additional schema if it exists
        $additionalSchemaFile = __DIR__ . '/bau_schema_additions.sql';
        if (file_exists($additionalSchemaFile)) {
            $sql = file_get_contents($additionalSchemaFile);
            
            $statements = array_filter(array_map('trim', explode(';', $sql)));
            
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    try {
                        $pdo->exec($statement);
                    } catch (PDOException $e) {
                        echo "Warning: " . $e->getMessage() . "\n";
                    }
                }
            }
            
            echo "Additional schema imported successfully.\n";
        }
    } else {
        echo "Database already has " . count($tables) . " tables. Skipping schema import.\n";
    }
    
    echo "\nDatabase setup completed successfully!\n";
    echo "You can now run your application.\n";
    
} catch (PDOException $e) {
    echo "Database setup failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>

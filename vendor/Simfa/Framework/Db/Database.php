<?php
# **************************************************************************** #
#                                                                              #
#                                                         :::      ::::::::    #
#    Database.php                                       :+:      :+:    :+:    #
#                                                     +:+ +:+         +:+      #
#    By: magoumi <agoumi.mohamed@outlook.com>       +#+  +:+       +#+         #
#                                                 +#+#+#+#+#+   +#+            #
#    Created: 2021/03/21 19:15:38 by magoumi           #+#    #+#              #
#    Updated: 2021/03/21 19:15:38 by magoumi          ###   ########lyon.fr    #
#                                                                              #
# **************************************************************************** #

namespace Simfa\Framework\Db;
use Simfa\Framework\Application;
use Exception;
use \PDO as PDO;
use \PDOException;

class Database
{
	
	public PDO $pdo;
	
	public function __construct(array $config)
	{
		$dsn = $config['DB_DSN'] ?? 'mysql:host=127.0.0.1;port=3306;dbname=camagru';
		$db_user = $config['DB_USER'] ?? '';
		$db_password = $config['DB_PASSWORD'] ?? '';

		try {
			$this->pdo = New PDO($dsn, $db_user, $db_password);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} catch(PDOException $e){
			APPLICATION::$APP->response->setStatusCode(500);
			if (Application::$ENV['env'] == 'dev')
				 die ($e->getMessage() . PHP_EOL); // todo handle this one nicely (add a backtrack)
			else
				echo APPLICATION::$APP->view->renderView('error/__500', [ // todo this is not working
					"title" => "500 Internal Server Error",
					"errorCode" => "00001c"
				]);
		}
	}

    /**
     * apply migrations file
     */
	public function applyMigrations()
	{
		$this->createMigrationsTable();
		$appliedMigrations = $this->getAppliedMigrations();

		$newMigrations = [];
		$files = scandir(Application::$ROOT_DIR.'/migrations');
		$toApplyMigrations = array_diff($files, $appliedMigrations);
		try {
			foreach ($toApplyMigrations as $migration) {
				if ($migration == '.' || $migration == '..')
					continue ;
				require_once Application::$ROOT_DIR. "/migrations//".$migration;
				$className = pathinfo($migration, PATHINFO_FILENAME);
				$instance = NEW $className();
				$this->log("Applying migration $migration");
				$instance->up();
				$this->log("Applied migration $migration");
				$newMigrations[] = $migration;
			}
		} catch (Exception $e) {
			if (!empty($newMigrations))
				$this->saveMigrations($newMigrations);
			echo "applying migration failed after " . $newMigrations[count($newMigrations) - 1] . PHP_EOL;
			print_r($e);
			echo PHP_EOL;
		}

		if (!empty($newMigrations)) 
			$this->saveMigrations($newMigrations);
		else
			$this->log("All migrations are applied");
	}

    /**
     * easy and simple : create migrations table if it doesn't exist
     */
	public function createMigrationsTable()
	{
		$this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
			id INT AUTO_INCREMENT PRIMARY KEY,
			migration VARCHAR(255),
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		) ENGINE=INNODB");
	}

    /**
     * gets the list of already applied migrations
     * @return array
     */
    public function getAppliedMigrations(): array
    {
		$statement = $this->pdo->prepare("SELECT migration FROM migrations");
		$statement->execute();

		return $statement->fetchAll(PDO::FETCH_COLUMN);
	}

	public function saveMigrations(array $migrations)
	{
		$str = implode(', ', array_map(fn($m) => "('$m')", $migrations));
		$statement = $this->pdo->prepare("INSERT INTO migrations(migration) VALUES
			$str
		");
		$statement->execute();
	}

    /**
     * echo a log style the passed parameter
     * @param $message
     */
	protected function log($message)
	{
		echo RED . '[' . date('Y-m-d H:i:s') . ']' . RESET . ' - ' . YELLOW . $message . RESET . PHP_EOL;
	}

    public function downMigrations(int $migrationsNumber)
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $appliedMigrations = array_reverse($appliedMigrations);
        if ($migrationsNumber)
            $toApplyMigrations = array_slice($appliedMigrations,0, $migrationsNumber);
        else
            $toApplyMigrations = $appliedMigrations;

        foreach ($toApplyMigrations as $migration) {
            if ($migration == '.' || $migration == '..')
                continue ;
            require_once Application::$ROOT_DIR. "/migrations//".$migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = NEW $className();
            $this->log("Reverting migration $migration");
            $instance->down();
            $this->log("Reverted migration $migration");
            $newMigrations[] = $migration;
        }
        if (!empty($newMigrations))
            $this->DeleteMigrations($newMigrations);
        else
            $this->log("All migrations are reverted");

    }

    private function DeleteMigrations(array $migrations)
    {
        $str = implode(', ', array_map(fn($m) => "('$m')", $migrations));
        $statement = $this->pdo->prepare("DELETE FROM migrations WHERE (migration) IN ($str)");
        $statement->execute();
    }

//	abstract function relationships():array;
}

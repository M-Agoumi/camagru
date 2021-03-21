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

class database
{
	
	public PDO $pdo;
	
	public function __construct(array $config)
	{
		$dsn = $config['DB_DSN'] ?? 'mysql:host=localhost;port=3306;dbname=camagru';
		$db_user = $config['DB_USER'] ?? '';
		$db_password = $config['DB_PASSWORD'] ?? '';
		try {
			$this->pdo = New PDO($dsn, $db_user, $db_password);
		} catch(PDOException  $e){
			// todo remove this and handle it quitly (code 500)
			APPLICATION::$APP->response->setStatusCode(500);
			if (Application::$APP::$ENV['env'] == 'dev')	
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			else
				echo APPLICATION::$APP->router->renderView('error/__500', [
					"title" => "500 Internal Server Error",
					"errorCode" => "00001c"
				]);
		}
	}

	public function applyMigrataions()
	{
		$this->createMigrationsTable();
		$appliedMigrations = $this->getAppliedMigrations();

		$newMigrations = [];
		$files = scandir(Application::$ROOT_DIR.'/migrations');
		$toApplyMigrations = array_diff($files, $appliedMigrations);
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

		if (!empty($newMigrations)) 
			$this->saveMigrations($newMigrations);
		else
			$this->log("All migrations are applied");
	}

	public function createMigrationsTable()
	{
		$this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
			id INT AUTO_INCREMENT PRIMARY KEY,
			migration VARCHAR(255),
			created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
		) ENGINE=INNODB");
	}

	public function getAppliedMigrations()
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

	protected function log($message)
	{
		echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
	}

}
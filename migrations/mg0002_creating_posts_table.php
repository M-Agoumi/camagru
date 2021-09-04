<?php


use core\Application;

class mg0002_creating_posts_table
{
	public function up()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("CREATE TABLE IF NOT EXISTS `posts` (
						 `id`         int NOT NULL AUTO_INCREMENT ,
						 `title`      varchar(255) NULL ,
						 `comment`    text NULL ,
						 `picture`    varchar(255) NOT NULL ,
						 `slug`       varchar(255) NOT NULL UNIQUE,
						 `created_at` timestamp NOT NULL ,
						 `updated_at` timestamp NULL ,
						 `status`     tinyint NOT NULL default 0,
						 `author`     int NULL ,
						
						PRIMARY KEY (`id`),
						KEY `fkIdx_23` (`author`),
						CONSTRAINT `FK_22` FOREIGN KEY `fkIdx_23` (`author`) REFERENCES `users` (`id`)
						);
						insert into posts(title, comment, picture, slug, created_at, author) values
                       		('Crashing Waves', '#Landscapes, #Waves, #Beach', 'waves.jpg', 'crashing-waves-q42fkx', 
                        	CURRENT_TIMESTAMP(), 1);
						
                        insert into posts(title, comment, picture, slug, created_at, author) values ('Blue Docks', 
                        	'#Docks, #Sunset, #Horizon', 'docks.jpg', 'blue-docks-y42syb', CURRENT_TIMESTAMP(), 1);
						insert into posts(title, comment, picture, slug, created_at, author) values ('Pastel Canyons', 
						    '#Canyon, #Rock #formations', 'canyon.jpg', 'pastel-canyons-y42syb', CURRENT_TIMESTAMP(), 1);
						insert into posts(title, comment, picture, slug, created_at, author) values ('Pink Mountain Sunset',
							'#Landscapes, #Sunset, #Mountains', 'dawn-mountains.jpg', 'pink-mountain-sunset-y42syb',
							CURRENT_TIMESTAMP(), 1);
insert into posts(title, comment, picture, slug, created_at, author) values ('Autumn Pine', '#Forest, #Pines, #Mountains', 'forest.jpg', 'autumn-pine-y42syb', CURRENT_TIMESTAMP(), 1);
insert into posts(title, comment, picture, slug, created_at, author) values ('Purple Power Poles', '#Urban, #landscape, #manmade', 'power-poles.jpg', 'purple-power-poles-y42syb', CURRENT_TIMESTAMP(), 1);
insert into posts(title, comment, picture, slug, created_at, author) values ('Purple Ridges', '#Mountains, #Sunrise, #Landscapes', 'purple-mtn.jpg', 'purple-ridges-y42syb', CURRENT_TIMESTAMP(), 1);
insert into posts(title, comment, picture, slug, created_at, author) values ('Spinning Lights', '#Carnival, #Lights, #Nightlife', 'rides.jpg', 'spinning-lights-y42syb', CURRENT_TIMESTAMP(), 1);
insert into posts(title, comment, picture, slug, created_at, author) values ('Feather Bokeh', '#Close-ups, #Feather, #Bokeh', 'feather.jpg', 'feather-bokeh-y42syb', CURRENT_TIMESTAMP(), 1);
insert into posts(title, comment, picture, slug, created_at, author) values ('Irridescent Bench', '#Landscapes, #Still-Life, #Countryside', 'bench-alt.jpg', 'irridescent-bench-y42syb', CURRENT_TIMESTAMP(), 1);
 insert into posts(title, comment, picture, slug, created_at, author) values ('Blazing Dandelions', '#Dandelion, #Bokeh, #Flora', 'bokeh-dandelion.jpg', 'blazing-dandelions-y42syb', CURRENT_TIMESTAMP(), 1);
						");
	}

	public function down()
	{
		$db = Application::$APP->db;

		$db->pdo->exec("DROP TABLE IF EXISTS posts");
	}
}

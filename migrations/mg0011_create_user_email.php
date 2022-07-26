<?php

use Simfa\Framework\Db\Migration;
use Simfa\Framework\Db\Migration\Schema;

class mg0011_create_user_email
{
	/**
	 * table name in database
	 */
	private const TABLE_NAME = 'email';

	/**
	 * @return void
	 */
	public function up(): void
	{
		Migration::create(self::TABLE_NAME, function (Schema $table){
			$table->id();
			$table->string('email');
			$table->int('user')->nullable();
			$table->string('token')->nullable();
			$table->smallInt('used')->default(0);
			$table->smallInt('active');
			$table->smallInt('confirmed')->default('0');
			$table->smallInt('prime');
			$table->timestamps();

			$table->foreign('user')->references('entityID')->on('user')->onUpdate('cascade')->onDelete('cascade');

			return $table;
		});
	}

	public function down()
	{
		Migration::drop(self::TABLE_NAME);
	}
}

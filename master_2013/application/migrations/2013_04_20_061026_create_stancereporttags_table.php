<?php

class Create_StanceReportTags_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stanceReportTags', function($table) {
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('stanceReport_id')->unsigned();
			$table->string('tag');

			$table->foreign('stanceReport_id')
				->references('id')
				->on('stanceReports')
				->on_delete('restrict')
				->on_update('cascade');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('stanceReportTags');
	}

}
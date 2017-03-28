<?php

class Create_Commentreporttags_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commentReportTags', function($table) {
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('commentReport_id')->unsigned();
			$table->string('tag');

			$table->foreign('commentReport_id')
				->references('id')
				->on('commentReports')
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
		Schema::drop('commentReportTags');
	}

}
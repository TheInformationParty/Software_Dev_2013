<?php

class StanceReportTag extends Tag {

	public function stanceReport()
    {
        return $this->belongs_to('StanceReport');
    }

}
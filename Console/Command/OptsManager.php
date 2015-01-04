<?php
class OptsManager extends AppShell {
	public function main() {
		$this->out('Import and export opts from CSV. Argument is path to file to load or save.');
	}
	public function export() {
		mysql_query('select * from orbopts into outfile local '.$this->args[0]);
	}
	public function import() {
		mysql_query('truncate table orbopts');
		mysql_query('load infile local '.$this->args[0].' into table orbopts');
	}
}

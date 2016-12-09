<?php

class LoginsController extends Controller {

	function view($id = null,$name = null) {
		echo "\n inside logincontroller - > view \n";
		$this->set('title',$name.' - My Login List App');
		$this->set('WMM',$this->Login->select($id));
	}
	
	function viewall() {
		echo "\n inside logincontroller - > viewall \n";
		$this->set('title','All Logins - My Login List App');
		$this->set('WMM',$this->Login->selectAll());
	}

	function add() {
		$username = $_POST['username'];
		$password = $_POST['password'];
		echo "\n add : " . $username . " " . $password . "\n";
		$this->set('title','Success - My Login List App');
		$this->set('WMM',$this->Login->query('insert into logins (username, password, token, id) values ('" . $username . "','" . $password ."','','')));
		// felet ligger på raden ovan.
	}

	function delete($id = null) {
		$this->set('title','Success - My Login List App');
		$this->set('WMM',$this->Login->query('delete from logins where id = \''.mysql_real_escape_string($id).'\''));	
	}
}

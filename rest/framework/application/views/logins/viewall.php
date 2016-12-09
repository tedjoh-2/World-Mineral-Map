<form action="../logins/add" method="post">
<input type="text" value="username.." name="username" onclick="this.value=''">
<input type="text" value ="password.." name="password" onclick="this.value=''">
<input type="submit" value="add">
</form>
<br/><br/>
<?php $number = 0?>
<?php foreach ($WMM as $WMMlogin):?>
	<class="big" href="../logins/view/<?php echo $WMMlogin['Login']['username']?>/<?php echo $WMMlogin['Login']['password']?>/<?php echo $WMMlogin['Login']['Token']?>/<?php echo $WMMlogin['Login']['id']?>">
	<span class="login">
	<?php echo ++$number?>
	<?php echo $WMMlogin['Login']['username']?>
	<?php echo $WMMlogin['Login']['password']?>
	</span>
	</a><br/>
<?php endforeach?>

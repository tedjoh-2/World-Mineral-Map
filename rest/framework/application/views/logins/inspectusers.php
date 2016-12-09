<?php $number = 0?>

<?php foreach ($todo as $todoitem):?>
	<a class="big" href="../items/view/<?php echo $todoitem['Item']['id']?>/<?php echo strtolower(str_replace(" ","-",$todoitem['Item']['username']))?>/<?php echo $todoitem['Item']['password']?">
	<span class="login">
	<?php echo ++$number?>
	<?php echo $todoitem['Item']['username']?>
	</span>
	</a><br/>
<?php endforeach?>

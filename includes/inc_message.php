<? if(isset($_SESSION['message']["success"])) { ?>

<font color="green"><? echo $_SESSION['message']["success"] ?></font>

<? } elseif(isset($_SESSION['message']["error"])) { ?>

<font color="red"><? echo $_SESSION['message']["error"] ?></font>

<? } ?>
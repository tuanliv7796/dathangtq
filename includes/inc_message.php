<? if(isset($_SESSION["success"])) { ?>

<font color="green"><? echo $_SESSION["success"] ?></font>

<? } elseif(isset($_SESSION["error"])) { ?>

<font color="red"><? echo $_SESSION["error"] ?></font>

<? } ?>
<html>
<head>
    <link href="../css/auth.css" rel="stylesheet">
</head>

<body>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://ajax.googleapis.com//ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js" type="text/javascript"></script>
<form action='' method='' name='form' id='form' autocomplete='off' draggable='false'>
    <div id="PINcode" class="container-fluid">
        <br>
        <input id='PINbox' type='password' value='' name='PINbox' disabled /><br/>
        <div id='BUTTONbox' >
            <input type='button' class='PINbutton' name='1' value='1' id='1' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='2' value='2' id='2' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='3' value='3' id='3' onClick=addNumber(this); />
            <br>
            <input type='button' class='PINbutton' name='4' value='4' id='4' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='5' value='5' id='5' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='6' value='6' id='6' onClick=addNumber(this); />
            <br>
            <input type='button' class='PINbutton' name='7' value='7' id='7' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='8' value='8' id='8' onClick=addNumber(this); />
            <input type='button' class='PINbutton' name='9' value='9' id='9' onClick=addNumber(this); />
            <br>
            <input type='button' class='PINbutton clear' name='-' value='clear' id='-' onClick=clearForm(this); />
            <input type='button' class='PINbutton' name='0' value='0' id='0' onClick=addNumber(this); />
            <input type='button' class='btn-lg btn-primary btn-block btn-lg' name='+' value='enter' id='+' onClick=submitForm(PINbox); />
            <br>
            <br>
        </div>
    </div>
</form>


<script src="../js/auth.js"></script>
</body>
</html>
<?php

die();

?>
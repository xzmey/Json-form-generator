<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Json Генератор форм</title>
    <style>
        body{ padding:50px; }
        label{ display:block; }
        input{ padding:5px; }
        select{ width:100px; padding:5px;}
    </style>
</head>
<body>
            <?php
                require_once('form.class.php');
                $form = new Form(['file'=>'./example_form.json']);
		
                $form->show();
				//echo "<pre>";
				//print_r($form);
				//echo "</pre>";
				
            ?>
</body>
</html>
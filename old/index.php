<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Gift Manager</title>
    </head>
    <body>
        <?php
        class User {
            public $id;
            public $name;
            public $surname;
           
            public function __construct($i, $n, $s) {
                $this -> id = $i;
                $this -> name = $n;
                $this -> surname = $s;
            }
        
        }
        echo "Everything OK";
        ?>
    </body>
</html>

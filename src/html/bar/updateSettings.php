<?php include("./password_protect.php"); ?>
<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

require_once('../core.php');

if (MW_DEBUG == True) {
	error_reporting(E_ALL);
	ini_set('display_errors',1);
	echo '<pre>';
	var_dump($_POST);
	echo '</pre>';
}


$data=$_POST;

//$stmt = $pdo->prepare('UPDATE categories SET name=? WHERE id=?');
//$stmt->execute($namehere, $idhere));
//DELETE FROM tabel WHERE id NOT IN (lijst van id's)
//INSERT INTO table (id, name, age) VALUES(1, "A", 19) ON DUPLICATE KEY UPDATE
//name="A", age=19


// SQL Injections are no bueno
if (!empty($data)) {

    foreach ($data['settings'] as $key => $value) {
        $stmt = $pdo->prepare("UPDATE `settings` SET `value` =? WHERE `settings`.`setting` = ?;");
        $stmt->execute(array($value,$key));
    }

    //categories delete, update/insert
	if ($data['categories']){
			$array_del_id = array_column($data['categories'], 'id'); //extract ids from categories
			$array_len = count($array_del_id); //count amount of cat id's
			if ($array_len != 0){
				$varprep = rtrim(str_repeat("?,",$array_len),','); //create list of ?,? and remove last comma
				$sql = "DELETE FROM categories WHERE id NOT IN (" . $varprep . ");"; //create sql
				$stmt = $pdo->prepare($sql);
				$stmt->execute($array_del_id);
			}
			foreach ($data['categories'] as $key => $value) {

				$stmt = $pdo->prepare("
					INSERT INTO `categories` (`id`,`name`)
					VALUES (?,?) 
					ON DUPLICATE KEY 
					UPDATE `id` = VALUES(`id`), `name` = VALUES(`name`);"
				);
				$stmt->execute(
					array(
						$value['id'],
						$value['name']
					)
				);
			};
	}
	if ($data['items']){
		$array_del_id = array_column($data['items'], 'id'); //extract ids from items
		$array_len = count($array_del_id); //count amount of item id's
		if ($array_len != 0){
			$varprep = rtrim(str_repeat("?,",$array_len),','); //create list of ?,? and remove last comma
			$sql = "DELETE FROM drinks WHERE id NOT IN (" . $varprep . ");"; //create sql
			$stmt = $pdo->prepare($sql);
			$stmt->execute($array_del_id);
		}
		foreach ($data['items'] as $key => $value) {
			if ($value['active']=='true') {$value['active']="1";} else {$value['active']="0";}
			$stmt = $pdo->prepare("
				INSERT INTO `drinks` (id,name,start_price,minimum_price,active)
				VALUES (?,?,?,?,?) 
				ON DUPLICATE KEY 
				UPDATE `id` = VALUES(`id`), `name` = VALUES(`name`), `start_price` = VALUES(`start_price`), `minimum_price` = VALUES(`minimum_price`), `active` = VALUES(`active`);"
			);
			$stmt->execute(
				array(
					$value['id'],
					$value['name'],
					$value['start_price'],
					$value['minimum_price'],
					$value['active']
				)
			);
		}
	}


    /*
        //needs insert
        foreach ($data['items'] as $key => $value) {
            if ($value['active']==true) {$value['active']="1";} else {$value['active']="0";}
            //$stmt = $pdo->prepare("UPDATE `drinks` SET `name` = ?, `start_price` = ?, `minimum_price` = ?, `active` = ? WHERE `drinks`.`id` = ?;");

            $stmt = $pdo->prepare("
                INSERT INTO `drinks` (`id`,`name`,`start_price`,`minimum_price`,`active`)
                VALUES (?,?,?,?,?)
                ON DUPLICATE KEY
                UPDATE `id` = VALUES(`id`), `name` = VALUES(`name`), `start_price` = VALUES(`start_price`), `minimum_price` = VALUES(`minimum_price`), `active` = VALUES(`active`) ;"
            );
            $stmt->execute(
                array(
                    $value['id'],
                    $value['name'],
                    $value['start_price'],
                    $value['minimum_price'],
                    $value['active'],
                )
            );
        }*/

    //first clear table of drinks&category
    $stmt = $pdo->prepare("TRUNCATE TABLE `drink_category`;");
    $stmt->execute();
    foreach ($data['items'] as $key => $value) {
        if ($value['categories']){
            foreach ($value['categories'] as $key2 => $value2) {
                $stmt = $pdo->prepare("INSERT INTO `drink_category` (`drink_id`, `category_id`) VALUES (?, ?);");
                $stmt->execute(array($value['id'], $value2));
            }
        }

    }
}

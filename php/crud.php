<?php if(isset($_POST['action'])){
	$inputValues = $_POST;
	unset($inputValues['action'], $inputValues['table']);
	if(isset($_FILES['image']['tmp_name']))	$inputValues['image'] = base64_encode(file_get_contents(addslashes($_FILES['image']['tmp_name'])));
	$postReference = $inputValues;
	$inputValues = array_values($inputValues);
	$columnsAndValues = $columns = $values = '';
	for($i = 0; $i < count($inputValues); $i++){
		if($i > 0 && $i != count($inputValues)){
			$columnsAndValues .= ', ';
			$columns .= ', ';
			$values .= ', ';
		}
		$columnsAndValues .= array_search($inputValues[$i], $postReference)." = '$inputValues[$i]'";
		$columns .= array_search($inputValues[$i], $postReference);
		$values .= "'$inputValues[$i]'";
		unset($postReference[array_search($inputValues[$i], $postReference)]);
	}
	switch($_POST['action']){
		case 'insert': $conn->query("insert into $_POST[table] ($columns) values ($values)");
		break;
		case 'delOrUp':
			if(isset($_POST['edi'])){
				$ediInfo = $conn->query("select * from $_POST[table] where id = $_POST[edi]")->fetch_assoc();
				$ediInfo = array_values($ediInfo);
			}else if(isset($_POST['eli'])){
				$conn->query("delete from $_POST[table] where id = $_POST[eli]");
			}
		break;
		case 'update': $conn->query("update $_POST[table] set $columnsAndValues where id = $_POST[id]");
		break;
	}
}

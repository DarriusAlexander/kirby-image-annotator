<?php
$router = new Router(array(
	array(
		'pattern' => '(:all)/imagemarkers/update-coordinates',
		'method'  => 'POST',
		'filter'  => 'auth',
		'action'  => function() {
			$uid = $_POST['uid'];
			$fieldname = $_POST['fieldname'];
			$entryId = $_POST['entryid'];
		    $id = $_POST['id'];
		    $x = $_POST['x'];
		    $y = $_POST['y'];

		    $page = site()->index()->findBy('uid', $uid);
		    $field = $page->$fieldname()->yaml();

		    $key = array_search($id, array_column($field, 'markerid'));

		    $markerids = array_column($field, 'markerid');
		    $key = array_search($id, $markerids);

		    $field[$key]['x'] = $x;
		    $field[$key]['y'] = $y;

		    $field = yaml::encode($field);

		    try {
		        $page->update(array($fieldname => $field));
		        return true;
		    } catch(Exception $e) {
		        return $e->getMessage();
		    }
        }
	),
));

function addToStructure($page, $field, $data = array()){
    $fieldData = page($page)->$field()->yaml();
    $fieldData[] = $data;
    $fieldData = yaml::encode($fieldData);
    try {
        page($page)->update(array($field => $fieldData));
        return true;
    } catch(Exception $e) {
        return $e->getMessage();
    }
}

$route = $router->run(kirby()->path());
if(is_null($route)) return;
$response = call($route->action(), $route->arguments());
exit;
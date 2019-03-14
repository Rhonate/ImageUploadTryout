<?php
header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

require_once dirname(__FILE__) . '/FileHandler.php';

$response = array();

if (isset($_GET['apicall'])) {
    switch ($_GET['apicall']) {
        case 'upload':

            if (isset($_POST['desc']) && strlen($_POST['desc']) > 0 && $_FILES['image']['error'] === UPLOAD_ERR_OK && isset($_POST['product_name'])){
                $upload = new FileHandler();

                $file = $_FILES['image']['tmp_name'];
                 $product_name=$_POST['product_name'];
                // $seller_owner=$_POST['seller_id'];
                $desc = $_POST['desc'];

                if ($upload->saveFile($file, getFileExtension($_FILES['image']['name']), $desc,$product_name)) {
                    $response['error'] = false;
                    $response['message'] = 'File Uploaded Successfullly';
                }

            } else {
                $response['error'] = true;
                $response['message'] = 'Required parameters are not available';
            }

            break;

        case 'getallimages':

            $upload = new FileHandler();
            $response['error'] = false;
            $response['images'] = $upload->getAllFiles();

            break;
			
		case 'getsellerimages':
		$getone=new FileHandler();
		$response['error']=false;
		$response['images']=$getone->getonefile();
		
		  break;
    }
}

echo json_encode($response);

function getFileExtension($file)
{
    $path_parts = pathinfo($file);
    return $path_parts['extension'];
}
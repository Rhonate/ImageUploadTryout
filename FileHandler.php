<?php


error_reporting(E_ALL);
ini_set('display_errors', 1);
class FileHandler
{

    private $con;

    public function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';

        $db = new DbConnect();
        $this->con = $db->connect();
    }


    public function saveFile($file, $extension, $desc,$product_name)
    {
        $name = round(microtime(true) * 1000) . '.' . $extension;
        $filedest = dirname(__FILE__) . UPLOAD_PATH . $name;
        move_uploaded_file($file, $filedest);


        $stmt = $this->con->prepare("INSERT INTO images (description, image,product_name) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $desc, $name,$product_name);
        if ($stmt->execute())
            return true;
        return false;
    }

    public function getAllFiles()
    {
        $stmt = $this->con->prepare("SELECT description,product_name, image FROM images ORDER BY id DESC");
        $stmt->execute();
        $stmt->bind_result($desc,$product_name, $image);

        $images = array();

        while ($stmt->fetch()) {

            $temp = array();
            $absurl = 'http://' . gethostbyname(gethostname()) . '/ImageUploadApi' . UPLOAD_PATH . $image;
            // $temp['id'] = $id;
            $temp['desc'] = $desc;
			$temp['product_name'] = $product_name;

            $temp['image'] = $absurl;
            array_push($images, $temp);
        }

        return $images;
    }
	
	
	
	
	 public function getonefile($id)
    {
        $stmt = $this->con->prepare("SELECT * FROM images where id= $_POST[$id]");
        $stmt->execute();
        $stmt->bind_result($id, $desc,$product_name, $image);

        $sellerimage = array();

        while ($stmt->fetch()) {

            $temp = array();
            $absurl = 'http://' . gethostbyname(gethostname()) . '/ImageUploadApi' . UPLOAD_PATH . $image;
            $temp['id'] = $id;
            $temp['desc'] = $desc;
			$temp['product_name'] = $product_name;

            $temp['image'] = $absurl;
            array_push($sellerimage, $temp);
        }

        return $sellerimage;
    }
/*
$sql="UPDATE table set column='".$_POST['Customer']."',number='".$_POST['number']."'where id='".$_POST['id']."'";


*/
} 
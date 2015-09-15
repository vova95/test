<?php
	class imageGrid
{

    private $realWidth;
    private $realHeight;
    private $gridWidth;
    private $gridHeight;
    private $image;

    public function __construct($realWidth, $realHeight, $gridWidth, $gridHeight)
    {
        $this->realWidth = $realWidth;
        $this->realHeight = $realHeight;
        $this->gridWidth = $gridWidth;
        $this->gridHeight = $gridHeight;

        // create destination image
        $this->image = imagecreatetruecolor($realWidth, $realHeight);

        // set image default background
        $white = imagecolorallocate($this->image, 255, 255, 255);
        imagefill($this->image, 0, 0, $white);
    }

    public function __destruct()
    {
        imagedestroy($this->image);
    }

    public function display()
    {
        header("Content-type: image/png");
        imagepng($this->image);
    }
    public function demoGrid()
	{
	    $black = imagecolorallocate($this->image, 0, 0, 0);
	    imagesetthickness($this->image, 3);
	    $cellWidth = ($this->realWidth - 1) / $this->gridWidth;   // note: -1 to avoid writting
	    $cellHeight = ($this->realHeight - 1) / $this->gridHeight; // a pixel outside the image
	    for ($x = 0; ($x <= $this->gridWidth); $x++)
	    {
	        for ($y = 0; ($y <= $this->gridHeight); $y++)
	        {
	            imageline($this->image, ($x * $cellWidth), 0, ($x * $cellWidth), $this->realHeight, $black);
	            imageline($this->image, 0, ($y * $cellHeight), $this->realWidth, ($y * $cellHeight), $black);
	        }
	    }
	}
	public function putImage($img, $sizeW, $sizeH, $posX, $posY)
	{
	    // Cell width
	    $cellWidth = $this->realWidth / $this->gridWidth;
	    $cellHeight = $this->realHeight / $this->gridHeight;

	    // Conversion of our virtual sizes/positions to real ones
	    $realSizeW = ceil($cellWidth * $sizeW);
	    $realSizeH = ceil($cellHeight * $sizeH);
	    $realPosX = ($cellWidth * $posX);
	    $realPosY = ($cellHeight * $posY);

	    // Copying the image
	    imagecopyresampled($this->image, $img, $realPosX, $realPosY, 0, 0, $realSizeW, $realSizeH, imagesx($img), imagesy($img));
	}
	public function url_get_contents ($Url) {
	    if (!function_exists('curl_init')){ 
	        die('CURL is not installed!');
	    }
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $Url);
	    curl_setopt($ch, CURLOPT_PROXY, '192.168.5.111:3128');
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $output = curl_exec($ch);
	    curl_close($ch);
	    return $output;
	}

	public function copyIMGS() {
		$images = array();
		$images[] = 'https://pp.vk.me/c410118/v410118197/7533/w1sahwpcIGQ.jpg';
		$images[] = 'https://pp.vk.me/c624029/v624029197/5d22/aFqYSgFiYyI.jpg';
		foreach ($images as $key => $value) {
			$content = $this->url_get_contents($value);
			file_put_contents('tmp/images/array[' . $key . '].jpg', $content);
		}
		
	}
	public function getAllImages() {
		$directory = "tmp/images/";
		$images = glob($directory . "*.jpg");

		return $images;
	}
}

$imageGrid = new imageGrid(800, 600, 10, 10);
$imageGrid->demoGrid();
$images = $imageGrid->getAllImages();
foreach ($images as $key => $image) {
	$img = imagecreatefromjpeg($image);
	$imageGrid->putImage($img, 1, 1, 2, 5);
}

$imageGrid->display();
$imageGrid->copyIMGS();
$imageGrid->getAllImages();
?>
<?php include("./password_protect.php"); ?>
<?php
$message=null;
#error_reporting(E_ALL);
#ini_set('display_errors', 1);
//create a new clean array
$files = [];
foreach ($_FILES as $key=>$value) {
    if ($value['size']!=0 && $value['error']==0){
        $n=count($files);
        $files[$n]=$value;
        $files[$n]['name']=$key . ".png";
        $n+=1;
    }
}


$target_dir = "../images/drinks/";
foreach ($files as $key=>$value) {


    $target_file = $target_dir . basename($files[$key]["name"]);
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    // Check if image file is a actual image or fake image


    if (isset($_POST["submit"])) {

        $check = getimagesize($files[$key]["tmp_name"]);
        if ($check !== false) {
            /*echo "File is an image - " . $check["mime"] . ".";*/
            $uploadOk = 1;
        } else {
            $message= "File is not an image.";
            $uploadOk = 0;
        }
    }


    // Check file size
    $max_size = 300000;
    if ($files[$key]["size"] > $max_size) {
        $message= "Sorry, your file is too large. <br>" . $max_size / 1000 . "kb is allowed";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if ($imageFileType != "png") {
        $message= "Sorry, PNG files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        $message="<br>Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        unlink("$target_file");
        if (move_uploaded_file($files[$key]["tmp_name"], $target_file)) {
            /*echo "The file " . basename($files[$key]["name"]) . " has been uploaded.";*/

            $im = new imagick($target_file);
            $imageprops = $im->getImageGeometry();
            $width = $imageprops['width'];
            $height = $imageprops['height'];
            if($width > $height){
                $newHeight = 200;
                $newWidth = (200 / $height) * $width;
            }else{
                $newWidth = 200;
                $newHeight = (200 / $width) * $height;
            }
            $im->resizeImage($newWidth,$newHeight, imagick::FILTER_LANCZOS, 0.9, true);
            $im->cropImage (200,200,0,0);
            $im->writeImage( $target_file );
            /* echo '<img src=' . $target_file . '>';*/



        } else {
            $message= "<br>Sorry, there was an error uploading your file.";
        }
    }
    /*echo "<br><br><a href='./index.php'>Terug naar invoer</a>";*/
}
?>
<!DOCTYPE html>
<html lang="en" ng-app="barApp">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/bar.css" rel="stylesheet">

		<title>Marktwerking - Bar</title>
	</head>
	<body ng-controller="barController as bar">
    <div class="navbar navbar-default navbar-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div id="navbar" class="navbar-right navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="../index.php" type="button">Beamer</a></li>
                    <li><a data-target="#financial" data-toggle="modal" type="button">Financial</a></li>
                    <li><a href="./backoffice.php" type="button">Settings</a></li>
                    <li><a href="./index.php?logout">Uitloggen</a></li>
                </ul>
            </div>
            <div class="nav navbar-nav navbar-left">
			
                <div class="navbar-text navbar-marktwerking" id="timer" >Mode: Marktwerking | 04 min. 08 sec.</div>
                <div class="navbar-text navbar-marktwerking">Ronde {{ bar.round }} / {{bar.settings.time_total*60/bar.settings.time_round}}</div>
                <div class="navbar-text navbar-bar" id="time" >Mode: Bar | 14:00:00</div>
                <div class="navbar-text navbar-streeplijst">Mode: Streeplijst | {{bar.settings.limit | currency:"&euro;" }} - {{bar.orderInfo.total | currency:"&euro;" }} = {{bar.orderInfo.diff | currency:"&euro;" }}</div>
            </div>
        </div>
    </div>
		<div class="container">
			<div class="row">
				<div class="col-md-5">
                    <div class="orderlist row">
                        <div class="col-xs-12">
                            <div class="row checkout-header">
                                <div class="col-xs-12">Order:</div>
                            </div>
                            <hr>
                            <div class="checkout-content">
                                <div class="row checkout-item" ng-repeat="item in bar.order">
                                    <div class="col-xs-2">{{ item.times }}x</div>
                                    <div class="col-xs-5">{{ item.name }}</div>
                                    <div class="col-xs-2">{{ (item.times * item.price) | currency:"&euro;" }}</div>
                                    <div class="col-xs-3 ">
                                        <div class="btn btn-warning checkout-item-mod" ng-click="subtractFromOrder(item)">-</div>
                                        <div class="btn btn-danger checkout-item-mod" ng-click="deleteFromOrder(item)">X</div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row checkout-total">
                                <!-- list of total drinks to be payed -->
                                <div class="col-xs-7">{{ getTotalItems() }} items</div>
                                <div class="col-xs-5 checkout-total-price" >{{ getTotalPrice() | currency:"&euro;" }}</div>
                                <div class="col-xs-12 btn btn-success btn-group-justified checkout-order-pay" ng-click="submitOrder()">Betalen</div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 orderlist orderlist-prev">
                            <div class="row">
                                <div class="col-xs-12">Vorige bestelling:</div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-xs-7">{{bar.order_prev.amount}} items</div>
                                <div class="col-xs-5 checkout-previous-price">{{ bar.order_prev.total| currency:"&euro;" }}</div>
                            </div>
                        </div>
                    </div>

				</div>

				<div class="col-md-7">
					<div class="row category">
						<div class="col-md-12">
							<div class="btn-group btn-group-justified cat">
								<a class="btn btn-primary btn-sq" ng-click="bar.itemFilter = ''">All</a>
                                <a class="btn btn-primary btn-sq" ng-repeat="cat in bar.categories" ng-click="bar.itemFilter = cat.id" ng-if="cat.name !== ''">{{ cat.name }}</a>
								<!-- using JS more cat buttons will be created-->
							</div>
						</div>
					</div>
                    <div  class="row">
						<div class="col-md-12">
                            <div class="products">
                                <div class="btn btn-sq-prod"
                                     ng-repeat="item in bar.items | filter : item.active==True | matchCategory: bar.itemFilter | orderBy:'name'"
                                     ng-click="addToOrder(item)"
                                     ng-style="{'background-image': 'linear-gradient( rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.7) ),url('+'../images/drinks/' + item.id+'.png' + ')'}">
                                    <div class="btn-content">
                                        {{ item.name }}
                                        <div class="btn-sq-prod-price">{{item.price | currency:"€":0}}</div>
                                    </div>
                                </div>
                                <!-- using JS buttons will be created-->
                            </div>
                        </div>
					</div>
				</div>
			</div>
		</div><!-- Modal -->
		<div class="modal fade" id="financial" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" data-dismiss="modal" type="button">&times;</button>
						<h4 class="modal-title">
							Total financial data
						</h4>
					</div>
					<div class="modal-body">
						<p>
							$
						</p>
					</div>
					<div class="modal-footer">
						<button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
					</div>
				</div>
			</div>
		</div><!-- Modal-->
        <?php if($message != null): ?>
        <div class="footer">
            <div class="footer-message-image">
                <hr>
                <?=$message; ?>
                <hr>
            </div>
        </div>
        <?php endif; ?>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.5/angular.js"></script>
        <script src="../js/bar.js"></script>


    </body>
</html>

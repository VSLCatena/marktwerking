<?php include("./password_protect.php"); ?>
<!DOCTYPE html>
<html lang="en" ng-app="barApp">
	<head>
		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/bar.css" rel="stylesheet">

		<title></title>
	</head>
	<body ng-controller="barController as bar">
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-1 view">
					<div class="btn-group-vertical">
                        <a class="btn btn-primary btn-sq" href="../index.php" type="button">Beamer</a>
						<button class="btn btn-primary btn-sq" data-target="#financial" data-toggle="modal" type="button">Financial</button>
						<button class="btn btn-primary btn-sq" data-target="#settings" data-toggle="modal" type="button">Settings</button>
                        <a class="btn btn-primary btn-sq" href="./index.php?logout">Uitloggen</a>
					</div>
				</div>
				<div class="col-md-4 orderlist">
                    <div class="row checkout-header">
                        <div class="col-xs-5">Naam:</div>
                        <div class="col-xs-2">Aantal</div>
                        <div class="col-xs-3">Prijs</div>
                    </div>
                    <hr>
                    <div class="checkout-content">
                    <div class="row checkout-item" ng-repeat="item in bar.order">
                        <div class="col-xs-5">{{ item.name }}</div>
                        <div class="col-xs-2">{{ item.times }}</div>
                        <div class="col-xs-2">{{ (item.times * item.price) | currency:"&euro;" }}</div>
                        <div class="col-xs-3 ">
                            <div class="btn btn-warning checkout-item-mod" ng-click="subtractFromOrder(item)">-</div>
                            <div class="btn btn-danger checkout-item-mod" ng-click="deleteFromOrder(item)">X</div>
                        </div>
                    </div>
                    </div>
					<div class="row checkout-total">
                        <!-- list of total drinks to be payed -->
                        <div class="col-xs-offset-5 col-xs-2">{{ getTotalItems() }}</div>
                        <div class="col-xs-3 checkout-total-price" >{{ getTotalPrice() | currency:"&euro;" }}</div>
					    <div class="col-xs-1 btn btn-success btn-group-justified checkout-order-pay">Betalen</div>
                    </div>
				</div>

				<div class="col-md-7">

					<div class="row category">
						<div class="col-md-12">
							<div class="btn-group btn-group-justified cat">
								<a class="btn btn-primary btn-sq" ng-click="bar.itemFilter = ''">All</a>
                                <a class="btn btn-primary btn-sq" ng-repeat="cat in bar.categories" ng-click="bar.itemFilter = cat.id">{{ cat.name }}</a>
								<!-- using JS more cat buttons will be created-->
							</div>
						</div>
					</div>
                    <br>
					<div  class="row">
						<div class="col-md-12">
                            <div class="products">
                                <div class="btn btn-sq-prod"
                                     ng-repeat="item in bar.items | filter : item.active==True | matchCategory: bar.itemFilter | orderBy:'name'"
                                     ng-click="addToOrder(item)"
                                     ng-style="{'background-image': 'linear-gradient( rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.7) ),url('+'../images/drinks/' + item.id+'.png' + ')'}">
                                    {{ item.name }}
                                    <div class="btn-sq-prod-price">{{item.price | currency:"€":0}}</div>
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
		<div class="modal fade " id="settings" role="dialog">
			<div class="modal-dialog modal-xlg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" data-dismiss="modal" type="button">&times;</button>
						<h4 class="modal-title">
                            <ul class="nav nav-tabs">
                                <li class="active"><a data-toggle="tab" href="#general">Algemeen</a></li>
                                <li><a data-toggle="tab" href="#drinks">Artikelen</a></li>
                                <li><a data-toggle="tab" href="#category">Categorieen</a></li>
                                <li><a data-toggle="tab" href="#drinks-mix">Gemixte artikelen</a></li>
                                <li><a data-toggle="tab" href="#rounds">Marktwerking/Ronden</a></li>
                                <li><a data-toggle="tab" href="#stock">Voorraad</a></li>
                            </ul>
						</h4>
					</div>
					<div class="modal-body">
                        <div class="tab-content">
                            <div id="general" class="tab-pane fade in active">

                                <div class="radio">
                                    <label><input type="radio" name="settings-type-bar" ng-checked="">Normale Bar</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="settings-type-bar" ng-checked="">Marktwerking</label>
                                </div>
                                <div class="radio">
                                    <label><input type="radio" name="settings-type-bar" ng-checked="">Streeplijst</label>
                                </div>
                            </div>

                            <div id="rounds" class="tab-pane fade">

                                <form>
                                    <div class="input-group">
                                        <span class="input-group-addon">Tijd ronde (min):</span>
                                        <input id="time-round" type="text" class="form-control" name="time-round" ng-model="bar.settings.time_round">
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-addon">Tijd totaal (uur):</span>
                                        <input id="time-total" type="text" class="form-control" name="time-total" placeholder="" ng-model="bar.settings.time_total">
                                    </div>
                                    <br>
                                    <div class="input-group">
                                        <span class="input-group-addon">Aantal ronden:</span>
                                        <input id="round-total" type="text" class="form-control" name="round-total" placeholder="" value="{{bar.settings.time_total*60/bar.settings.time_round}}" disabled>
                                    </div>

                                </form>
                            </div>

                            <div id="drinks" class="tab-pane fade">
                                <div class="row">
                                    <div class="col-sm-1">ID</div>
                                    <div class="col-sm-3">Naam</div>
                                    <div class="col-sm-1">Prijs</div>
                                    <div class="col-sm-1">Minimum</div>
                                    <div class="col-sm-1">Actief</div>
                                    <div class="col-sm-2">Foto uploaden</div>
                                    <div class="col-sm-1">Link foto</div>
                                    <div class="col-sm-1">Verwijderen</div>
                                    <div class="col-sm-1">&nbsp;</div>
                                </div>
                                <br>
                                <hr>



                                <form action="index.php" method="post" enctype="multipart/form-data">
                                    <div class="input-append row" ng-repeat="item in bar.items">
                                        <div class="col-sm-1" >{{item.id}}</div>
                                        <input class="col-sm-3" type="text" ng-model="item.name">
                                        <input type="text" class="settings-item-price col-sm-1" ng-model="item.start_price">
                                        <input type="text" class="settings-item-price col-sm-1" ng-model="item.minimum_price">
                                        <label class="col-sm-1"><input type="checkbox" ng-model="item.active" >Actief</label>
                                        <input type="file" name="{{item.id}}" id="fileToUpload_{{item.id}}" class="btn col-sm-2"></input>
                                        <div class="col-sm-1">
                                            <a href='../images/drinks/{{item.id}}.png'><img class=" settings-item-img" ng-src='../images/drinks/{{item.id}}.png' alt="no image"></img></a>
                                        </div>
                                        <button class="btn col-sm-1" ng-click="settingsItemRemove($index)">X</button>

                                    </div>
                                    <button class="btn btn-small" type="button" ng-click="settingsItemAdd(item.name,item.price,item.price_min,item.category,item.active )">Nieuwe rij toevoegen</button>
                                    <input class="btn btn-small btn-info" type="submit" value="Foto's uploaden" name="submit">
                                </form>
                            </div>


                            <div id="drinks-mix" class="tab-pane fade">

                            </div>
                            <div id="category" class="tab-pane fade">
                                <div class="row" ng-repeat="category in bar.categories">
                                    <input class="col-sm-1 col-sm-offset-1" type="text" ng-model="category.name">

                                </div>
                                <div class="row">
                                    <div class="input-append col-md-4" ng-repeat="item in bar.items">
                                        <div class="h4" >{{item.name}}</div>
                                        <select name="category" class="form-control" id="settingsItem{{ item.id }}" ng-model="dummy" ng-change="updateItemCategories(item)" multiple>
                                           <option value="{{ category.id }}" ng-repeat="category in bar.categories" ng-selected="item.categories.indexOf(category.id) !== -1">{{category.name}}</option>
                                        </select>

                                    </div>
                                </div>
                            </div>
                            <div id="stock" class="tab-pane fade in active">

                            <p>hier komt ooit de voorraadinformatie</p>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer">
                        <p class="pull-left">Let op! Aanpassingen in de instellingen worden direct toegepast</p>
                        <button class="btn btn-default" data-dismiss="modal" type="button">Sluiten</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="footer">
            <hr>
            <div class="footer-message">
                <div class="footer-message-image">
                    <!--FULL IMAGE UPLOAD-->
                    <?php
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
                                echo "File is not an image.";
                                $uploadOk = 0;
                            }
                        }


            // Check file size
                        $max_size = 300000;
                        if ($files[$key]["size"] > $max_size) {
                            echo "Sorry, your file is too large. <br>" . $max_size / 1000 . "kb is allowed";
                            $uploadOk = 0;
                        }
            // Allow certain file formats
                        if ($imageFileType != "png") {
                            echo "Sorry, PNG files are allowed.";
                            $uploadOk = 0;
                        }
            // Check if $uploadOk is set to 0 by an error
                        if ($uploadOk == 0) {
                            echo "<br>Sorry, your file was not uploaded.";
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
                                echo "<br>Sorry, there was an error uploading your file.";
                            }
                        }
                        /*echo "<br><br><a href='./index.php'>Terug naar invoer</a>";*/
                    }
                    ?>
                </div>
            </div>
            <hr>
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.32/angular.js"></script>
        <script src="../js/bar.js"></script>


    </body>
</html>

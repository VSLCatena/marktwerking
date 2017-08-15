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
	<body>
		<div class="container-fluid" ng-controller="barController as bar">
			<div class="row">
				<div class="col-md-1 view">
					<div class="btn-group-vertical">
						<button class="btn btn-primary btn-sq" type="button">view_01</button>
						<button class="btn btn-primary btn-sq" type="button">view_02</button>
						<button class="btn btn-primary btn-sq" data-target="#financial" data-toggle="modal" type="button">Financial</button>
						<button class="btn btn-primary btn-sq" data-target="#settings" data-toggle="modal" type="button">Settings</button>
					</div>
				</div>
				<div class="col-md-4 orderlist">
                    <div class="row">
                        <div class="col-xs-5">Naam:</div>
                        <div class="col-xs-2">Aantal</div>
                        <div class="col-xs-3">Prijs</div>
                    </div>
                    <div class="row" ng-repeat="item in bar.order">
                        <div class="col-xs-5">{{ item.name }}</div>
                        <div class="col-xs-2">{{ item.times }}</div>
                        <div class="col-xs-3">{{ (item.times * item.price)/100 | currency:"&euro;" }}</div>
                        <div class="col-xs-1">X</div>
                    </div>
					<div class="row checkout-total">
                        <!-- list of total drinks to be payed -->
                        <div class="col-md-offset-5 col-md-2">{{ getTotalItems() }}</div>
                        <div class="col-md-3">{{ getTotalPrice()/100 | currency:"&euro;" }}</div>
					</div>
				</div>

				<div class="col-md-7">

					<div class="row category">
						<div class="col-md-12">
							<div class="btn-group btn-group-justified cat">
								<a class="btn btn-primary btn-sq" ng-click="bar.itemFilter = ''">All</a>
                                <a class="btn btn-primary btn-sq" ng-repeat="cat in bar.categories" ng-click="bar.itemFilter = cat">{{ cat }}</a>
								<!-- using JS more cat buttons will be created-->
							</div>
						</div>
					</div>

					<div  class="row products ">
						<div class="col-md-12">
                            <button ng-repeat="item in bar.items | orderBy:'name' | filter:{ category: bar.itemFilter }" ng-click="addToOrder(item)" class="btn-sq-prod">{{ item.name }}</button>
                            <!-- using JS buttons will be created-->
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
		<div class="modal fade" id="settings" role="dialog">
			<div class="modal-dialog modal-lg">
				<!-- Modal content-->
				<div class="modal-content">
					<div class="modal-header">
						<button class="close" data-dismiss="modal" type="button">&times;</button>
						<h4 class="modal-title">
							Settings
						</h4>
					</div>
					<div class="modal-body">
						<p>
							Length of rounds, amount of rounds, pricing, etc
							
							<?php
								print '<pre>';
								print_r($result); 
								print '</pre>';
							?>
							
						</p>
					</div>
					<div class="modal-footer">
						<button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
					</div>
				</div>
			</div>
		</div>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.32/angular.js"></script>
        <script src="../js/bar.js"></script>
	</body>
</html>
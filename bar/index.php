<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<link href="../css/bar.css" rel="stylesheet">
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		
		<title></title>
	</head>
	<?php

	
	?>
	
	<body>
		<div class="container-fluid">
			<div class="row">
				<div class="col-md-1 view">
					<div class="btn-group-vertical">
						<button class="btn btn-primary btn-sq" type="button">view_01</button> 
						<button class="btn btn-primary btn-sq" type="button">view_02</button> 
						<button class="btn btn-primary btn-sq" data-target="#financial" data-toggle="modal" type="button">Financial</button> 
						<button class="btn btn-primary btn-sq" data-target="#settings" data-toggle="modal" type="button">Settings</button>
					</div>
				</div>
				<div class="col-md-4 calc"> 
					<div id="checkout-list" class="checkout-list">
					<!-- list of each drinks to be payed -->
					
					</div>
					<div class="checkout-total">
					<!-- list of total drinks to be payed -->
					<div class="col-md-8"></div>
					<div class="col-md-2" id="checkoutDrink_all-amount">0</div>
					<div class="col-md-2" id="checkoutDrink_all-total">0</div>
					</div>
				</div>

				<div class="col-md-7">

					<div class="row category">
						<div class="col-md-12">
							<div id = "btn_cat_menu" class="btn-group btn-group-justified cat">
								<a id = "btn_cat_all" class="btn btn-primary btn-sq">All</a> 								
								<!-- using JS more cat buttons will be created-->
							</div>
						</div>
					</div>
					
					<div  class="row products ">
						<div class="col-md-12">
							<div id = "btn_prod_list">
								<!-- using JS buttons will be created-->	
								<!--  <button ng-repeat="drinks in drinks | filter:catFilter">{{drinks.name}}</button> -->
								
							
							</div>
						</div>
					</div>
				</div>
			</div>
		</div><!-- Modal -->
		<div class="modal fade" id="financial" role="dialog">
			<div class="modal-dialog">
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
			<div class="modal-dialog">
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
		
		<script src="../js/bar.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.32/angular.js"></script>
		<script src="../js/angular_functions.js"></script>
	</body>
</html>
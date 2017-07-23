<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1" name="viewport">
		<link href="./css/index.css" rel="stylesheet">
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js">
		</script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js">
		</script>
		<title></title>
	</head>
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
					Calculator
				</div>
				<div class="col-md-7">
					<div class="row category">
						<div class="col-md-12">
							<div class="btn-group btn-group-justified">
								<a class="btn btn-primary btn-sq" data-toggle="tab" href="#Cat_ALL">ALL</a> 
								<a class="btn btn-primary btn-sq" data-toggle="tab" href="#Cat_01">Cat_01</a> 
								<a class="btn btn-primary btn-sq" data-toggle="tab" href="#Cat_02">Cat_02</a> 
								<a class="btn btn-primary btn-sq" data-toggle="tab" href="#Cat_03">Cat_03</a> 
								<a class="btn btn-primary btn-sq" data-toggle="tab" href="#Cat_04">Cat_04</a>
							</div>
						</div>
					</div>
					<div class="row products">
						<div class="col-md-12">
							<!-- nav-tabs content-->
							<div class="tab-content">
								<div class="tab-pane fade in active" id="Cat_ALL">
									<p>
										Some content.
									</p>
									<div class="btn-group-vertical btn-sq-prod">
										<button class="btn btn-primary prod" type="button">prod_01</button> 
										<button class="btn btn-primary prod_decr" type="button">prod_01_decr</button>
									</div>
								</div>
								<div class="tab-pane fade" id="Cat_01">
									<p>
										Some content in cat 1.
									</p>
								</div>
								<div class="tab-pane fade" id="Cat_02">
									<p>
										Some content in cat 2.
									</p>
								</div>
								<div class="tab-pane fade" id="Cat_03">
									<p>
										Some content in cat 3.
									</p>
								</div>
								<div class="tab-pane fade" id="Cat_04">
									<p>
										Some content in cat 4.
									</p>
								</div>
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
						</p>
					</div>
					<div class="modal-footer">
						<button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
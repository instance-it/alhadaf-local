<?php

?>
<!doctype html>
<html>
    <head>

	<link rel="stylesheet" href="bootstrap-material-datetimepicker.css">

	</head>
	<body>
		<div class="container well">
			<div class="row">
				<div class="col-md-6">
					<h1>Bootstrap Material DatePicker</h1>
				</div>
			</div>
		</div>
		<div class="container well">
			<div class="row">
				<div class="col-md-6">
					<h2>Date Picker</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-control-wrapper">
						<input type="text" id="date" class="form-control floating-label" placeholder="Date">
					</div>
				</div>
				<div class="col-md-6">
					<code>
						<p>Code</p>
						$('#date').bootstrapMaterialDatePicker({ weekStart : 0, time: false });
					</code>
				</div>
			</div>
		</div>
		<div class="container well">
			<div class="row">
				<div class="col-md-6">
					<h2>Time Picker</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-control-wrapper">
						<input type="text" id="time" class="form-control floating-label" placeholder="Time">
					</div>
				</div>
				<div class="col-md-6">
					<code>
						<p>Code</p>
						$('#time').bootstrapMaterialDatePicker({ date: false });
					</code>
				</div>
			</div>
		</div>
		<div class="container well">
			<div class="row">
				<div class="col-md-6">
					<h2>Date Time Picker</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-control-wrapper">
						<input type="text" id="date-format" class="form-control floating-label" placeholder="Begin Date Time">
					</div>
				</div>
				<div class="col-md-6">
					<code>
						<p>Code</p>
						$('#date-format').bootstrapMaterialDatePicker({ format : 'dddd DD MMMM YYYY - HH:mm' });
					</code>
				</div>
			</div>
		</div>
		<div class="container well">
			<div class="row">
				<div class="col-md-6">
					<h2>French Locales (Week starts at Monday)</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-control-wrapper">
						<input type="text" id="date-fr" class="form-control floating-label" value="18/03/2015 08:00" placeholder="Date de début">
					</div>
				</div>
				<div class="col-md-6">
					<code>
						<p>Code</p>
						$('#date-fr').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY HH:mm', lang : 'fr', weekStart : 1, cancelText : 'ANNULER' });
					</code>
				</div>
			</div>
		</div>
		<div class="container well">
			<div class="row">
				<div class="col-md-6">
					<h2>Min Date set</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-control-wrapper">
						<input type="text" id="min-date" class="form-control floating-label" placeholder="Start Date">
					</div>
				</div>
				<div class="col-md-6">
					<code>
						<p>Code</p>
						$('#min-date').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY HH:mm', minDate : new Date() });
					</code>
				</div>
			</div>
		</div>
		<div class="container well">
			<div class="row">
				<div class="col-md-6">
					<h2>Events</h2>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-12">
							<div class="form-control-wrapper">
								<input type="text" id="date-start" class="form-control floating-label" placeholder="Start Date">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="form-control-wrapper">
								<input type="text" id="date-end" class="form-control floating-label" placeholder="End Date">
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="row">
						<div class="col-md-12">
							<code>
								<p>Code</p>
								$('#date-end').bootstrapMaterialDatePicker({ weekStart : 0 });<br />
								$('#date-start').bootstrapMaterialDatePicker({ weekStart : 0 }).on('change', function(e, date)<br />
								{<br />
									$('#date-end').bootstrapMaterialDatePicker('setMinDate', date);<br />
								});
							</code>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="jquery-3.5.1.min.js"></script>
		<script src="moment.min.js"></script> 
		<script src="bootstrap-material-datetimepicker.js"></script>
	

		<script type="text/javascript">
		$(document).ready(function()
		{
			$('#date').bootstrapMaterialDatePicker
			({
				time: false,
				clearButton: true,
				format: 'DD/MM/YYYY'
			});

			$('#time').bootstrapMaterialDatePicker
			({
				date: false,
				shortTime: true,
				format: 'hh:mm A'
			});

			$('#date-format').bootstrapMaterialDatePicker
			({
				format: 'DD/MM/YYYY - hh:mm A',
				shortTime: true,
				nowButton : true,
			});

			$('#date-fr').bootstrapMaterialDatePicker
			({
				format: 'DD/MM/YYYY HH:mm',
				lang: 'fr',
				weekStart: 1, 
				cancelText : 'ANNULER',
				nowButton : true,
				switchOnClick : true
			});

			$('#date-end').bootstrapMaterialDatePicker
			({
				weekStart: 0, format: 'DD/MM/YYYY HH:mm'
			});
			$('#date-start').bootstrapMaterialDatePicker
			({
				weekStart: 0, format: 'DD/MM/YYYY HH:mm', shortTime : true
			}).on('change', function(e, date)
			{
				$('#date-end').bootstrapMaterialDatePicker('setMinDate', date);
			});

			$('#min-date').bootstrapMaterialDatePicker({ format : 'DD/MM/YYYY HH:mm', minDate : new Date() });

		});
		</script>
	</body>
</html>

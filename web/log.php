<?php
$pageTitle = "Updates";
include('_includes/header.php');
?>


			<div>
				<ul class="breadcrumb">
					<li><a href="index.php">Home</a> <span class="divider">/</span></li>
					<li class="active"><?php echo $pageTitle; ?></li>
				</ul>
			</div>

			<div class="row-fluid sortable">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-list-alt"></i> Log File</h2>
					</div>
					<div class="box-content">

						<?php
							$output = shell_exec('cat /var/log/svxlink');
							echo "<pre>$output</pre>";
						?>



						
					</div>
				</div><!--/span-->
			
			</div><!--/row-->

    
<?php include('_includes/footer.php'); ?>
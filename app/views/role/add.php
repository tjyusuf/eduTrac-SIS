<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * Add Role View
 *  
 * PHP 5.4+
 *
 * eduTrac(tm) : Student Information System (http://www.7mediaws.org/)
 * @copyright (c) 2013 7 Media Web Solutions, LLC
 * 
 * @link        http://www.7mediaws.org/
 * @since       3.0.0
 * @package     eduTrac
 * @author      Joshua Parker <josh@7mediaws.org>
 */
$app = \Liten\Liten::getInstance();
$app->view->extend('_layouts/dashboard');
$app->view->block('dashboard');
$message = new \app\src\Messages;
?>

<script type="text/javascript">
$(".panel").show();
setTimeout(function() { $(".panel").hide(); }, 10000);
</script>

<ul class="breadcrumb">
	<li><?=_t( 'You are here');?></li>
	<li><a href="<?=url('/');?>dashboard/<?=bm();?>" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
	<li class="divider"></li>
	<li><a href="<?=url('/');?>role/<?=bm();?>" class="glyphicons rotation_lock"><i></i> <?=_t( 'Manage Roles' );?></a></li>
	<li class="divider"></li>
	<li><?=_t( 'Add Role' );?></li>
</ul>

<h3><?=_t( 'Add Role' );?></h3>
<div class="innerLR">
    
    <?=$message->flashMessage();?>

	<!-- Form -->
	<form class="form-horizontal margin-none" action="<?=url('/');?>role/add/" id="validateSubmitForm" method="post" autocomplete="off">
		
		<!-- Widget -->
		<div class="widget widget-heading-simple widget-body-gray">
		
			<!-- Widget heading -->
			<div class="widget-head">
				<h4 class="heading"><font color="red">*</font> <?=_t( 'Indicates field is required' );?></h4>
			</div>
			<!-- // Widget heading END -->
			
			<div class="widget-body">
					
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label" for="roleName"><font color="red">*</font> <?=_t( 'Role Name' );?></label>
							<div class="col-md-12"><input class="form-control" name="roleName" type="text" required/></div>
						</div>
						<!-- // Group END -->
						
						<!-- Table -->
						<table class="dynamicTable tableTools table table-striped table-bordered table-condensed table-white">
						
							<!-- Table heading -->
							<thead>
								<tr>
									<th><?=_t( 'Permission' );?></th>
									<th><?=_t( 'Allow' );?></th>
								</tr>
							</thead>
							<!-- // Table heading END -->
							
							<tbody>
								<?php rolePerm(); ?>
							</tbody>
				
					</table>
					<!-- // Table END -->
			
				<hr class="separator" />
				
				<!-- Form actions -->
				<div class="form-actions">
					<button type="submit" name="Submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i><?=_t( 'Submit' );?></button>
					<button type="button" class="btn btn-icon btn-primary glyphicons circle_minus" onclick="window.location='<?=url('/');?>role/<?=bm();?>'"><i></i><?=_t( 'Cancel' );?></button>
				</div>
				<!-- // Form actions END -->
				
			</div>
		</div>
		<!-- // Widget END -->
		
	</form>
	<!-- // Form END -->
	
</div>	
	
		
		</div>
		<!-- // Content END -->
<?php $app->view->stop(); ?>
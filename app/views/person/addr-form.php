<?php if ( ! defined('BASE_PATH') ) exit('No direct script access allowed');
/**
 * Add Address View
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
	<li><?=_t( 'You are here' );?></li>
	<li><a href="<?=url('/');?>dashboard/<?=bm();?>" class="glyphicons dashboard"><i></i> <?=_t( 'Dashboard' );?></a></li>
	<li class="divider"></li>
	<li><a href="<?=url('/');?>nae/<?=bm();?>" class="glyphicons search"><i></i> <?=_t( 'Search Person' );?></a></li>
    <li class="divider"></li>
    <li><a href="<?=url('/');?>nae/<?=_h($addr[0]['personID']);?>/<?=bm();?>" class="glyphicons user"><i></i> <?=get_name(_h((int)$addr[0]['personID']));?></a></li>
    <li class="divider"></li>
    <li><a href="<?=url('/');?>nae/adsu/<?=_h($addr[0]['personID']);?>/<?=bm();?>" class="glyphicons vcard"><i></i> <?=_t( 'Address Summary' );?></a></li>
    <li class="divider"></li>
	<li><?=_t( 'Edit Address' );?></li>
</ul>

<h3><?=get_name(_h((int)$addr[0]['personID']));?> <?=_t( "ID: " );?><?=_h($addr[0]['personID']);?></h3>
<div class="innerLR">
    
    <?=$message->flashMessage();?>

	<!-- Form -->
	<form class="form-horizontal margin-none" action="<?=url('/');?>nae/addr-form/<?=_h($addr[0]['personID']);?>/" id="validateSubmitForm" method="post" autocomplete="off">
		
		<!-- Widget -->
		<div class="widget widget-heading-simple widget-body-gray">
		
			<!-- Widget heading -->
			<div class="widget-head">
				<h4 class="heading"><font color="red">*</font> <?=_t( 'Indicates field is required' );?></h4>
			</div>
			<!-- // Widget heading END -->
			
			<div class="widget-body">
			
				<!-- Row -->
				<div class="row">
					<!-- Column -->
					<div class="col-md-6">
						
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label"><?=_t( 'First Name' );?></label>
							<div class="col-md-8">
								<input class="form-control" type="text" readonly value="<?=_h($addr[0]['fname']);?>" />
							</div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Last Name' );?></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" readonly value="<?=_h($addr[0]['lname']);?>" />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Middle Initial' );?></label>
                            <div class="col-md-2">
                                <input class="form-control" type="text" readonly value="<?=_h($addr[0]['mname']);?>" />
                            </div>
                        </div>
                        <!-- // Group END -->
						
						<!-- Group -->
						<div class="form-group">
							<label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Address1' );?></label>
							<div class="col-md-8">
								<input class="form-control" type="text" name="address1" required />
							</div>
						</div>
						<!-- // Group END -->
						
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Address2' );?></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="address2" />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'City' );?></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" name="city" required />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'State' );?></label>
                            <div class="col-md-8">
                                <select name="state" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true" required>
                                    <option value="">&nbsp;</option>
                                    <?php table_dropdown('state',null,'code','code','name'); ?>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Zip Code' );?></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="zip" required />
                            </div>
                        </div>
                        <!-- // Group END -->
						
					</div>
					<!-- // Column END -->
					
					<!-- Column -->
					<div class="col-md-6">
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Country' );?></label>
                            <div class="col-md-8">
                                <select name="country" class="selectpicker form-control" data-style="btn-info" data-size="10" data-live-search="true">
                                    <option value="">&nbsp;</option>
                                    <?php table_dropdown('country',null,'iso2','iso2','short_name'); ?>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Address Type' );?></label>
                            <div class="col-md-8">
                                <?=address_type_select();?>
                            </div>
                        </div>
                        <!-- // Group END -->
					
						<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Start Date' );?></label>
                            <div class="col-md-8">
                                <div class="input-group date col-md-8" id="datepicker6">
                                    <input class="form-control" name="startDate" type="text" required/>
                                    <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'End Date' );?></label>
                            <div class="col-md-8">
                                <div class="input-group date col-md-8" id="datepicker7">
                                    <input class="form-control" name="endDate" type="text" />
                                    <span class="input-group-addon"><i class="fa fa-th"></i></span>
                                </div>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><font color="red">*</font> <?=_t( 'Status' );?></label>
                            <div class="col-md-8">
                               <?=address_status_select();?>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Add Date' );?></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" readonly value="<?=date('D, M d, o',strtotime(date("Y-m-d")));?>" />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Added By' );?></label>
                            <div class="col-md-6">
                                <input class="form-control" type="text" readonly value="<?=get_name(_h(get_persondata('personID')));?>" />
                            </div>
                        </div>
                        <!-- // Group END -->
						
					</div>
					<!-- // Column END -->
				</div>
				<!-- // Row END -->
			
				<hr class="separator" />
            
            <div class="widget-body">		
				<!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-4">
                        
        				<!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Phone' );?></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="phone1" />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                    </div>
                    
                    <!-- Column -->
                    <div class="col-md-4">
                        
                        <!-- Group -->
                        <div class="form-group">
                            
                            <label class="col-md-3 control-label"><?=_t( 'Extension' );?></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="ext1" />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                    </div>
                    
                    <!-- Column -->
                    <div class="col-md-4">
                        
                        <!-- Group -->
                        <div class="form-group">
                            
                            <label class="col-md-3 control-label"><?=_t( 'Type' );?></label>
                            <div class="col-md-8">
                                <select name="phoneType1" class="selectpicker col-md-8" data-style="btn-info" data-size="10" data-live-search="true">
                                    <option value="">&nbsp;</option>
                                    <option value="BUS"><?=_t( 'Business' );?></option>
                                    <option value="CEL"><?=_t( 'Cellular' );?></option>
                                    <option value="H"><?=_t( 'Home' );?></option>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                    </div>
                </div>
                <!-- Row End -->
                
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-4">
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Phone' );?></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="phone2" />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                    </div>
                    
                    <!-- Column -->
                    <div class="col-md-4">
                        
                        <!-- Group -->
                        <div class="form-group">
                            
                            <label class="col-md-3 control-label"><?=_t( 'Extension' );?></label>
                            <div class="col-md-8">
                                <input class="form-control" type="text" name="ext2" />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                    </div>
                    
                    <!-- Column -->
                    <div class="col-md-4">
                        
                        <!-- Group -->
                        <div class="form-group">
                            
                            <label class="col-md-3 control-label"><?=_t( 'Type' );?></label>
                            <div class="col-md-8">
                                <select name="phoneType2" class="selectpicker col-md-8" data-style="btn-info" data-size="10" data-live-search="true">
                                    <option value="">&nbsp;</option>
                                    <option value="BUS"><?=_t( 'Business' );?></option>
                                    <option value="CEL"><?=_t( 'Cellular' );?></option>
                                    <option value="H"><?=_t( 'Home' );?></option>
                                </select>
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                    </div>
                </div>
                <!-- Row End -->
            </div>
            
                <hr class="separator" />
                
            <div class="widget-body">       
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-12">
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Primary Email' );?></label>
                            <div class="col-md-6">
                                <input class="form-control" type="email" readonly value="<?=_h($addr[0]['email']);?>" />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                    </div>
                </div>
                <!-- Row End -->
                
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-md-12">
                        
                        <!-- Group -->
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=_t( 'Secondary Email' );?></label>
                            <div class="col-md-6">
                                <input class="form-control" type="email" name="email2" />
                            </div>
                        </div>
                        <!-- // Group END -->
                        
                    </div>
                </div>
                <!-- Row End -->
            </div>
				
				<hr class="separator" />
				
				<!-- Form actions -->
				<div class="form-actions">
					<button type="submit" class="btn btn-icon btn-primary glyphicons circle_ok"><i></i><?=_t( 'Save' );?></button>
                    <button type="button" class="btn btn-icon btn-primary glyphicons circle_minus" onclick="window.location='<?=url('/');?>nae/adsu/<?=_h($addr[0]['personID']);?>/<?=bm();?>'"><i></i><?=_t( 'Cancel' );?></button>
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
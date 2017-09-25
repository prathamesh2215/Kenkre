<?php
	/* include this on page to get loader on page*/
	function getloder()
	{
		?>
			<div id="lodermodal"></div>
			<div id="loderfade"></div>
		<?php
	}
	/* include this on page to get loader on page*/
	
	/* loader on front end*/
	function loaderData()
	{
		global $BaseFolder;
		?>
		<style type="text/css">
			#lodermodal 
			{
				background: rgb(249, 249, 249) url("<?php print $BaseFolder; ?>/images/loader.gif") no-repeat scroll 50% 50% / 100% auto;
				border: 3px solid #ababab;
				border-radius: 20px;
				box-shadow: 1px 1px 10px #ababab;
				display: none;
				height: 100px;
				left: 45%;
				overflow: auto;
				padding: 30px 15px 0;
				position: fixed;
				text-align: center;
				top: 45%;
				width: 100px;
				z-index: 1002;
			}
			#loderfade 
			{
				background-color: #666;
				display: none;
				height: 100%;
				left: 0;
				opacity: 0.7;
				position: fixed;
				top: 0;
				width: 100%;
				z-index: 1001;
			}
			
			.searchdropdown{
				float:left;
				height:30px
			}
			
			.searchtextbox{
				border:1px solid #24282e !important;
				float:left;
				margin:10px 0px 0px 10px;
				height:30px !important;
				background:#fff;
				border-radius:5px;
			}
			
			.divfloatright
			{
			float:right;	
			}
		</style>
		<?php
	}
	/* include this on page to get bootstrap model pop up on page*/
	function modelPopUp()
	{
		?>
	   
	 <div class="modal fade studpkg" role="dialog">
	  <div class="modal-dialog modal-lg" >
		<div class="modal-content">
		<div class="modal-header">                    	
							<div align="right">
								<button type="button" class="cws-button border-radius close-popup" data-dismiss="modal"><i class="fa fa-times"></i></button>
							</div>
						</div>
		   <img src="img/pe-student-package-steps-1-03.jpg">
		</div>
	  </div>
	</div>
	
	
			<div class="modal fade" id="error_model" role="dialog">
				<div class="modal-dialog"><!-- Modal content-->
					<div class="modal-content">
						<div class="modal-header">                    	
							<div align="right">
								<button type="button" class="cws-button border-radius close-popup" data-dismiss="modal"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<div style="clear:both;"></div>
						<div class="modal-body text-center" id="model_body">
						</div>
						<div class="modal-footer">
							&nbsp;<!--<button type="button" class="show-more" data-dismiss="modal">Close</button>-->
						</div>
					</div>      
				</div>
			</div>
		<?php
	}
?>
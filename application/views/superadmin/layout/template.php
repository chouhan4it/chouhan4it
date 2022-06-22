<!doctype html>
<html>
    <head>
        <?= $this->load->view(ADMIN_FOLDER.'/layout/header'); ?>
       
    </head><body class="no-skin">
        <?= $this->load->view(ADMIN_FOLDER.'/layout/topmenu'); ?>
		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
			try{ace.settings.loadState('main-container')}catch(e){}
			</script>
			<?= $this->load->view(ADMIN_FOLDER.'/layout/leftmenu'); ?>
			<?= $this->load->view($content); ?>
			<?= $this->load->view(ADMIN_FOLDER.'/layout/footer'); ?>
			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
					<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div>
        <?= $this->load->view(ADMIN_FOLDER.'/layout/footerbottom'); ?>
    </body>
</html>
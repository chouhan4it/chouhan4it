<!doctype html>
<html>
<head>
<?= $this->load->view(FRANCHISE_FOLDER.'/layout/header'); ?>
</head>
<body>

<div class="clearfix"></div>
<?= $this->load->view(FRANCHISE_FOLDER.'/layout/topmenu'); ?>
<div class="page-content">
  <div class="row">
    <?= $this->load->view(FRANCHISE_FOLDER.'/layout/leftmenu'); ?>
    <div class="col-md-10">
      <div class="content-box-large">
        <?= $this->load->view($content); ?>
      </div>
    </div>
  </div>
</div>
<?= $this->load->view(FRANCHISE_FOLDER.'/layout/footer'); ?>
<?= $this->load->view(FRANCHISE_FOLDER.'/layout/footerbottom'); ?>
</body>
</html>
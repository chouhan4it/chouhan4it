<?php defined('BASEPATH') OR exit('No direct script access allowed');  ?>
<iframe <?php if($_SERVER['HTTP_HOST']!="localhost"){?> onload="disableContextMenu();" onmyload="disableContextMenu();" <?php } ?>
class="autoHeight" scrolling="auto"  frameborder="0" src="<?php echo FRANCHISE_PATH."operation/blank"; ?>" name="MainIframe" id="MainIframe" 
style="min-height:500px; width:100%" ></iframe>	
<?php
/*
Plugin Name: Slide Show
Plugin URI: http://xpertshelp.com
Version: 0.5
Author: Ashok kumar Das
Author URI: http://xpertshelp.com
*/
require_once(dirname(__FILE__) . '/function.php');
$ssWidth = get_option('width');
 function slideshow(){
$blb = unserialize(get_option('uds-billboard')) ?>
<?php if(!empty($blb)):?>
	<?php $uds_sliders = array('uBillboard', 'uSlider', 'cycle'); ?>
	<?php $slider = get_option('uds-billboard-type', 'uSlider'); ?>
	<?php if(UDS_THEME_SWITCHING){
	    if(!empty($_SESSION['uds-billboard-type']) && in_array($_SESSION['uds-billboard-type'], array_keys($uds_sliders))){
	    	$slider = $_SESSION['uds-billboard-type'];
	    }
	    
	    if(!empty($_GET['uds-billboard-type']) && in_array($_GET['uds-billboard-type'], array_keys($uds_sliders))){
	    	$_SESSION['uds-billboard-type'] = $_GET['uds-billboard-type'];
	    	$slider = $_GET['uds-billboard-type'];
	    }
	} ?>
 

    <script type="text/javascript">
        var base_url = "<?php echo bloginfo('url')?>";  
        var template_url = "/wp-content/plugins/slideshow";
        var theme = "<?php echo get_option('uds-theme')?>";
        var easing = "ease<?php echo get_option('uds-easing-inout').get_option('uds-easing')?>";
        <?php $uds_sliders = array('uBillboard', 'uSlider', 'cycle'); ?>
        <?php $slider = get_option('uds-billboard-type', 'uSlider'); ?>
        <?php if(UDS_THEME_SWITCHING){
            if(!empty($_SESSION['uds-billboard-type']) && in_array($_SESSION['uds-billboard-type'], array_keys($uds_sliders))){
                $slider = $_SESSION['uds-billboard-type'];
            }
            
            if(!empty($_GET['uds-billboard-type']) && in_array($_GET['uds-billboard-type'], array_keys($uds_sliders))){
                $_SESSION['uds-billboard-type'] = $_GET['uds-billboard-type'];
                $slider = $_GET['uds-billboard-type'];
            }
        } ?>
        var billboard_type = "<?php echo $slider?>";
        var billboard_delay = "<?php echo get_option('delay', '5000')?>";
        var ss_width = "<?php echo get_option('width')?>";
        var ss_height = "<?php echo get_option('height')?>";
        var family = "<?php echo get_option('family')?>";
        var size = "<?php echo get_option('size')?>";
        var color = "<?php echo get_option('color')?>";
        var weight = "<?php echo get_option('weight')?>";
        var position = "<?php echo get_option('position')?>";
    </script>     
    	<div id="billboard-wrapper">
    		<div id="billboard">
    			<?php foreach($blb as $b): ?>
    				<?php if(!empty($b['link'])): ?>
	    				<a href="<?php echo $b['link']?>"><img src="<?php echo $b['image']?>" width="<?php echo get_option('width')?>" height="<?php echo get_option('height')?>" alt="<?php echo strip_tags(stripslashes($b['text']))?>" /></a>
	    			<?php else: ?>
	    				<img src="<?php echo $b['image']?>" alt="<?php echo strip_tags(stripslashes($b['text']))?>" />
	    			<?php endif; ?>
    			<?php endforeach; ?>
    		</div>
    		<div id="billboard-shadow"></div>
    	</div>
<?php endif;
 }
?>
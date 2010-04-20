<?php
// billboard
// update options
//print_r($_REQUEST);

if(isset($_GET['type']) && $_GET['type'] == 'billboard' && isset($_GET['action']) && $_GET['action'] == 'delete' && is_numeric($_GET['id'])){
	$id = (int)$_GET['id'];
	$old_billboards = unserialize(get_option("uds-billboard"));
	$billboards = array();
	foreach($old_billboards as $key => $billboard){
		if($key != $id){
			$billboards[] = $billboard;
		}
	}  
	update_option("uds-billboard", serialize($billboards));
} elseif(isset($_POST['slide_image_add']) && !empty($_POST['slide_image_add'])){
	$new_billboard = Array();
	$new_billboard['image'] = $_POST['slide_image_add'];
	$new_billboard['link'] = $_POST['slide_link_add'];
	$new_billboard['text'] = $_POST['slide_text_add'];
	$billboards = Array();
	$i = 0;
	while(!empty($_POST['slide_image_'.$i])){
		$billboard = Array();
		$billboard['image'] = $_POST['slide_image_'.$i];
		$billboard['link'] = $_POST['slide_link_'.$i];
		$billboard['text'] = $_POST['slide_text_'.$i];
		$billboards[] = $billboard;
		$i++;
	}
	$billboards[] = $new_billboard; 
	update_option("uds-billboard", serialize($billboards));
} elseif (!empty($_POST['slide_image_0'])) {
	$billboards = Array();
	$i = 0;
	while(!empty($_POST['slide_image_'.$i])){
		$billboard = Array();
		$billboard['image'] = $_POST['slide_image_'.$i];
		$billboard['link'] = $_POST['slide_link_'.$i];
		$billboard['text'] = $_POST['slide_text_'.$i];
		$billboards[] = $billboard;
		$i++;
	}  
	update_option("uds-billboard", serialize($billboards));
}
if(isset($_FILES['slide_image_add']) && !empty($_FILES['slide_image_add'])){
    if($_FILES["slide_image_add"]["name"]!=""){
        $new_billboard = Array();
        $upload = wp_upload_bits($_FILES["slide_image_add"]["name"], null, file_get_contents($_FILES["slide_image_add"]["tmp_name"]));
        $new_billboard['image'] = $upload[url];
        $new_billboard['link'] = $_POST['slide_link_add'];
        $new_billboard['text'] = $_POST['slide_text_add'];
    }
    $billboards = Array();
    $i = 0;
    while(!empty($_POST['slide_image_'.$i])){
        $billboard = Array();
        $billboard['image'] = $_POST['slide_image_'.$i];
        $billboard['link'] = $_POST['slide_link_'.$i];
        $billboard['text'] = $_POST['slide_text_'.$i];
        $billboards[] = $billboard;
        $i++;
    }
    if($_FILES["slide_image_add"]["name"]!=""){
        $billboards[] = $new_billboard;
    } 
    update_option("uds-billboard", serialize($billboards));
}
// retrieve options
$billboards = unserialize(get_option("uds-billboard"));

if(!is_array($billboards)) {
	$billboards = Array();
}

?>
<div class="wrap">
	<h2>Slideshow options</h2>
	<div id="tabs">
		<ul>
			<li><a href="#general-options">General</a></li>
			<li><a href="#billboard-settings">Upload Images</a></li>
		</ul>
		<div id="general-options">
			<div>
				<form method="post" action="options.php">
                <input type="hidden" name="uds-billboard-type" value="uBillboard" >
                <?php if(get_option('width') !=''){ ?>
                <input type="hidden" name="width" value="950" >
                <input type="hidden" name="height" value="250" >
                <input type="hidden" name="delay" value="5000" >
                <input type="hidden" name="family" value="helvetica" >
                <input type="hidden" name="size" value="22" >
                <input type="hidden" name="color" value="white" >
                <input type="hidden" name="weight" value="bold" >
                <input type="hidden" name="position" value="6px 23px" >
                <?php }?>
				<?php wp_nonce_field('update-options') ?>
				<fieldset>
					<legend>General Settings:</legend>
					<table class="theme-options">
						<tr>
							<td class="name">Width: </td>
							<td>
								<input type="text" name="width" value="<?php echo get_option("width")?>" size="40" />
							</td>
						</tr>
						
                        <tr>
                            <td class="name">Height: </td>
                            <td>
                                <input type="text" name="height" value="<?php echo get_option("height")?>" size="40" />
                            </td>
                        </tr>

                        <tr>
                            <td class="name">Font Family: </td>
                            <td>
                                <input type="text" name="family" value="<?php echo get_option("family")?>" size="40" />
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="name">Font Color: </td>
                            <td>
                                <input type="text" name="color" value="<?php echo get_option("color")?>" size="40" />
                            </td>
                        </tr>
                        <tr>
                            <td class="name">Font Size: </td>
                            <td>
                                <select name="size">
                                    <?php for($i=10; $i<=30; $i++){ ?>
                                    <option value="<?php echo $i;?>" <?php if(get_option("size")==$i) echo "SELECTED"; ?> ><?php echo $i;?></option>
                                    <?php }?>
                                </select>
                            </td>
                        </tr>
                        
                        <tr>
                            <td class="name">Font Weight: </td>
                            <td>
                                <input type="text" name="weight" value="<?php echo get_option("weight")?>" size="40" />
                            </td>
                        </tr>
                        <tr>
                            <td class="name">Font Position: </td>
                            <td>
                                <input type="text" name="position" value="<?php echo get_option("position")?>" size="40" />
                            </td>
                        </tr>
                        <tr>
                            <td class="name">Slideshow delay: </td>
                            <td>
                                <input type="text" name="uds-billboard-delay" value="<?php echo get_option("uds-billboard-delay")?>" size="40" />
                            </td>
                        </tr>
					</table>
					<input type="hidden" name="action" value="update" />
					<input type="hidden" name="page_options" value="uds-heading-setup,uds-heading,slideshow-type,delay,width,height,family,color,size,weight,position" />
				</fieldset>
				<input type="submit" name="Submit" value="Update Options" class="button-primary" />
				<div class="clear"></div>
				</form>
			</div>
		</div>
		<div id="billboard-settings">
			<form method="post" action="" enctype="multipart/form-data">
			<?php wp_nonce_field('update-options') ?>
			<table class="billboard" width="100%">
				<tr>
				   	<th width="5%">No.</th>
				   	<th width="45%">Image</th>
				   	<th width="50%">Text</th>
				</tr>
				<?php $count = count($billboards); ?>
				<?php for($i = 0; $i < $count; $i++): ?>
				<tr>
				   	<td><?php echo ($i+1)?></td>
				   	<td>
				   			<?php if(!empty($billboards[$i]['image'])): ?>
								<img alt="Add an Image" src="<?php echo $billboards[$i]['image']?>" id="slide_image_<?php echo $i?>" width="400" height="150" class="billboard-image" />
							<?php else: ?>
								<img alt="Add an Image" src="/wp-content/plugins/slideshow/images/noimg385x180.jpg" id="slide_image_<?php echo $i?>" class="billboard-image" />
							<?php endif; ?>
				   		<input type='hidden' name='slide_image_<?php echo $i?>' value="<?php echo $billboards[$i]['image']?>" id='slide_image_<?php echo $i?>_hidden' />
				   	</td>
				   	<td>
				   		<div>
					   		<label for="slide_text_<?php echo $i?>">Text:</label>
					   		<textarea name="slide_text_<?php echo $i?>" id="slide_text_<?php echo $i?>" cols="60" rows="2"><?php echo stripslashes($billboards[$i]['text'])?></textarea>
					   		<label for="slide_link_<?php echo $i?>">Link:</label>
					   		<input type="text" name="slide_link_<?php echo $i?>" id="slide_link_<?php echo $i?>" value="<?php echo stripslashes($billboards[$i]['link'])?>" size="62" />
					   	</div>
						<a href="options-general.php?page=imag&type=billboard&action=delete&id=<?php echo $i?>" class="delete">Delete</a>
					</td>
				</tr>
				<?php endfor; ?>
				<tr>
				   	<td><?php echo ($count+1)?></td>
				   	<td>  
                         <input type="file" name="slide_image_add" />
				   		
				   	</td>
				   	<td>
				   		<div>
					   		<label for="slide_text_add">Title:</label>
					   		<textarea name="slide_text_add" id="slide_text_add" cols="60" rows="2"></textarea>
					   		<label for="slide_link_add">Link:</label>
					   		<input type="text" name="slide_link_add" id="slide_link_add" value="" size="62" />
					   	</div>
					</td>
				</tr>
			</table>
			<p><input type="submit" name="Submit" class="button-primary" value="Update Options" /></p>
			<div class="clear"></div>
			</form>
		</div>
 	</div>
</div>
<script language='JavaScript' type='text/javascript'>
var set_receiver = function(rec){
	//console.log(rec);
	window.receiver = jQuery(rec).attr('id');
	window.receiver_hidden = jQuery(rec).attr('id')+'_hidden';
}
var send_to_editor = function(img){
	 tb_remove();
	 if(jQuery(jQuery(img)).is('a')){ // work around Link URL supplied
	 	var src = jQuery(jQuery(img)).find('img').attr('src');
	 } else {
	 	var src = jQuery(jQuery(img)).attr('src');
	 }
	 
	 //console.log(window.receiver);
	 jQuery('#'+window.receiver).attr('src', src);
	 jQuery("#"+window.receiver_hidden).val(src);
}
jQuery('.billboard-image,#uds-logo,#uds-favicon').click(function(){
	set_receiver(this);
});
</script>
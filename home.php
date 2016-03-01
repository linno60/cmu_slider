<?php
session_start();
include("db/connect.php"); // this connects to the cmu_slider db so we can query it

$pageTitle = "home";
include("header.php");

if(isset($_SESSION['username'])){
?>
<!-- Main Content Begin -->
<script src="//cdn.ckeditor.com/4.5.7/full/ckeditor.js"></script>

<div class="container">
	<form id="register_form">
		
		<div class="row">
			<div class="twelve columns">
				<label for="slide_title">Title</label>
					<input type="text" name="slide_title" id="slide_title" class="u-full-width"/>
			</div>
		</div>

		<div class="row">
			<div class="twelve columns">
				<label for="slide_content">Content</label>
				<textarea name="slide_content" id="slide_content"></textarea>	
			</div>
		</div>

		<div class="row">
			<div class="six columns">
				<input type="button" value="Preview" class="button" id="preview_button"/>
			</div>

			<div class="six columns">
				<input type="submit" value="Submit" class="button-primary" id="submit_slide_button"/>
			</div>
		</div>
	
	</form>

</div>

<div class="row slide_preview" id="slide_preview">
	<div class="content_container" id="content_container">
	</div>
</div>

<!-- Main Content End -->
<?php
include("footer.php");

} // endif

else {
	echo "Please login to continue";
}

?>
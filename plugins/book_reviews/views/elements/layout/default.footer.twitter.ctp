<div class="footer-element" id="twitter">
	<strong class="title">Let's Talk on Twitter</strong>
</div>
<?php
	echo $javascript->link('/BookReviews/js/jquery.tweet');
?>
<script type='text/javascript'>
	$(document).ready(function(){
		$("#twitter .title").after(
			$('<ul />').tweet({
				username: "fufichi",
				join_text: "auto",
				avatar_size: 'auto',
				count: 3,
				auto_join_text_default: "",
				auto_join_text_ed: "",
				auto_join_text_ing: "",
				auto_join_text_reply: "",
				auto_join_text_url: "",
				outro_text : '',
				loading_text: "loading tweets..."
			})
		);
	});
</script>
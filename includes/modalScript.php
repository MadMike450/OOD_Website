<!-----------------jquery for modal------------------->
<script type="text/javascript">
	$(function() {
		$(document).on('click', '.edit-record', function(e) {
			e.preventDefault();
			
			$(".modal-body").html('');
			$(".modal-body").addClass('loader');
			$("#myModal").modal('show');
			
			$.post('modalDesc.php',
				{id: $(this).attr('data-id')},
				function(html){
					$(".modal-body").removeClass('loader');
					$(".modal-body").html(html);
				
				}
			);		
		});	
	});
</script>

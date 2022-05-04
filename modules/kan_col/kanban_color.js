$(document).ready(function(){
	// aromero: Ocultar el label que auto genera la vista de edición
	$("#card_colors_label").hide();
	// aromero: Capturar el evento onchange del select de campos para recalcular la kanban_color_table.
	$("#kanban_color_field").change(function(){
		SUGAR.ajaxUI.showLoadingPanel();
		$.ajax({
			url: 'index.php',
			type: 'POST',
			data: {
				entryPoint: 'entry_point_kanban_colors',
				id_kanban: $('#id_kanban').val(),
				target_field: $(this).val(),
				target_module: $('#target_module').val()
			},
			success: function (result){
				SUGAR.ajaxUI.hideLoadingPanel();
				$('#kanban_color_table>tbody').html(result);
				jscolor.init();

			}
		});
	});
});
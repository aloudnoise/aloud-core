( function() {

	CKEDITOR.plugins.add("material", {
		icons : "material",
		init: function (editor) {

			editor.addCommand("insertMaterial", {
				exec: function (editor) {

					Yii.app.navigate(Yii.app.createOrganizationUrl('/library/search'), 'modal', {
						transaction : false
					});

				},
				allowedContent: "material"
			});

			editor.ui.addButton("Material", {
				label: "Добавить материал из библиотеки",
				command: "insertMaterial",
				toolbar: "insert,0"
			});
		}
	});

})();

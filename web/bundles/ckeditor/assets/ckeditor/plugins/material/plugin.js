( function() {

	CKEDITOR.plugins.add("material", {
		icons : "material",
		init: function (editor) {

			editor.addCommand("insertMaterial", {
				exec: function (editor) {

					Yii.app.navigate(Yii.app.createOrganizationUrl('/library/index'), 'modal', {
						transaction : false,
						callback : function(action) {

						    $(this.el).find(".assign-item").click(function() {

						        console.log(this);

						        return false;

                            })

						}
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

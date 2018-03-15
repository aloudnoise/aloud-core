( function() {

	CKEDITOR.plugins.add("material", {
		icons : "material",
		init: function (editor) {

			editor.addCommand("insertMaterial", {
				exec: function (editor) {

					Yii.app.navigate(Yii.app.createOrganizationUrl('/library/index'), 'modal', {
						transaction : false,
						callback : function(action) {

							$(this.el).find(".assign-item a").click(function() {

								var material_id = $(this).parents("tr").attr("assign_id");

								var model = _(action.controller.model.get("materials")).findWhere({
									id : parseInt(material_id)
								});

								var material = editor.document.createElement("a");
                                material.setAttribute("data-model-id", material_id);
                                material.setAttribute("contentEditable", false);
                                material.setAttribute("href", Yii.app.createOrganizationUrl('/library/view', {'h' : model.hash}));
                                material.setAttribute("class", "material-link icon-" + model.icon);
                                material.setAttribute("target", "modal");
                                material.setText(model.name);

                                editor.insertElement(material);

                                $(action.controller.el).modal("hide");

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

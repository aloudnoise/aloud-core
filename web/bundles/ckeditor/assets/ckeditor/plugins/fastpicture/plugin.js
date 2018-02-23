( function() {

	CKEDITOR.plugins.add("fastpicture", {
		icons : "fastpicture",
		init: function (editor) {

			var el = $("<div class='uploader-block' style='display:none;'></div>");
			$("body").append(el);

			var placeholder = null;

			var EFileUploader = new EUploader({
				el : el,
				options : {
					multiple : true,
					cropper : false,
					video : false,
					uploadFileTemplate : "#ckeditor_upload_template",
					allowedExtensions : [
						'jpg','png','gif','jpeg','tif','tiff'
					],
					onSuccess : function(file) {

						if (placeholder) {
							$(placeholder.$).remove();
						}

						var img = editor.document.createElement("img");
						img.setAttribute("data-attachment-id", file.model.cid);
						img.setAttribute("src", file.model.get("response").url);
						img.setStyle("max-width", "100%");

						editor.insertElement(img);
						if (editor.widgets && editor.plugins.image2) {
							widget = editor.widgets.initOn(img, 'image');
							widget.focus();
						}
					}
				}
			});

			EFileUploader.newFile = function(file) {
                var m = new EFileModel({
					"name" : file.name,
					"file" : file
				})
				EFileUploader.collection.add([m]);

			};


			EFileUploader.options.addFileFunction = function(view)
			{
				placeholder = editor.document.createElement("div");

				placeholder.$ = view.render().el;

				editor.insertElement(placeholder);
			};

			EFileUploader.render();



			editor.addCommand("insertPicture", {
				exec: function (editor) {
					EFileUploader.buttonClicked();
				},
				allowedContent: "img[src,data-attachment,data-attachment-id,width,height]{float,margin,margin-top,margin-bottom,margin-left,margin-right,width,height}"
			});

			editor.ui.addButton("Fastpicture", {
				label: "Add Picture",
				command: "insertPicture",
				toolbar: "insert,0"
			});
		}
	});

})();

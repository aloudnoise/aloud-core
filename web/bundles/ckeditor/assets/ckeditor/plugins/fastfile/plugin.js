( function() {

	CKEDITOR.plugins.add("fastfile", {
		icons : "fastfile",
		init: function (editor) {

			var el = $("<div class='uploader-file-block' style='display:none;'></div>");
			$("body").append(el);

			var placeholder = null;

			var EFileUploader = new EUploader({
				el : el,
				options : {
					multiple : true,
					cropper : false,
					video : false,
					uploadFileTemplate : "#ckeditor_upload_template",
					onSuccess : function(file) {

						if (placeholder) {
							$(placeholder.$).remove();
						}

						var a = editor.document.createElement("a");
						a.setAttribute("data-attachment-id", file.model.cid);
						a.setAttribute("href", file.model.get("response").url);
						a.setHtml(file.model.get("response").name);

						editor.insertElement(a);
						if (editor.widgets && editor.plugins.link) {
							widget = editor.widgets.initOn(a, 'link');
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

			editor.addCommand("insertFile", {
				exec: function (editor) {
					EFileUploader.buttonClicked();
				},
				allowedContent: "p,a[href,data-attachment,data-attachment-id,width,height]{float,margin,margin-top,margin-bottom,margin-left,margin-right,width,height}"
			});

			editor.ui.addButton("Fastfile", {
				label: "Add file",
				command: "insertFile",
				toolbar: "insert,0"
			});
		}
	});

})();

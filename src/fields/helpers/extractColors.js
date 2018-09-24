export default (file, params) => {
    const defaults = {
    	url: "plugins/colorextractor/process-image",
    	accept: "text",
    	attributes: {},
    	complete: function() {},
    	error: function() {},
    	success: function() {},
    	progress: function() {}
    };

  	const options = Object.assign(defaults, params);
  	const formData = new FormData();

  	formData.append('id', file.id);
  	if (options.attributes) {
	    Object.keys(options.attributes).forEach(key => {
	        formData.append(key, options.attributes[key]);
	    });
	}

  	const xhr = new XMLHttpRequest();

	xhr.addEventListener("load", event => {
	    let json = null;

	    try {
	        json = JSON.parse(event.target.response);
	    } catch (e) {
	        json = {status: "error", message: "The file could not be updated"};
	    }

	    if (json.status && json.status === "error") {
	        options.error(xhr, file, json);
	    } else {
	        options.success(xhr, file, json);
	    }
	});

  	xhr.addEventListener("error", event => {
    	const json = JSON.parse(event.target.response);
    	options.error(xhr, file, json);
    	options.progress(xhr, file, 100);
  	});

  	xhr.open("POST", options.url, true);
  	xhr.send(formData);
};
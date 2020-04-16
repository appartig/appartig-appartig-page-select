jQuery(document).ready(function(){
	initSortable();
	initJson();

	jQuery("input[type=checkbox]").change(() => createJson());
});

function initSortable(){
	jQuery(".aaps-list").sortable({
		onDrop: function ($item, container, _super, event) {
			$item.removeClass(container.group.options.draggedClass).removeAttr("style")
			jQuery("body").removeClass(container.group.options.bodyClass)
			createJson();
		}
	});
}

function initJson(){
	let jsonString = jQuery('#appartig_page_select_value').val() ?? "[]";
	let json = JSON.parse(jsonString);

	// Init if not exist
	if (json.length == 0){
		createJson();
	}

	// Order Items
	let newHTML = jQuery('<ol class="aaps-list"></ol>');
	
	json.forEach(el => {
		let item = jQuery(".aaps-list li.aaps-item[data-id='" + el.id + "']");
		if(el.checked) jQuery("#aaps-item__show--" + el.id).attr("checked", "checked");
		newHTML.append(item);
	});

	jQuery(".aaps-list").remove();
	jQuery(".aaps").append(newHTML);
	initSortable();
}

function createJson(){
	
	let array = [];

	jQuery(".aaps-list li.aaps-item").each(function(i, el){
		let id = jQuery(el).data('id');
		let item = {
			id: id,
			checked: jQuery("#aaps-item__show--" + id).prop('checked') ? true : false
		};
		array.push(item);
	});

	jQuery('#appartig_page_select_value').val(JSON.stringify(array));
}
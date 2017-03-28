// public/js/script.js
$(document).ready(function() {
	/*
		Region typeahead
	 */
	$("#region-typeahead").typeahead({
		source: function(query, process) {
			$.ajax({
					url: BASE+'regions',
					type: 'POST',
					data: 'query=' + query,
					dataType: 'JSON',
					async: true,
					success: function (data) {
						regions = [];
						map = {};
						var obj = jQuery.parseJSON(data);
						$.each(obj, function(i, region) {
							//TODO: This mapping needs to account for regions
							//that have the same last name
							map[region.attributes.longname] = region;
							regions.push(region.attributes.longname);
						})
						process(regions);
					}
				});
		},
		updater: function (item) {
			//set the id in the hidden input field. :]
			selectedRegionID = map[item].attributes.id;
			selectedRegionName = map[item].attributes.longname;
			//add this region's id to the hidden input field
			if($('#region-ids').val() === ""){
				$('#region-ids').val(selectedRegionID);
			} else {
				$('#region-ids').val($('#region-ids').val()+","+selectedRegionID);
			}
			//TODO: make sure the new region isn't already selected
			//create the GUI tag in the region-tags ul
			$('ul#region-tags').append(
				"<li id='region-"+selectedRegionID+"'><div class='region-name'>"+selectedRegionName+"</div><a href='#' class='region-remove'>&#x2716;</a></li>"
			);
			//add the listener to remove what we're adding
			$('#region-'+selectedRegionID+' .region-remove').on("click", function(){
				$region = $(this).parent();
				//remove the id from the hidden list element
				var regionIDsArray = $('#region-ids').val().split(",");
				regionIDsArray.splice(regionIDsArray.indexOf($region.attr('id').substring(7)), 1);
				$('#region-ids').val(regionIDsArray.join(","))
				//remove the gui element
				$region.remove();
			});
			//clear the input
			$("#region-typeahead").val("");
			//the following line fills the textbox with the selected value
			// return item;
		},
		minLength: 2
	})

	$("#stancetag-typeahead").typeahead({
		source: function(query, process) {
			$.ajax({
					url: BASE+'stancetags',
					type: 'POST',
					data: 'query=' + query,
					dataType: 'JSON',
					async: true,
					success: function (data) {
						tags = [];
						map = {};
						var obj = jQuery.parseJSON(data);
						$.each(obj, function(i, tag) {
							map[tag.attributes.tag] = tag;
							tags.push(tag.attributes.tag);
						})
						process(tags);
					}
		 		});
		},
		updater: function (item) {
			//set the id in the hidden input field. :]
			selectedStanceTagID = map[item].attributes.id;
			selectedTag = map[item].attributes.tag;
			//add this tag's id to the hidden input field
			if($('#stancetag-ids').val() === ""){
				$('#stancetag-ids').val(selectedStanceTagID);
			} else {
				$('#stancetag-ids').val($('#stancetag-ids').val()+","+selectedStanceTagID);
			}
			//TODO: make sure the new stancetag isn't already selected
			//create the GUI tag in the stancetag-tags ul
			$('ul#stancetag-tags').append(
				"<li id='stancetag-"+selectedStanceTagID+"'><div class='stancetag-name'>"+selectedTag+"</div><a href='#' class='stancetag-remove'>&#x2716;</a></li>"
			);
			//add the listener to remove what we're adding
			$('#stancetag-'+selectedStanceTagID+' .stancetag-remove').on("click", function(){
				$stancetag = $(this).parent();
				//remove the id from the hidden list element
				var stancetagIDsArray = $('#stancetag-ids').val().split(",");
				stancetagIDsArray.splice(stancetagIDsArray.indexOf($stancetag.attr('id').substring(10)), 1);
				$('#stancetag-ids').val(stancetagIDsArray.join(","))
				//remove the gui element
				$stancetag.remove();
			});
			//clear the input
			$("#stancetag-typeahead").val("");
			//the following line fills the textbox with the selected value
			// return item;
		},
		minLength: 2
	})

	$(".hidden-ids").val(""); //This ensures that ids aren't remembered
});
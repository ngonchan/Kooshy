$(document).ready(function(){

	//Sidebar Accordion Menu:
		$("#main-nav li ul").hide(); // Hide all sub menus
		$("#main-nav li a.current").parent().find("ul").slideToggle("slow"); // Slide down the current menu item's sub menu

		$("#main-nav li a.nav-top-item").click( // When a top menu item is clicked...
			function () {
				$(this).parent().siblings().find("ul").slideUp("normal"); // Slide up all sub menus except the one clicked
				$(this).next().slideToggle("normal"); // Slide down the clicked sub menu
				return false;
			}
		);

		$("#main-nav li a.no-submenu").click( // When a menu item with no sub menu is clicked...
			function () {
				window.location.href=(this.href); // Just open the link instead of a sub menu
				return false;
			}
		);

    // Sidebar Accordion Menu Hover Effect:
		$("#main-nav li .nav-top-item").hover(
			function () {
				$(this).stop().animate({paddingRight: "25px"}, 200);
			},
			function () {
				$(this).stop().animate({paddingRight: "15px"});
			}
		);

    //Minimize Content Box
		$(".content-box-header h3").css({"cursor":"s-resize"}); // Give the h3 in Content Box Header a different cursor
		$(".closed-box .content-box-content").hide(); // Hide the content of the header if it has the class "closed"
		$(".closed-box .content-box-tabs").hide(); // Hide the tabs in the header if it has the class "closed"

		$(".content-box-header h3").click( // When the h3 is clicked...
			function () {
			  $(this).parent().next().toggle(); // Toggle the Content Box
			  $(this).parent().parent().toggleClass("closed-box"); // Toggle the class "closed-box" on the content box
			  $(this).parent().find(".content-box-tabs").toggle(); // Toggle the tabs
			}
		);

    // Content box tabs:
		$('.content-box .content-box-content div.tab-content').hide(); // Hide the content divs
		$('ul.content-box-tabs li a.default-tab').addClass('current'); // Add the class "current" to the default tab
		$('.content-box-content div.default-tab').show(); // Show the div with class "default-tab"

		$('.content-box ul.content-box-tabs li a').click( // When a tab is clicked...
			function() {
				$(this).parent().siblings().find("a").removeClass('current'); // Remove "current" class from all tabs
				$(this).addClass('current'); // Add class "current" to clicked tab
				var currentTab = $(this).attr('href'); // Set variable "currentTab" to the value of href of clicked tab
				$(currentTab).siblings().hide(); // Hide all content divs
				$(currentTab).show(); // Show the content div with the id equal to the id of clicked tab
				return false;
			}
		);

    //Close button:
		$(".close").click(
			function () {
				$(this).parent().fadeTo(400, 0, function () { // Links with the class "close" will close parent
					$(this).slideUp(400);
				});
				return false;
			}
		);

    // Alternating table rows:
		$('tbody tr:even').addClass("alt-row"); // Add class "alt-row" to even table rows

		// Datatables
		if ($('table.data.sort').length > 0) {
			$.fn.dataTableExt.oStdClasses.sPageButton = 'number';
			$.fn.dataTableExt.oStdClasses.sPageButtonActive = 'current';
			$.fn.dataTableExt.oPagination.iFullNumbersShowPages = 5;
			$('table.data.sort').dataTable({
				'aLengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
				'sPaginationType': 'tfoot_numbers',
				'sDom': '<"notification datatable png_bg"<"align-left"l><"align-right"f><i><"clear">>rtp<"clear">'
			});
		}

    // Check all checkboxes when the one in a table head is checked:
		$('.check-all').click(
			function(){
				$(this).parent().parent().parent().parent().find("input[type='checkbox']").attr('checked', $(this).is(':checked'));
			}
		);

    // Initialize Facebox Modal window:
		$('a[rel*=modal]').facebox(); // Applies modal window to any link with attribute rel="modal"

    // Initialize tinymce WYSIWYG:
		var load_tinymce = function() {
			$('textarea.tinymce').tinymce({
				// Location of TinyMCE script
				script_url : '/kms-asset/scripts/tinymce/tiny_mce.js',

				// General options
				//mode : "specific_textareas",
				skin : "cirkuit",
				extended_valid_elements : "iframe[src|width|height|name|align|frameborder|scrolling]",
				theme : "advanced",
				plugins : "pdw,spellchecker,safari,pagebreak,style,layer,table,save,advimage,advlink,advlist,emotions,iespell,inlinepopups,insertdatetime,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

				// Theme options
				theme_advanced_buttons1 : "formatselect,fontsizeselect,forecolor,|,bold,italic,underline,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,|,link,unlink,|,code,fullscreen,|,image,|,pdw_toggle",
				theme_advanced_buttons2 : "spellchecker,paste,pastetext,pasteword,removeformat,|,backcolor,|,strikethrough,justifyfull,sup,|,outdent,indent,|,hr,anchor,charmap,|,media,|,search,replace,|,,|,undo,redo",
				theme_advanced_buttons3 : "tablecontrols,|,visualaid",

				pdw_toggle_on : true,
				pdw_toggle_toolbars : "2,3",

				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,

				height : "450",
				width: "930"
			});
		}

		// Show / Hide Content Meta Fields and Load TinyMCE
		var meta_content = function() {
			var mime_type = $('#ff_mime_type').val();
			if (mime_type == 'text/html') {
				$('#ff_meta_keywords').parent().show();
				$('#ff_meta_description').parent().show();
				try {
					$('textarea.tinymce').tinymce().show();
				} catch (e) {
					load_tinymce();
				}
			} else {
				$('#ff_meta_keywords').parent().hide();
				$('#ff_meta_description').parent().hide();
				try {
					$('textarea.tinymce').tinymce().hide();
				} catch (e) {}
			}
		}
		if ($('#ff_mime_type').length > 0) {
			$('#ff_mime_type').change(function(){
				meta_content();
			});
			meta_content();
		} else {
			load_tinymce();
		}

		// Setup for Adding Columns in List Creation Section
		$('#add_list_column').each(function(i, el){
			$(el).click(function(e){
				var p = $(e.currentTarget).parent().parent();
				var el = jQuery('\
				<fieldset class="column-left">\
					<p>\
						<label>Name</label>\
						<input class="text-input medium-input" type="text" name="column_name[]" value="" />\
					</p>\
				</fieldset>\
				<fieldset class="column-right">\
					<p>\
						<label>Type</label>\
						<select name="column_type[]" class="medium-input">\
							<optgroup label="Number">\
								<option value="integer">Integer < 11 digits</option>\
								<option value="decimal">Decimal 13 digits . 2 digits</option>\
							</optgroup>\
							<optgroup label="Text">\
								<option value="text">for less than 255 chars</option>\
								<option value="long">long without WYSIWYG</option>\
								<option value="long-wysiwyg">long with WYSIWYG</option>\
							</optgroup>\
						</select>\
					</p>\
				</fieldset>\
				<div class="clear"></div>');
				p.before(el);
				return false;
			});
		});

});



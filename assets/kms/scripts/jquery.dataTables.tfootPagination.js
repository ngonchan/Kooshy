$.fn.dataTableExt.afnSortData['std'] = function  ( oSettings, iColumn, iVisColumn ) {
	var aData = [];
	$('td:eq('+iColumn+')', oSettings.oApi._fnGetTrNodes(oSettings)).each( function () {
		aData.push( $(this).attr('sortdata') ? $(this).attr('sortdata') + '::::' + $(this).html() : $(this).html() );
	});
	return aData;
};

$.fn.dataTableExt.oSort['string-asc'] = function( a, b ) {
	a = a.replace(/^(.+?)\:\:\:\:.+$/, '$1');
	b = b.replace(/^(.+?)\:\:\:\:.+$/, '$1');
	var x = a.toLowerCase();
	var y = b.toLowerCase();
	return ((x < y) ? -1 : ((x > y) ? 1 : 0));
}

$.fn.dataTableExt.oSort['string-desc'] = function( a, b ) {
	a = a.replace(/^(.+?)\:\:\:\:.+$/, '$1');
	b = b.replace(/^(.+?)\:\:\:\:.+$/, '$1');
	var x = a.toLowerCase();
	var y = b.toLowerCase();
	return ((x < y) ? 1 : ((x > y) ? -1 : 0));
}

$.fn.dataTableExt.oPagination.tfoot_numbers = {
	/*
		 * Function: oPagination.tfoot_numbers.fnInit
		 * Purpose:  Initalise dom elements required for pagination with a list of the pages
		 * Returns:  -
		 * Inputs:   object:oSettings - dataTables settings object
		 *           node:nPaging - the DIV which contains this pagination control
		 *           function:fnCallbackDraw - draw function which must be called on update
		 */
	"fnInit": function ( oSettings, nPaging, fnCallbackDraw ) {
		if (oSettings.nTFoot) {
			nPaging = $(oSettings.nTFoot).find('div.pagination');
		}	else {
			nPaging = $(nPaging);
		}
		if (!oSettings.tfoot_paginate) oSettings.tfoot_paginate = []
		oSettings.tfoot_paginate.push(nPaging);

		var nFirst = $('<a href="#"></a>');
		var nPrevious = $('<a href="#"></a>');
		var nList = $('<span></span>');
		var nNext = $('<a href="#"></a>');
		var nLast = $('<a href="#"></a>');

		nFirst.html(oSettings.oLanguage.oPaginate.sFirst);
		nPrevious.html(oSettings.oLanguage.oPaginate.sPrevious);
		nNext.html(oSettings.oLanguage.oPaginate.sNext);
		nLast.html(oSettings.oLanguage.oPaginate.sLast);

		var oClasses = oSettings.oClasses;
		nFirst.addClass(oClasses.sPageFirst)
		nPrevious.addClass(oClasses.sPagePrevious)
		nList.addClass((oClasses.sList?oClasses.sList:'list'))
		nNext.addClass(oClasses.sPageNext)
		nLast.addClass(oClasses.sPageLast)

		nPaging.append( nFirst );
		nPaging.append( nPrevious );
		nPaging.append( nList );
		nPaging.append( nNext );
		nPaging.append( nLast );

		$(nFirst).bind( 'click.DT', function () {
			if ( oSettings.oApi._fnPageChange( oSettings, "first" ) ) {
				fnCallbackDraw( oSettings );
				return false;
			}
		});

		$(nPrevious).bind( 'click.DT', function() {
			if ( oSettings.oApi._fnPageChange( oSettings, "previous" ) ) {
				fnCallbackDraw( oSettings );
				return false;
			}
		});

		$(nNext).bind( 'click.DT', function() {
			if ( oSettings.oApi._fnPageChange( oSettings, "next" ) ) {
				fnCallbackDraw( oSettings );
				return false;
			}
		});

		$(nLast).bind( 'click.DT', function() {
			if ( oSettings.oApi._fnPageChange( oSettings, "last" ) ) {
				fnCallbackDraw( oSettings );
				return false;
			}
		});

		/* Take the brutal approach to cancelling text selection */
		$('span', nPaging)
			.bind( 'mousedown.DT', function () { return false; } )
			.bind( 'selectstart.DT', function () { return false; } );

		/* ID the first elements only */
		if ( oSettings.sTableId !== '' && typeof oSettings.aanFeatures.p == "undefined" ) {
			nPaging.setAttribute( 'id', oSettings.sTableId+'_paginate' );
			nFirst.setAttribute( 'id', oSettings.sTableId+'_first' );
			nPrevious.setAttribute( 'id', oSettings.sTableId+'_previous' );
			nNext.setAttribute( 'id', oSettings.sTableId+'_next' );
			nLast.setAttribute( 'id', oSettings.sTableId+'_last' );
		}
	},

	/*
		 * Function: oPagination.tfoot_numbers.fnUpdate
		 * Purpose:  Update the list of page buttons shows
		 * Returns:  -
		 * Inputs:   object:oSettings - dataTables settings object
		 *           function:fnCallbackDraw - draw function to call on page change
		 */
	"fnUpdate": function ( oSettings, fnCallbackDraw ) {
		if ( !oSettings.tfoot_paginate ) return;

		var iPageCount = $.fn.dataTableExt.oPagination.iFullNumbersShowPages;
		var iPageCountHalf = Math.floor(iPageCount / 2);
		var iPages = Math.ceil((oSettings.fnRecordsDisplay()) / oSettings._iDisplayLength);
		var iCurrentPage = Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength) + 1;
		var sList = "";
		var iStartButton, iEndButton, i, iLen;
		var oClasses = oSettings.oClasses;

		/* Pages calculation */
		if (iPages < iPageCount) {
			iStartButton = 1;
			iEndButton = iPages;
		} else {
			if (iCurrentPage <= iPageCountHalf) {
				iStartButton = 1;
				iEndButton = iPageCount;
			} else {
				if (iCurrentPage >= (iPages - iPageCountHalf)) {
					iStartButton = iPages - iPageCount + 1;
					iEndButton = iPages;
				} else {
					iStartButton = iCurrentPage - Math.ceil(iPageCount / 2) + 1;
					iEndButton = iStartButton + iPageCount - 1;
				}
			}
		}

		/* Build the dynamic list */
		if ( iPages > 1 ) {
			for ( i=iStartButton ; i<=iEndButton ; i++ ) {
				if ( iCurrentPage != i ) {
					sList += '<a href="#" class="'+oClasses.sPageButton+'">'+i+'</a>';
				} else {
					sList += '<a href="#" class="'+oClasses.sPageButton+' '+oClasses.sPageButtonActive+'">'+i+'</a>';
				}
			}
		}

		/* Loop over each instance of the pager */
		var an = $(oSettings.tfoot_paginate);
		var anButtons, anStatic, nPaginateList;
		var fnClick = function() {
			/* Use the information in the element to jump to the required page */
			var iTarget = (this.innerHTML * 1) - 1;
			oSettings._iDisplayStart = iTarget * oSettings._iDisplayLength;
			fnCallbackDraw( oSettings );
			return false;
		};
		var fnFalse = function () { return false; };

		an.each(function(i, el) {
			var list = el.find('span.' + (oClasses.sList?oClasses.sList:'list'));

			if ( el.find('span.list').length <= 0 ) return;

			// Build up the dynamic list forst - html and listeners
			list.html( sList );
			$('a', list)
				.bind( 'click.DT', fnClick )
				.bind( 'mousedown.DT', fnFalse )
				.bind( 'selectstart.DT', fnFalse );

			// Update the 'premanent botton's classes
			anButtons = $(el).find('a');
			anStatic = [
				$(anButtons[0]), $(anButtons[1]),
				$(anButtons[anButtons.length-2]), $(anButtons[anButtons.length-1])
			];

			if ( iCurrentPage == 1 ) {
				anStatic[0].addClass('hide');
				anStatic[1].addClass('hide');
			} else {
				anStatic[0].removeClass('hide');
				anStatic[1].removeClass('hide');
			}

			if ( iPages === 0 || iCurrentPage == iPages || oSettings._iDisplayLength == -1 ) {
				anStatic[2].addClass('hide');
				anStatic[3].addClass('hide');
			} else {
				anStatic[2].removeClass('hide');
				anStatic[3].removeClass('hide');
			}
		});

		/*
		$('html, body').animate({
				scrollTop: $("#elementID").offset().top
		}, 2000);
		*/

		if (oSettings.tfootScroll) {
			$('html, body').animate({
				scrollTop: $(oSettings.nTableWrapper).offset().top
			}, 250);
		}
		oSettings.tfootScroll = true;

	}
}
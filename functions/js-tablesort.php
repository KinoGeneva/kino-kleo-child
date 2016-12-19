<?php 


/*
 * Add JS code for Table Sorting to Admin Pages
 *
*/


function kino_js_tablesort($tableid){
		
		// Make table sortable
		// Documentation: https://mottie.github.io/tablesorter/docs/#Demo
		
		?>
		
		<script>
			
			jQuery(document).ready(function($){
			
				$.tablesorter.themes.bootstrap = {
				    // these classes are added to the table. To see other table classes available,
				    // look here: http://getbootstrap.com/css/#tables
				    table        : 'table table-bordered table-striped',
				    caption      : 'caption',
				    // header class names
				    header       : 'bootstrap-header', // give the header a gradient background (theme.bootstrap_2.css)
				    sortNone     : '',
				    sortAsc      : '',
				    sortDesc     : '',
				    active       : '', // applied when column is sorted
				    hover        : '', // custom css required - a defined bootstrap style may not override other classes
				    // icon class names
				    icons        : '', // add "icon-white" to make them white; this icon class is added to the <i> in the header
				     iconSortNone : 'icon-sort', // class name added to icon when column is not sorted
				     iconSortAsc  : 'icon-sort-up', // class name added to icon when column has ascending sort
				     iconSortDesc : 'icon-sort-down', // class name added to icon when column has descending sort
				    filterRow    : '', // filter row class; use widgetOptions.filter_cssFilter for the input/select element
				    footerRow    : '',
				    footerCells  : '',
				    even         : '', // even row zebra striping
				    odd          : ''  // odd row zebra striping
				  };
				
				$("#<?php echo $tableid; ?>").tablesorter({
						
						// this will apply the bootstrap theme if "uitheme" widget is included
						// the widgetOptions.uitheme is no longer required to be set
						theme : "bootstrap",
						
						    widthFixed: true,
						
						    headerTemplate : '{content} {icon}', // new in v2.7. Needed to add the bootstrap icon!
						
						    // widget code contained in the jquery.tablesorter.widgets.js file
						    // use the zebra stripe widget if you plan on hiding any rows (filter widget)
						    widgets : [ "uitheme", "filter", "zebra" ],
						
						    widgetOptions : {
						      // using the default zebra striping class name, so it actually isn't included in the theme variable above
						      // this is ONLY needed for bootstrap theming if you are using the filter widget, because rows are hidden
						      zebra : ["even", "odd"],
						
						      // reset filters button
						      filter_reset : ".reset",
						
						      // extra css class name (string or array) added to the filter element (input or select)
						      filter_cssFilter: "form-control",
						
						      // set the uitheme widget to use the bootstrap theme class names
						      // this is no longer required, if theme is set
						      // ,uitheme : "bootstrap"
						
						    }
				
				});
				
			});
		
		</script>
		
		
		<?php

}

#
# Returns data related to the BGO application framework with a set of reusable components
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT { 
	?domain # a bgo:Domain ;
	 	bgo:title ?title ;
        bgo:description ?description ;
        bgo:abstract ?abstract ;
        bgo:hasSocialSharing  ?socialSharing ;
        bgo:hasOverview  ?overview ;
        bgo:hasTableview  ?tableView ;
        bgo:hasCredits  ?credits ;
        bgo:hasTerms  ?terms ;
        bgo:hasCopyrigth ?copyright ;       
        ?hasMenu ?menu 
    .
    
    
    # Menu construction
    ?domain ?hasMenu ?menu .
    ?menu bgo:withCustomMenuItems ?menuItemsList .
	?menuItemsListRest rdf:first ?menuItem ; rdf:rest ?menuItemsListTail .
    ?menuItem bgo:icon ?menuItemIcon ;
    	bgo:label ?menuItemLabel ;
    	bgo:title ?menuItemTitle ;
    	bgo:link ?menuItemLink .
    	
    	
    # Minimal view data for menu building
    ?view # i.e. overview, table, credits, term
		bgo:icon ?viewIcon ; 
		bgo:label ?viewLabel ; 
		bgo:title ?viewTitle .
    
    ?overview 
    	bgo:hasPartitions ?partitions . 
    ?partitions 
    	bgo:icon ?partitionsIcon ;
    	bgo:label ?partitionsLabel ;
    	bgo:title ?partitionsTitle ; 
    	bgo:hasPartitionList ?partitionList 
    . 
    # manage partition list	
	?partitionListRest 
		rdf:first ?partition ; 
		rdf:rest ?partitionListTail 
	.
    ?partition 
    	bgo:icon ?partitionIcon ;
    	bgo:label ?partitionLabel ;
    	bgo:title ?partitionTitle 
    .
  
} 
WHERE {
	<?php if ($domainId) {?>
		?domain bgo:domainId "<?php echo $domainId;?>" .
	<?php } else { ?>
		FILTER NOT EXISTS { ?domain bgo:domainId [] } .
	<?php }?>
	
	?domain 
		bgo:hasOverview ?overview ;
		bgo:hasTableView ?tableView ;
        bgo:hasCredits  ?credits ;
        bgo:hasTerms  ?terms 
    .
	
	OPTIONAL { ?domain bgo:title ?title }
	OPTIONAL { ?domain bgo:description ?description }
	OPTIONAL { ?domain bgo:abstract ?abstract }
	OPTIONAL { ?domain bgo:hasSocialSharing  ?socialSharing }
	OPTIONAL { ?domain bgo:hasCopyrigth ?copyright }

	
	# Menu construction
	# See https://stackoverflow.com/questions/44221975/how-to-write-a-sparql-construct-query-that-returns-an-rdf-list
	OPTIONAL { 
		
    	VALUES ?hasMenu {
    		bgo:hasNavigationMenu
    		bgo:hasOptionMenu
    		bgo:hasFooterMenu
    	}
    	
		?domain ?hasMenu ?menu .
		?menu bgo:withCustomMenuItems ?menuItemsList .
		?menuItemsList rdf:rest* ?menuItemsListRest .
		?menuItemsListRest rdf:first ?menuItem ; rdf:rest ?menuItemsListTail .
		
     	OPTIONAL { ?menuItem bgo:icon ?menuItemIcon }
     	OPTIONAL { ?menuItem bgo:label ?menuItemLabel }
     	OPTIONAL { ?menuItem bgo:title ?menuItemTitle }
     	OPTIONAL { ?menuItem bgo:link ?menuItemLink }
     }
     
    OPTIONAL { ?overview bgo:icon ?overviewIcon }
    OPTIONAL { ?overview bgo:label ?overviewLabel }
    OPTIONAL { ?overview bgo:title ?overviewTitle } 
   	OPTIONAL { ?overview bgo:hasPartitions ?partitions } 

    
    
    # Minimal partitions  data for menu building    
    OPTIONAL { 
    	?overview bgo:hasPartitions ?partitions .
        OPTIONAL { ?partitions bgo:icon ?partitionsIcon }
        OPTIONAL { ?partitions bgo:label ?partitionsLabel }
        OPTIONAL { ?partitions bgo:title ?partitionsTitle } 
       	OPTIONAL { ?partitions bgo:hasPartitionList ?partitionList } 
    }  
    
    # Minimal partition  data for menu building    
    OPTIONAL { 
    	?overview bgo:hasPartitions/bgo:hasPartitionList ?partitionList .
    	
    	?partitionList rdf:rest* ?partitionListRest .
		?partitionListRest rdf:first ?partition ; rdf:rest ?partitionListTail .
		
     	OPTIONAL { ?partition bgo:icon ?partitionIcon }
     	OPTIONAL { ?partition bgo:label ?partitionLabel }
     	OPTIONAL { ?partition bgo:title ?partitionTitle }	
    
    }  
    
    
    # Minimal data for other view for menu building
     OPTIONAL {
         VALUES ?hasViews {
         	bgo:hasOverview
         	bgo:hasTableview
         	bgo:hasCredits
         	bgo:hasTerms
         }     
     	
     	?domain ?hasView ?view .
     	OPTIONAL { ?view bgo:icon ?viewIcon }
     	OPTIONAL { ?view bgo:label ?viewLabel }
     	OPTIONAL { ?view bgo:title ?viewTitle }
     }

     
}
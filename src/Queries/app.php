#
# Returns BGO portion required to render an empty page frameworks (with al menues)
#
# expect result similar to:
#
##	 <urn:bgo:test:domain> bgo:abstract "These are **demo data** without a real meaning."^^bgo:MDString ;
##	     bgo:description "Here a short description of the data domain" ;
##	     bgo:hasCopyrigth "(c) [LinkedData.Center](http://linkeddata.center)"^^bgo:MDString ;
##	     bgo:hasCredits <urn:bgo:test:credits_page> ;
##	     bgo:hasFooterMenu [ bgo:withCustomMenuItem [ bgo:icon "mda-github-box" ;
##	                     bgo:link <https://github.com/linkeddatacenter/LODMAP2D> ;
##	                     bgo:title "GitHub" ],
##	                 [ bgo:icon "mdi-table" ;
##	                     bgo:link "/table"^^bgo:route ] ] ;
##	     bgo:hasNavigationMenu [ bgo:withCustomMenuItem [ bgo:icon "mdi-atom-variant" ;
##	                     bgo:link <https://github.com/linkeddatacenter/LODMAP2D> ;
##	                     bgo:title "reference implementation..." ] ] ;
##	     bgo:hasOptionMenu [ bgo:withCustomMenuItem [ bgo:icon "mdi-information-outline" ;
##	                     bgo:link <http://bit.ly/lodmap2d_p> ;
##	                     bgo:title "about LODMAP2D..." ],
##	                 [ bgo:icon "mdi-lock" ;
##	                     bgo:link <https://github.com/solid/webid-oidc-spec> ;
##	                     bgo:title "about WebID-OIDC..." ] ] ;
##	     bgo:hasOverview <urn:bgo:test:overview> ;
##	     bgo:hasSocialSharing true ;
##	     bgo:hasTableView <urn:bgo:test:table_view> ;
##	     bgo:hasTerms <urn:bgo:test:terms_page> ;
##	     bgo:title "Live test of Bubble Graph Ontology" .
##	 
##	 <urn:bgo:test:credits_page> bgo:icon "fas fa-users" ;
##	     bgo:title "Credits"@en .
##	 
##	 <urn:bgo:test:overview> bgo:hasPartitions [ bgo:hasPartition <urn:bgo:test:p1>,
##	                 <urn:bgo:test:p2> ;
##	             bgo:icon "fas fa-atom" ;
##	             bgo:label "Partitions" ] ;
##	     bgo:icon "fas fa-atom" ;
##	     bgo:label "Stato" ;
##	     bgo:title "Overview" .
##	 
##	 <urn:bgo:test:p1> bgo:label "partition1" ;
##	     bgo:partitionId "p1" ;
##	     bgo:title "Partition 1 title" .
##	 
##	 <urn:bgo:test:p2> bgo:label "partition2" ;
##	     bgo:partitionId "2" ;
##	     bgo:title "P2 title" .
##	 
##	 <urn:bgo:test:table_view> bgo:title "Table view" .
##	 
##	 <urn:bgo:test:terms_page> bgo:icon "fas fa-gavel" ;
##	     bgo:title "Terms & conditions" .
#
PREFIX bgo: <http://linkeddata.center/lodmap-bgo/v1#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
CONSTRUCT { 
	?domain # a bgo:Domain ;
		bgo:title ?domainTitle ;
        bgo:description ?description ;
        bgo:abstract ?abstract ;
        bgo:hasSocialSharing  ?socialSharing ;
        bgo:hasOverview  ?overview ;
        bgo:hasTableView  ?tableView ;
        bgo:hasCredits  ?credits ;
        bgo:hasTerms  ?terms ;
        bgo:hasCopyrigth ?copyright ;       
        ?hasMenu ?menu 
    .
     
    # Menu construction
    ?domain ?hasMenu ?menu .
    ?menu bgo:withCustomMenuItem ?menuItem .
    
    ?menuItem 
    	bgo:link ?menuItemLink 
    .
    
    ?overview 
    	bgo:hasPartitions ?partitions . 
    	
    ?partitions 
    	bgo:hasPartition ?partition
    . 
    
    ?partition 
    	bgo:partitionId ?partitionId
    . 
    
    ?bgoThings 
        bgo:icon ?icon ;
    	bgo:label ?label ;
    	bgo:title ?title
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

	OPTIONAL { ?domain bgo:title ?domainTitle }
	OPTIONAL { ?domain bgo:description ?description }
	OPTIONAL { ?domain bgo:abstract ?abstract }
	OPTIONAL { ?domain bgo:hasSocialSharing  ?socialSharing }
	OPTIONAL { ?domain bgo:hasCopyrigth ?copyright }
 
 
    # Minimal partitions data required for menu building    
    OPTIONAL { 
    	?overview bgo:hasPartitions ?partitions .
  		?partitions bgo:hasPartition ?partition .
   		?partition bgo:partitionId ?partitionId
    }  
   
    # icons, labels and titles required for menu building   
	{ ?domain bgo:hasOverview|bgo:hasTableView|bgo:hasCredits|bgo:hasTerms ?bgoThings }
	UNION
	{ ?overview bgo:hasPartitions ?bgoThings }
	UNION
	{ ?overview bgo:hasPartitions/bgo:hasPartition ?bgoThings }
	UNION
	{ 
		?domain bgo:hasNavigationMenu|bgo:hasOptionMenu|bgo:hasFooterMenu ?menu .
		?menu  bgo:withCustomMenuItem ?bgoThings
	}
    OPTIONAL { ?bgoThings bgo:icon ?icon }
 	OPTIONAL { ?bgoThings bgo:label ?label }
 	OPTIONAL { ?bgoThings bgo:title ?title }
	
	
	# Menu construction
	OPTIONAL { 		
    	VALUES ?hasMenu {
    		bgo:hasNavigationMenu
    		bgo:hasOptionMenu
    		bgo:hasFooterMenu
    	}
    	
		?domain ?hasMenu ?menu .
		?menu bgo:withCustomMenuItem ?menuItem .
		
     	OPTIONAL { ?menuItem bgo:link ?menuItemLink }
     }
     

}
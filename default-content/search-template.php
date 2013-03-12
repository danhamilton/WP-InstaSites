<div class="btn-group">
    <a class="btn" onclick="doSearch(1)">List</a>
    <a class="btn" onclick="doSearch(2)">Gallery</a>
    <a class="btn" onclick="doSearch(3)">Map</a>
</div>

<div id="map-view" style="display:none">
    <div id="content-nosidebar">
        <div class="map-view-page">   
        <div class="clear2"></div>
        <div id="map-view-map" class="MapStyle" style="width:650px"></div>
        <div class="clear clear-map-view"></div>
        </div>
    </div>
</div>

<div class="left-content">    
    <div class="clear"></div>
    
    <div class="sidebar-ip" id="reg-view" style="display:none">
        <div id="bapi-left-content">
        <div class="list-view-page">
            <div class="portal-results">    
            <h1><span id="numresults"></span> Results</h1>
            <div class="clear2"></div>
            <div id="search-results"></div>        
            </div>
            <div class="clear"></div>
        </div>
        </div> 
    </div>	
</div>

<div class="right-sidebar" id="right-sidebar"> 
    <div class="clear"></div>          
    <div class="reviseSearchBlock">
    <h1>Revise Your Search</h1>        
    <div id="qsearch" class="property-search-revise-block">        
    </div>
    </div>
    <div class="clear2"></div>    
</div>
// See this for important info :

http://docs.geoserver.org/stable/en/user/_sources/rest/examples/curl.txt


// Get all workspaces
curl -v -u admin:geoserver -GET http://localhost:8080/geoserver/rest/workspaces.xml

// Create a new WORKSPACE
curl -u admin:geoserver -v -XPOST -H 'Content-type:text/xml'
-d '<workspace><name>WORKSPACE</name></workspace>'
http://localhost:8080/geoserver/rest/workspaces

// DELETE a WORKSPACE & all layers in it.
// NOTE: trailing parameter must be in double quotes!
curl -v -u admin:geoserver -XDELETE http://localhost:8080/geoserver/rest/workspaces/WORKSPACE?recurse="true"

// Get a workspace's settings
curl -v -u admin:geoserver http://localhost:8080/geoserver/rest/workspaces/tiger/settings.xml

// Modify a workspace's name
curl -v -u admin:geoserver -XPUT -H "Content-type: text/xml" -d 
'<settings><name>tiger1</name></settings>' 
http://localhost:8080/geoserver/rest/workspaces/tiger/settings.xml

// Get settings for local WMS settings
curl -v -u admin:geoserver
http://localhost:8080/geoserver/rest/services/wms/workspaces/WORKSPACE/settings.xml

// Enable a local WMS service
curl -v -u admin:geoserver -v -XPUT -H "Content-type: text/xml"
-d '<wms><enabled>true</enabled></wms>'
http://localhost:8080/geoserver/rest/services/wms/workspaces/WORKSPACE/settings.xml

// Set the "Limited SRS list" for a local WMS service
curl -v -u admin:geoserver -v -XPUT -H "Content-type: text/xml"
-d '<wms><srs><string>4326</string><string>3857</string></srs>
<bboxForEachCRS>true</bboxForEachCRS></wms>'
http://localhost:8080/geoserver/rest/services/wms/workspaces/WORKSPACE/settings.xml

// Change the title of a local WMS service
curl -v -u admin:geoserver -v -XPUT -H "Content-type: text/xml"
 -d '<wms><name>New EarthWS WMS Service</name></wms>'
 http://localhost:8080/geoserver/rest/services/wms/workspaces/WORKSPACE/settings.xml


// Get all Raster Stores (CoverageStores) for a workspace WORKSPACE
curl -v -u admin:geoserver -GET
http://localhost:8080/geoserver/rest/workspaces/WORKSPACE/coveragestores.xml

// Using response from above, get coverage/raster layer for a particular COVERAGESTORE
curl -u admin:geoserver
http://localhost:8080/geoserver/rest/workspaces/WORKSPACE/coveragestores/COVERAGESTORE.xml

// Get all coverages for a coveragestore daily1
curl -u admin:geoserver
http://localhost:8080/geoserver/rest/workspaces/getsat/coveragestores/rain_weekly/coverages.json
// this will contain a coverages.coverage object containg name and href
// this href returns..
curl -u admin:geoserver
http://localhost:8080/geoserver/rest/workspaces/getsat/coveragestores/daily1/coverages/20170211a.xml

// Bulk update a bunch of coverage properties
// Bulk set a bunch of layer properties.
curl -v -u admin:geoserver -v -XPUT -H "Content-type: text/xml"
-d "<coverage><abstract>THis yet another abstract test</abstract>
<title>A new title</title>
<requestSRS><string>EPSG:4326</string>
<string>EPSG:3857</string>
<string>EPSG:900913</string>
</requestSRS>
<responseSRS>
<string>EPSG:4326</string>
<string>EPSG:3857</string>
<string>EPSG:900913</string>
</responseSRS>
</coverage>"
http://localhost:8080/geoserver/rest/workspaces/WORKSPACE/coveragestores/COVERAGENAME/coverages/COVERAGENAME.xml

// Create a GeoTIFF raster coverageStore in a $WORKSPACE, for a file that already exists on the server.
// And change its name tp $COVERAGE_NAME
curl -v -u admin:geoserver -XPUT -H "Content-type: text/plain" -d
"file:///Users/bruce/Downloads/GIS_data/rain/daily/20170214.tif"
'http://localhost:8080/geoserver/rest/workspaces/getsat/coveragestores/rain_daily_20170214/external.geotiff?configure=first&coverageName=rain_daily_20170214'

//Assign an SLD to a raster image
// Image title : 20170211.tif
// NOTE: Don't set the InputTransparentColor if a SLD is attached.
curl -v -u admin:geoserver -XPUT -H "Content-type: text/xml"
-d "<layer><defaultStyle><name>raster_rain</name></defaultStyle></layer>"
http://localhost:8080/geoserver/rest/layers/WORKSPACE:LAYERNAME


// Set a layers title
curl -v -u admin:geoserver -v -XPUT -H "Content-type: text/xml"
-d "<layer><title>New_title</title></coverage>"
http://localhost:8080/geoserver/rest/workspaces/WORKSPACE/coveragestores/COVERAGENAME/coverages/COVERAGENAME.xml


// Update the Default WMS path for a layer
curl -v -u admin:geoserver -XPUT -H "Content-type:
text/xml" -d "<layer><path>wms/test/path</path></layer>"
http://localhost:8080/geoserver/rest/layers/WORKSPACE:LAYERNAME.xml

// Create a coverageStore in the first call, then attach an GeoTIFF to the newly created coverageStore

//Step1:
curl -u admin:geoserver -v -XPOST -H 'Content-Type: application/xml'
-d '<coverageStore><name>COVERAGESTORENAME</name><workspace>WORKSPACE</workspace><enabled>true</enabled>
<type>GeoTIFF</type></coverageStore>'
http://localhost:8080/geoserver/rest/workspaces/WORKSPACE/coveragestores

//Step 2:
curl -u admin:geoserver -v -XPUT -H 'Content-type: text/plain'
-d 'file:///Users/bruce/Downloads/GIS_data/rain/week/20170214.tif'
http://localhost:8080/geoserver/rest/workspaces/WORKSPACE/coveragestores/COVERAGESTORENAME/external.geotiff?configure=first&coverageName=NAME_OF_LAYER_TO_DISPLAY




curl -u admin:geoserver -XGET -H 'Content-type: text/xml' http://localhost:8080/geoserver/rest/settings.xml
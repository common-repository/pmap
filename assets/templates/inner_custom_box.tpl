<div id="pmieldswrap" class="pmieldswrap">

    
    <div class="pmields">

        <p>
            <label>
                lat:
                <input type="text" class="pmLatLng" name="<%= latitude_name %>" value='<%= latitude_value %>' placeholder="latitude" id="<%= latitude_id %>" >
            </label>
            <label>
                lng:
                <input type="text" class="pmLatLng" name="<%= longitude_name %>" value='<%= longitude_value %>' placeholder="longitude" id="<%= longitude_id %>" >
            </label> 
        </p>

        <p>
            <input type="text" placeholder="insert text address" id="pm_address">
            <input type="button" value="select" id="pm_setaddress">
        </p>

        <p id="pmmsg"></p>

        <div id="pmmap" class="pmmap">
            loading map...
        </div>

    </div>

</div>
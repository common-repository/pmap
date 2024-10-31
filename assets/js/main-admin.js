jQuery(document).ready(function(){

    var $ = jQuery;
    
    if(typeof(Backbone) == 'undefined') return; 

    var pmieldsMapModel = Backbone.Model.extend({

        defaults:{
            address:'',
            formatted_address:'',
            pos:new google.maps.LatLng(44.643254,33.517699),
            marker:{},
            map:{},
            geocoder:new google.maps.Geocoder(),
            map_options:{
                zoom:8,
                center:new google.maps.LatLng(44.643254,33.517699),
            }
        },

        initialize:function()
        {
            this.bind('change:map_options', this.regenmap, this);
            this.bind('change:map', this.regenmarker, this);
            this.bind('change:address', this.geocodeaddress, this);
            this.bind('change:pos', this.setpositionvalues, this);
            this.bind('change:formatted_address', this.changeformatted_address, this);
        },

        setpositionvalues:function()
        {
            var p = this.get('pos');

            this.view.latitude.value = p.lat();
            this.view.longitude.value = p.lng();
            this.get('map').setCenter(p);
            // this.get('marker').setPosition(p);

        },

        changeformatted_address:function()
        {
            var a = this.get('formatted_address');
            this.view.msgblock.innerHTML = a;
        },

        regenmap:function()
        {
            var that = this;
            var o = this.get('map_options');
            var m = new google.maps.Map(that.view.mapblock, o);
            this.set({map:m});


             google.maps.event.addListener(m, 'click', function(e){
                   
                    var lat = e.latLng.lat();
                    var lng = e.latLng.lng();
                    var p = new google.maps.LatLng(lat, lng);
                    that.geocodeposition(p);
                    that.get('marker').setPosition(p);

            });
        },

        regenmarker:function(){

            var that= this;

            var markerData = {
                position:that.get('pos'),
                map:that.get('map'),
                draggable:true,
                animation: google.maps.Animation.DROP,
            };


            markerData.title = (this.get('formatted_address') != '') ? this.get('formatted_address') : 'marker title';

            var marker = new google.maps.Marker(markerData);
            this.set({marker:marker});

            google.maps.event.addListener(marker, 'dragend', function(){
                    that.geocodeposition(marker.getPosition());
            });
            
        },

        geocodeposition:function(pos)
        {
            var that = this;
            var g = new google.maps.Geocoder();
            var m = this.get('map');

            g.geocode( { latLng: pos}, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {

                    m.setCenter(results[0].geometry.location);
                    that.set({formatted_address:results[0].formatted_address, pos:results[0].geometry.location, pos:results[0].geometry.location});

                }else{
                    that.set({formatted_address:'error to geocode this address'});
                }
            });

        },

        geocodeaddress:function()
        {
            var that = this;
            var a = this.get('address');
            var g = this.get('geocoder');
            var m = this.get('map');
            var marker = this.get('marker');
 
            g.geocode( { 'address': a}, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {

                    that.view.msgblock.innerHTML = results[0].formatted_address;


                    that.set({pos:results[0].geometry.location});
                    marker.setPosition(results[0].geometry.location);

                }else{
                    that.view.msgblock.innerHTML = 'error to geocode this address';
                }
            });

        },

    });

    var pmieldsMapView = Backbone.View.extend({
        
        el:document.getElementById('pmieldswrap'),
        mapblock:document.getElementById('pmmap'),
        msgblock:document.getElementById('pmmsg'),
        latitude:document.getElementById('pmmap_latitude'),
        longitude:document.getElementById('pmmap_longitude'),

        events:{
            'click #pm_setaddress':'pm_setaddress',
        },


        initialize:function()
        {
            var that= this;

            if(!this.el)
            {
                alert('not find #pmieldswrap block');
                return;
            }

            if(!google)
            {
                alert('not load goole map');
                return;
            }

            /**
             * add fields ids nod in this view
             * @type {[type]}
             */
            if(typeof(pmapd) !== 'undefined'){

                var d = pmapd(); 

                for(var f in d)
                {
                    var node = document.getElementById(d[f].id);
                    if(node && node !== null){
                        this[f] = node;
                    }
                }

            }
            

            this.model = new pmieldsMapModel();
            this.model.view = this;

            this.init();

            this.bind('change:address', this.address_keyup, this);
            this.bind('change:latlng', this.pm_setLatLng, this);

            document.getElementById('pm_address').onkeyup = function()
            {
                that.trigger('change:address');
            }

            $('.pmLatLng').keyup(function() {
                that.trigger('change:latlng');
            });

        },

        pm_setaddress:function()
        {
            this.trigger('change:address');
        },

        pm_setLatLng:function()
        {
            if(this.latitude){
                var lat = this.latitude.value;
            }
            if(this.longitude){
                var lng = this.longitude.value;
            }

            if(lat && lng){
                var p = new google.maps.LatLng(lat,lng);
                this.model.set({pos:p});
            }

        },

        init:function()
        {
            var that = this;
            var LatLng = [];

            if(this.latitude){
                var Lat = this.latitude.value;
            }
            if(this.longitude){
                var Lng = this.longitude.value;
            }


            if(Lat !== undefined && Lat !== '') LatLng[0] = Lat;
            if(Lng !== undefined && Lng !== '') LatLng[1] = Lng;

            var baseoption = {};

            if(LatLng.length == 2)
            {
                baseoption.center = new google.maps.LatLng(LatLng[0], LatLng[1]);
            }
        

            var options = _.extend(that.model.get('map_options'), baseoption);
            this.model.set({map_options:options, pos:options.center}, {silent:true});
            this.model.trigger('change:map_options');
            this.model.trigger('change:address');

        },

        address_keyup:function()
        {
            var address = document.getElementById('pm_address').value;
            this.model.set({address:address});
        },

    });


    new pmieldsMapView();

   
});

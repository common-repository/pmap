(function($){

$(document).ready(function(){

    if(typeof(Backbone) == 'undefined') return;

    var pmModel = Backbone.Model.extend({

        initialize:function()
        {
            this.bind('change:map_options', this.regenmap, this);
            this.bind('change:map', this.regenmarker, this);
        },

        regenmarker:function()
        {
            var mOptions = this.get('map_options');
            var that= this;
            var pos = new google.maps.LatLng(mOptions.latitude,mOptions.longitude);

            var markerData = {
                position:pos,
                map:that.get('map'),
                draggable:false,
                animation: google.maps.Animation.DROP,
                title:mOptions.title
            };

            var marker = new google.maps.Marker(markerData);
            this.set({marker:marker});

        },

        regenmap:function()
        {
            var mOptions = this.get('map_options');

            var o = {

            };

            o.center = mOptions.center;
            o.zoom = mOptions.zoom;

                if(o.center == 'marker')
                {
                    o.center = new google.maps.LatLng(mOptions.latitude,mOptions.longitude);
                }

            
            var that = this;
            var m = new google.maps.Map(that.view.el, o);
            this.set({map:m});

        }

    });

    var pmView = Backbone.View.extend({


        initialize:function()
        {
            this.model = new pmModel();
            this.model.view = this;

            var d = 'd_pmap_'+this.el.getAttribute('data-map_id');
            var map_data = document.getElementById(d);
            if(map_data)
            {
                map_data = map_data.innerHTML;
                map_data = map_data.split(' ').join('');
                map_data = JSON.parse(map_data);
                this.model.set({map_options:map_data});
            }
            
        }

    });

    var maps = $('.pmap');

    for(var i=0;i<maps.length;i++)
    {
        new pmView({el:maps[i]});
    }

    delete maps;

    

});

})(jQuery);